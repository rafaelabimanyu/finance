<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        // Default query with eager loading to prevent N+1 issues
        $query = Transaction::with(['category', 'user']);

        // Keyword Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by Type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date,
                $request->end_date
            ]);
        } elseif ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        // Order and Paginate
        $transactions = $query->latest('transaction_date')
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('transactions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Transaction::create($data);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi keuangan berhasil dicatat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $categories = Category::orderBy('name')->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi keuangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi keuangan berhasil dihapus.');
    }

    /**
     * Export the filtered transaction records to a CSV file.
     */
    public function export(Request $request)
    {
        // 1. Get filtered transactions (similar to index query)
        $query = Transaction::with(['category', 'user']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [
                $request->start_date,
                $request->end_date
            ]);
        } elseif ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->latest('transaction_date')
            ->latest('created_at')
            ->get();

        // 2. Calculate totals within range for CSV header summary
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenseTx = $transactions->where('type', 'expense')->sum('amount');

        // Security boundaries: Only show payroll details and net profit to Owner
        $isOwner = auth()->user()->role === 'owner';
        $totalPayroll = 0;

        if ($isOwner) {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
            $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
            $startPeriod = Carbon::parse($startDate)->format('Y-m');
            $endPeriod = Carbon::parse($endDate)->format('Y-m');
            $totalPayroll = Payroll::whereBetween('period', [$startPeriod, $endPeriod])->sum('base_salary');
        }

        $fileName = 'laporan_keuangan_' . now()->format('Ymd_His') . '.csv';

        // Headers for downloading file
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transactions, $totalIncome, $totalExpenseTx, $totalPayroll, $isOwner) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write Title
            fputcsv($file, ['LAPORAN KEUANGAN INTERNAL J&J GROUP']);
            fputcsv($file, ['Tanggal Ekspor', now()->isoFormat('D MMMM YYYY H:i:s')]);
            fputcsv($file, ['Unduh Oleh', auth()->user()->name . ' (' . strtoupper(auth()->user()->role) . ')']);
            fputcsv($file, []);

            // Summary Row
            fputcsv($file, ['RINGKASAN LAPORAN']);
            fputcsv($file, ['Total Omset Kotor (Income)', 'Rp ' . number_format($totalIncome, 2, ',', '.')]);
            fputcsv($file, ['Total OPEX Transaksi (Expense)', 'Rp ' . number_format($totalExpenseTx, 2, ',', '.')]);
            
            if ($isOwner) {
                $totalExpense = $totalExpenseTx + $totalPayroll;
                $netProfit = $totalIncome - $totalExpense;
                fputcsv($file, ['Total Gaji & Payroll', 'Rp ' . number_format($totalPayroll, 2, ',', '.')]);
                fputcsv($file, ['Total Pengeluaran (OPEX + Payroll)', 'Rp ' . number_format($totalExpense, 2, ',', '.')]);
                fputcsv($file, ['Pendapatan Bersih (Laba/Rugi)', 'Rp ' . number_format($netProfit, 2, ',', '.')]);
            } else {
                fputcsv($file, ['Total Pengeluaran (Hanya OPEX)', 'Rp ' . number_format($totalExpenseTx, 2, ',', '.')]);
                fputcsv($file, ['Pendapatan Bersih', 'Akses Dibatasi (Owner Only)']);
            }
            
            fputcsv($file, []);

            // Header for CSV data table
            fputcsv($file, ['Tanggal', 'Judul Transaksi', 'Kategori', 'Tipe', 'Nominal', 'Pencatat']);

            // Write transaction rows
            foreach ($transactions as $tx) {
                fputcsv($file, [
                    $tx->transaction_date->format('Y-m-d'),
                    $tx->title,
                    $tx->category->name,
                    $tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
                    $tx->amount,
                    $tx->user->name
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

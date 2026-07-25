<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

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
}

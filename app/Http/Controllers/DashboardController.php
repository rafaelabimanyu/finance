<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard summary page.
     */
    public function index(Request $request)
    {
        // Default to current month
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $totalCategories = Category::count();
        $totalUsers = User::count();

        // 1. Total Omset Kotor (Sum of income transactions)
        $totalIncome = Transaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // 2. Total Pengeluaran Transaksi (Sum of expense transactions)
        $totalExpenseTransactions = Transaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        // Convert dates to YYYY-MM periods for payroll calculations
        $startPeriod = Carbon::parse($startDate)->format('Y-m');
        $endPeriod = Carbon::parse($endDate)->format('Y-m');

        // 3. Total Payrolls in Period
        $totalPayrollsCount = Payroll::whereBetween('period', [$startPeriod, $endPeriod])->count();
        $totalSalaryPaid = Payroll::where('status', 'paid')
            ->whereBetween('period', [$startPeriod, $endPeriod])
            ->sum('base_salary');
        $totalSalaryPending = Payroll::where('status', 'pending')
            ->whereBetween('period', [$startPeriod, $endPeriod])
            ->sum('base_salary');

        $totalPayrollExpense = $totalSalaryPaid + $totalSalaryPending;

        // 4. Total Expense (OPEX + Payroll)
        $totalExpense = $totalExpenseTransactions + $totalPayrollExpense;

        // 5. Net Profit / Laba Bersih
        $netProfit = $totalIncome - $totalExpense;

        // 6. Chart Data: Monthly trend for the past 12 months (including the current month)
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];

        for ($i = 11; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $monthKey = $monthDate->format('Y-m');
            
            $chartLabels[] = $monthDate->locale('id')->translatedFormat('F Y');

            $startOfMonth = $monthDate->copy()->startOfMonth()->toDateString();
            $endOfMonth = $monthDate->copy()->endOfMonth()->toDateString();

            // Sum income transactions in this month
            $inc = Transaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
            
            // Sum expense transactions in this month
            $expTx = Transaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            // Sum payroll base salary in this month
            $payExp = Payroll::where('period', $monthKey)
                ->sum('base_salary');

            $chartIncome[] = (float) $inc;
            $chartExpense[] = (float) ($expTx + $payExp);
        }

        return view('dashboard', compact(
            'totalCategories',
            'totalUsers',
            'totalIncome',
            'totalExpenseTransactions',
            'totalPayrollExpense',
            'totalSalaryPaid',
            'totalSalaryPending',
            'totalExpense',
            'netProfit',
            'startDate',
            'endDate',
            'totalPayrollsCount',
            'chartLabels',
            'chartIncome',
            'chartExpense'
        ));
    }
}

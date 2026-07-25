<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Payroll;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Show the dashboard summary page.
     */
    public function index()
    {
        $totalCategories = Category::count();
        $totalPayrolls = Payroll::count();
        $totalUsers = User::count();
        
        $totalSalaryPaid = Payroll::where('status', 'paid')->sum('base_salary');
        $totalSalaryPending = Payroll::where('status', 'pending')->sum('base_salary');

        return view('dashboard', compact(
            'totalCategories',
            'totalPayrolls',
            'totalUsers',
            'totalSalaryPaid',
            'totalSalaryPending'
        ));
    }
}

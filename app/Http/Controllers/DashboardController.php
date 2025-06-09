<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Transaction::where('user_id', $user->id);

        // Overall totals (all time)
        $totalCashIn = (clone $query)->where('type', 'cash_in')->sum('amount');
        $totalCashOut = (clone $query)->where('type', 'cash_out')->sum('amount');

        // Yearly totals
        $totalCashInYearly = (clone $query)
            ->where('type', 'cash_in')
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        $totalCashOutYearly = (clone $query)
            ->where('type', 'cash_out')
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        // Monthly totals
        $totalCashInMonthly = (clone $query)
            ->where('type', 'cash_in')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        $totalCashOutMonthly = (clone $query)
            ->where('type', 'cash_out')
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->sum('amount');

        // Weekly totals
        $totalCashInWeekly = (clone $query)
            ->where('type', 'cash_in')
            ->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');

        $totalCashOutWeekly = (clone $query)
            ->where('type', 'cash_out')
            ->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');

        // Daily totals
        $totalCashInDaily = (clone $query)
            ->where('type', 'cash_in')
            ->whereDate('transaction_date', now()->toDateString())
            ->sum('amount');

        $totalCashOutDaily = (clone $query)
            ->where('type', 'cash_out')
            ->whereDate('transaction_date', now()->toDateString())
            ->sum('amount');

        return view('dashboard', compact(
            'totalCashIn', 'totalCashOut',
            'totalCashInYearly', 'totalCashOutYearly',
            'totalCashInMonthly', 'totalCashOutMonthly',
            'totalCashInWeekly', 'totalCashOutWeekly',
            'totalCashInDaily', 'totalCashOutDaily'
        ));
    }


}

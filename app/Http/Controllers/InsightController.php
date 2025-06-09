<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InsightController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $now = Carbon::now();
        $last12Months = $now->copy()->subMonths(11)->startOfMonth();
        $last30Days = $now->copy()->subDays(29)->startOfDay();

        // --- Total Cash In and Cash Out ---
        $totals = DB::table('transactions')
            ->selectRaw('
                SUM(CASE WHEN type = "cash_in" THEN amount ELSE 0 END) as total_cash_in,
                SUM(CASE WHEN type = "cash_out" THEN amount ELSE 0 END) as total_cash_out
            ')
            ->where('user_id', $userId)
            ->first();

        $totalCashIn = floatval($totals->total_cash_in ?? 0);
        $totalCashOut = floatval($totals->total_cash_out ?? 0);
        $balance = $totalCashIn - $totalCashOut;

        // --- Category breakdown for Cash In ---
        $cashInData = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('SUM(transactions.amount) as total'))
            ->where('transactions.type', 'cash_in')
            ->where('transactions.user_id', $userId)
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // --- Category breakdown for Cash Out ---
        $cashOutData = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('SUM(transactions.amount) as total'))
            ->where('transactions.type', 'cash_out')
            ->where('transactions.user_id', $userId)
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // --- Monthly trends for last 12 months ---
        $rawMonthly = DB::table('transactions')
            ->selectRaw('
                DATE_FORMAT(transaction_date, "%Y-%m") as month,
                SUM(CASE WHEN type = "cash_in" THEN amount ELSE 0 END) as cash_in,
                SUM(CASE WHEN type = "cash_out" THEN amount ELSE 0 END) as cash_out
            ')
            ->where('user_id', $userId)
            ->whereBetween('transaction_date', [$last12Months->toDateString(), $now->endOfDay()->toDateTimeString()])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyTrend = [];
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $last12Months->copy()->addMonths($i)->format('Y-m');
            $months[] = $month;

            if (isset($rawMonthly[$month])) {
                $monthlyTrend[] = [
                    'month' => $month,
                    'cash_in' => floatval($rawMonthly[$month]->cash_in),
                    'cash_out' => floatval($rawMonthly[$month]->cash_out),
                ];
            } else {
                $monthlyTrend[] = [
                    'month' => $month,
                    'cash_in' => 0,
                    'cash_out' => 0,
                ];
            }
        }

        // --- Top 5 spending categories (Cash Out) ---
        $topSpendingCategories = $cashOutData->take(5);

        // --- Daily cash flow for last 30 days ---
        $rawDaily = DB::table('transactions')
            ->selectRaw('
                DATE(transaction_date) as date,
                SUM(CASE WHEN type = "cash_in" THEN amount ELSE 0 END) as cash_in,
                SUM(CASE WHEN type = "cash_out" THEN amount ELSE 0 END) as cash_out
            ')
            ->where('user_id', $userId)
            ->whereBetween('transaction_date', [$last30Days->toDateString(), $now->endOfDay()->toDateTimeString()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyFlow = [];
        $dailyDates = [];
        for ($i = 0; $i < 30; $i++) {
            $date = $last30Days->copy()->addDays($i)->format('Y-m-d');
            $dailyDates[] = $date;

            if (isset($rawDaily[$date])) {
                $dailyFlow[] = [
                    'date' => $date,
                    'cash_in' => floatval($rawDaily[$date]->cash_in),
                    'cash_out' => floatval($rawDaily[$date]->cash_out),
                ];
            } else {
                $dailyFlow[] = [
                    'date' => $date,
                    'cash_in' => 0,
                    'cash_out' => 0,
                ];
            }
        }

        return view('insights', [
            'totalCashIn' => $totalCashIn,
            'totalCashOut' => $totalCashOut,
            'balance' => $balance,
            'cashInData' => $cashInData,
            'cashOutData' => $cashOutData,
            'monthlyTrend' => $monthlyTrend,
            'months' => $months,
            'topSpendingCategories' => $topSpendingCategories,
            'dailyFlow' => $dailyFlow,
            'dailyDates' => $dailyDates,
        ]);
    }
}

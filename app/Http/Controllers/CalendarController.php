<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Get month and year from query parameters
        $year = $request->query('year');
        $month = $request->query('month');

        if ($year && $month) {
            // Create Carbon instance from separate year and month
            $currentMonth = Carbon::createFromDate($year, $month, 1);
        } else {
            // fallback to current month if no query params
            $currentMonth = Carbon::now()->startOfMonth();
        }

        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Fetch transactions for the entire month
        $transactions = DB::table('transactions')
            ->selectRaw('DATE(transaction_date) as date')
            ->selectRaw('SUM(CASE WHEN type = "cash_in" THEN amount ELSE 0 END) as cash_in')
            ->selectRaw('SUM(CASE WHEN type = "cash_out" THEN amount ELSE 0 END) as cash_out')
            ->whereBetween('transaction_date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->groupBy(DB::raw('DATE(transaction_date)'))
            ->orderBy('date')
            ->get();

        $data = [];
        $totalCashIn = 0;
        $totalCashOut = 0;
        foreach ($transactions as $t) {
            $data[$t->date] = [
                'cash_in' => $t->cash_in,
                'cash_out' => $t->cash_out,
            ];

            $totalCashIn += $t->cash_in;
            $totalCashOut += $t->cash_out;
        }

        $balance = $totalCashIn - $totalCashOut;

        return view('calendar', compact('data', 'currentMonth','totalCashIn','totalCashOut', 'balance'));
    }
}

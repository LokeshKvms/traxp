<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Read month from query param or default to current month (format: Y-m)
        $month = $request->query('month', Carbon::now()->format('Y-m'));

        // Create Carbon instance from month string
        $currentMonth = Carbon::createFromFormat('Y-m', $month);

        // Get start and end of the current month
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
        foreach ($transactions as $t) {
            $data[$t->date] = [
                'cash_in' => $t->cash_in,
                'cash_out' => $t->cash_out,
            ];
        }

        // Pass data and current month to the view
        return view('calendar', compact('data', 'currentMonth'));
    }
}

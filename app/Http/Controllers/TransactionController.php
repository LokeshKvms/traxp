<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');
        $categoryId = $request->get('category_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $export = $request->get('export');

        $query = Transaction::with('category')->where('user_id', $user->id);

        // Apply filters
        switch ($filter) {
            case 'yearly':
                $query->whereYear('transaction_date', now()->year);
                break;
            case 'monthly':
                $query->whereYear('transaction_date', now()->year)
                    ->whereMonth('transaction_date', now()->month);
                break;
            case 'weekly':
                $query->whereBetween('transaction_date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'daily':
                $query->whereDate('transaction_date', now()->toDateString());
                break;
            case 'custom':
                if ($startDate && $endDate) {
                    $query->whereBetween('transaction_date', [$startDate, $endDate]);
                }
                break;
            case 'category':
                if ($categoryId) {
                    $query->where('category_id', $categoryId);
                }
                break;
        }

        // Search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('desc', 'like', '%' . $search . '%')
                    ->orWhereHas('category', fn($q2) => $q2->where('name', 'like', '%' . $search . '%'));
            });
        }

        // Clone query before pagination for total calculations
        $totalsQuery = clone $query;
        $totalCashIn = $totalsQuery->where('type', 'cash_in')->sum('amount');
        $totalCashOut = (clone $query)->where('type', 'cash_out')->sum('amount');
        $balance = $totalCashIn - $totalCashOut;

        // Export CSV
        if ($export === 'csv') {
            $transactions = $query->orderBy('transaction_date', 'desc')->get();
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="transactions.csv"',
            ];

            $callback = function () use ($transactions, $totalCashIn, $totalCashOut, $balance) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Date & Time', 'Category', 'Reason', 'Cash In', 'Cash Out']);

                foreach ($transactions as $t) {
                    fputcsv($file, [
                        $t->id,
                        $t->transaction_date,
                        $t->category->name ?? 'N/A',
                        $t->desc,
                        $t->type === 'cash_in' ? $t->amount : '',
                        $t->type === 'cash_out' ? $t->amount : '',
                    ]);
                }
                fputcsv($file, []);
                fputcsv($file, ['', '', '', 'Total Cash In', $totalCashIn]);
                fputcsv($file, ['', '', '', 'Total Cash Out', '', $totalCashOut]);
                fputcsv($file, ['', '', '', 'Balance', $balance]);
                fclose($file);
            };
            return Response::stream($callback, 200, $headers);
        }

        $transactions = $query->orderBy('transaction_date', direction: 'desc')->paginate(5)->withQueryString();
        $categories = Category::orderBy('type')->get();

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'filter',
            'search',
            'categoryId',
            'startDate',
            'endDate',
            'totalCashIn',
            'totalCashOut',
            'balance'
        ));
    }


    public function create()
    {
        // $categories = Category::orderBy('name')->get();
        $categories = Category::orderBy('name')->get()->groupBy('type');
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:cash_in,cash_out',
            'amount' => 'required|numeric|min:0.01',
            'desc' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            // 'category_id' => 'required|exists:categories,id',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) use ($request) {
                    $query->where('type', $request->type);
                }),
            ],
        ]);

        $request->user()->transactions()->create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction added successfully.');
    }

    public function show($id)
    {
        $transaction = Transaction::with('category')->where('user_id', auth()->id())->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        // $categories = Category::orderBy('name')->get();
        $categories = Category::orderBy('name')->get()->groupBy('type');
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:cash_in,cash_out',
            'amount' => 'required|numeric|min:0.01',
            'desc' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}

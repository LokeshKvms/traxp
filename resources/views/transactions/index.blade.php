<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Transactions</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4 flex flex-wrap items-center gap-4">
            {{-- Search --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex space-x-2 items-center">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="rounded border-gray-300" />
                <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded">Search</button>
            </form>

            {{-- Export --}}
            <form method="GET" action="{{ route('transactions.index') }}">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input type="hidden" name="search" value="{{ $search }}">
                <input type="hidden" name="export" value="csv">
                @if ($filter === 'custom')
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                @endif
                @if ($filter === 'category')
                    <input type="hidden" name="category_id" value="{{ $categoryId }}">
                @endif
                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">Export CSV</button>
            </form>
        </div>

        {{-- Filter Buttons --}}
        <div class="flex gap-2 mb-4">
            @foreach (['all', 'yearly', 'monthly', 'weekly', 'daily'] as $option)
                <a href="{{ route('transactions.index', ['filter' => $option]) }}"
                   class="px-3 py-1 rounded {{ $filter === $option ? 'bg-indigo-700 text-white' : 'bg-gray-200' }}">
                    {{ ucfirst($option) }}
                </a>
            @endforeach

            {{-- Custom Date Range --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="filter" value="custom">
                <input type="date" name="start_date" value="{{ $startDate }}" class="border rounded px-2">
                <input type="date" name="end_date" value="{{ $endDate }}" class="border rounded px-2">
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Apply</button>
            </form>

            {{-- Category Filter --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="filter" value="category">
                <select name="category_id" class="border rounded px-2">
                    <option value="">Choose Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded">Filter</button>
            </form>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white shadow overflow-x-auto rounded">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Reason</th>
                        <th class="px-4 py-2 text-right">Cash In</th>
                        <th class="px-4 py-2 text-right">Cash Out</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $t)
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('transactions.show', $t) }}'">
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d h:i A') }}</td>
                            <td class="px-4 py-2">{{ $t->category->name ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $t->desc }}</td>
                            <td class="px-4 py-2 text-right">{{ $t->type === 'cash_in' ? number_format($t->amount, 2) : '0.00' }}</td>
                            <td class="px-4 py-2 text-right">{{ $t->type === 'cash_out' ? number_format($t->amount, 2) : '0.00' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>

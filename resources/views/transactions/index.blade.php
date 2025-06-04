<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">All Transactions</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Controls (Search + Export) --}}
        <div class="mb-6 flex flex-wrap items-center gap-4">
            {{-- Search --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex space-x-2 items-center">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}" 
                    placeholder="Search transactions..." 
                    class="rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition">
                    Search
                </button>
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
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition">
                    Export CSV
                </button>
            </form>
        </div>

        {{-- Filter Buttons --}}
        <div class="flex flex-wrap gap-3 mb-6 items-center">
            @foreach (['all', 'yearly', 'monthly', 'weekly', 'daily'] as $option)
                <a 
                    href="{{ route('transactions.index', ['filter' => $option]) }}"
                    class="px-4 py-2 rounded-md font-semibold transition
                        {{ $filter === $option ? 'bg-indigo-700 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }}"
                >
                    {{ ucfirst($option) }}
                </a>
            @endforeach

            {{-- Custom Date Range --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="filter" value="custom">
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ $startDate }}" 
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
                <span class="text-gray-500 font-semibold select-none">to</span>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ $endDate }}" 
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                >
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition">
                    Apply
                </button>
            </form>

            {{-- Category Filter --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-2" id="categoryFilterForm">
                <input type="hidden" name="filter" value="category">
                <select 
                    name="category_id" 
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                    onchange="document.getElementById('categoryFilterForm').submit()"
                >
                    <option value="">Choose Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>

        </div>

        {{-- Transactions Table --}}
        <div class="bg-white shadow rounded-lg overflow-x-auto border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-center">
                <thead class="bg-gray-800 text-center">
                    <tr>
                        <th class="px-6 py-3 font-semibold text-white">S. No</th>
                        <th class="px-6 py-3 font-semibold text-white">Date</th>
                        <th class="px-6 py-3 font-semibold text-white">Category</th>
                        <th class="px-6 py-3 font-semibold text-white">Reason</th>
                        <th class="px-6 py-3 font-semibold text-white">Cash In</th>
                        <th class="px-6 py-3 font-semibold text-white">Cash Out</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-gray-300">
                    @forelse ($transactions as $t)
                        <tr 
                            class="hover:bg-gray-100 cursor-pointer transition" 
                            onclick="window.location='{{ route('transactions.show', $t) }}'"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($t->transaction_date)->format('Y-m-d h:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $t->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $t->desc ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600">{{ $t->type === 'cash_in' ? '+ ₹'.number_format($t->amount, 2) : '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-red-600">{{ $t->type === 'cash_out' ? '- ₹'. number_format($t->amount, 2) : '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500 italic">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>

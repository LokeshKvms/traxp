@php
    $balanceClass = $balance < 0 ? 'text-red-600' : ($balance > 0 ? 'text-green-600' : 'text-gray-900');
    $balanceBg = $balance < 0 ? 'bg-rose-50' : ($balance > 0 ? 'bg-teal-50' : 'bg-gray-50');

@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Your Transactions</h2>
            <button type="button"
                class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-md font-semibold transition"
                onclick="window.location='{{ route('transactions.create') }}'">
                Add Transaction
            </button>
        </div>
    </x-slot>

    <div class="py-4 max-w-7xl px-4 mx-auto md:px-6 lg:px-8">

        {{-- Filter Buttons --}}
        <div
            class="flex flex-wrap lg:flex-nowrap gap-4 p-4 mb-6 border border-gray-300 bg-white rounded-md shadow-sm items-center overflow-hidden min-w-0">
            {{-- Dates Label --}}
            <label class="flex gap-2 w-full lg:w-auto items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z" />
                </svg>
                <span class="font-semibold text-gray-700">Dates:</span>
            </label>

            {{-- Filters + Custom Button --}}
            <div class="flex flex-wrap lg:flex-nowrap gap-2 items-center w-full lg:w-auto">
                <!-- Link buttons for md+ -->
                <div class="hidden lg:flex space-x-2">
                    @foreach (['all', 'yearly', 'monthly', 'weekly', 'daily'] as $option)
                        <a href="{{ route('transactions.index', ['filter' => $option]) }}"
                            class="px-4 py-2 rounded-md font-semibold transition
                            {{ $filter === $option ? 'bg-gray-900 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }}">
                            {{ ucfirst($option) }}
                        </a>
                    @endforeach
                    <button type="button" id="openCustomDateModal"
                        class="px-4 py-2 min-w-1/3 rounded-md font-semibold transition
                    {{ $filter === 'custom' ? 'bg-gray-900 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }}">
                        Custom
                    </button>
                </div>

                <!-- Dropdown for small screens -->
                <div class="lg:hidden min-w-full flex gap-3">
                    <select onchange="window.location.href=this.value"
                        class="block w-2/3 px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-gray-300">
                        @foreach (['all', 'yearly', 'monthly', 'weekly', 'daily'] as $option)
                            <option value="{{ route('transactions.index', ['filter' => $option]) }}"
                                {{ $filter === $option ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" id="openCustomDateModal"
                        class="px-4 py-2 w-1/3 rounded-md font-semibold transition
                    {{ $filter === 'custom' ? 'bg-gray-900 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }}">
                        Custom
                    </button>
                </div>
            </div>

            {{-- Category Filter --}}
            <form method="GET" action="{{ route('transactions.index') }}"
                class="flex flex-col lg:flex-row items-start lg:items-center gap-2 lg:ml-auto w-full lg:w-auto"
                id="categoryFilterForm">
                <input type="hidden" name="filter" value="category">
                <label for="category_id" class="flex gap-2 items-center text-gray-700 font-semibold">
                    Categories:
                </label>
                <select name="category_id" id="category_id"
                    class="border border-gray-300 rounded-md px-3 py-2 w-full lg:w-60 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition"
                    onchange="document.getElementById('categoryFilterForm').submit()">
                    <option value="" disabled selected class="text-gray-400">Choose Category</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            class="{{ $cat->type === 'cash_in' ? 'bg-teal-50' : 'bg-red-50' }}"
                            @selected($categoryId == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Custom Date Range Modal -->
        <div id="customDateModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
            aria-hidden="true">
            <div class="bg-white rounded-md shadow-lg max-w-md w-full p-6 relative">
                <button type="button" id="closeCustomDateModal"
                    class="absolute top-3 right-3 text-gray-600 hover:text-gray-900" aria-label="Close modal">
                    &times;
                </button>
                <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-col gap-4">
                    <input type="hidden" name="filter" value="custom">
                    <div>
                        <label for="start_date" class="block mb-1 font-semibold text-gray-700">Start Date</label>
                        <input id="start_date" type="date" name="start_date" value="{{ $startDate }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                    </div>
                    <div>
                        <label for="end_date" class="block mb-1 font-semibold text-gray-700">End Date</label>
                        <input id="end_date" type="date" name="end_date" value="{{ $endDate }}"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            required>
                    </div>
                    <button type="submit"
                        class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-md font-medium transition">
                        Apply
                    </button>
                </form>
            </div>
        </div>

        {{-- Controls (Search + Export) --}}
        <div class="mb-4 flex flex-col md:flex-row gap-4 border border-gray-300 bg-white rounded-md shadow-sm p-4">

            {{-- Export --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="md:w-auto w-full">
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

                <button type="submit"
                    class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-md font-semibold transition">
                    Export
                </button>
            </form>

            {{-- Search --}}
            <form method="GET" action="{{ route('transactions.index') }}"
                class="flex flex-col md:flex-row gap-2 flex-1 w-full">

                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search transactions..."
                    class="w-full rounded-md border border-gray-300 bg-gray-100 text-gray-900 placeholder-gray-400 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition" />

                <div class="flex gap-2">
                    <button type="submit"
                        class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-md font-semibold transition"
                        aria-label="Search transactions">
                        Search
                    </button>

                    <button type="button"
                        class="w-full md:w-auto bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-md font-semibold transition"
                        onclick="window.location='{{ route('transactions.index') }}'">
                        Clear
                    </button>
                </div>
            </form>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white shadow rounded-lg overflow-x-auto border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-center" id='expTable'>
                <thead class="bg-gray-800 text-center">
                    <tr>
                        <th class="px-6 py-3 font-semibold text-white cursor-pointer hidden md:table-cell">S. No</th>
                        <th class="px-6 py-3 font-semibold text-white cursor-pointer hidden md:table-cell">Date</th>
                        <th class="px-6 py-3 font-semibold text-white cursor-pointer">Category</th>
                        <th class="px-6 py-3 font-semibold text-white cursor-pointer hidden md:table-cell">Reason</th>
                        <th class="px-6 py-3 font-semibold text-white cursor-pointer">Cash In</th>
                        <th class="px-6 py-3 font-semibold text-white cursor-pointer">Cash Out</th>
                    </tr>
                </thead>

                <tbody class="divide-y-2 divide-gray-300">
                    @forelse ($transactions as $t)
                        <tr class="hover:bg-gray-100 cursor-pointer transition"
                            onclick="window.location='{{ route('transactions.show', $t) }}'">

                            {{-- S. No - hidden on md and smaller --}}
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>

                            {{-- Date - hidden on md and smaller --}}
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                {{ \Carbon\Carbon::parse($t->transaction_date)->format('F j, Y') }}<br>
                                {{ \Carbon\Carbon::parse($t->transaction_date)->format('h:i A') }}
                            </td>

                            {{-- Category + Date (visible only on md and smaller) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-left md:hidden">
                                <div class="font-semibold">{{ $t->category->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">
                                    <span class="block sm:inline">
                                        {{ \Carbon\Carbon::parse($t->transaction_date)->format('F j, Y') }},
                                    </span>
                                    <span class="block sm:inline">
                                        {{ \Carbon\Carbon::parse($t->transaction_date)->format('h:i A') }}
                                    </span>
                                </div>

                            </td>

                            {{-- Category only for larger screens --}}
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                {{ $t->category->name ?? '-' }}</td>

                            {{-- Reason - hidden on md and smaller --}}
                            <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">{{ $t->desc ?? '-' }}</td>

                            <td class="px-6 py-4 whitespace-nowrap font-semibold bg-green-50 text-green-600">
                                {{ $t->type === 'cash_in' ? '+ ₹' . App\Helpers\NumberFormatter::formatIndianNumber($t->amount, 2) : '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-semibold bg-red-50 text-red-600">
                                {{ $t->type === 'cash_out' ? '- ₹' . App\Helpers\NumberFormatter::formatIndianNumber($t->amount, 2) : '' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-md text-gray-500 italic font-bold">No
                                transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-balance-summary-bar :$totalCashIn :$totalCashOut :$balance />
        {{-- Pagination --}}
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>

<script>
    const openModalBtn = document.getElementById('openCustomDateModal');
    const closeModalBtn = document.getElementById('closeCustomDateModal');
    const modal = document.getElementById('customDateModal');

    openModalBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
    });

    // Optional: close modal when clicking outside modal content
    modal.addEventListener('click', e => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }
    });
</script>

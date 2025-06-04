<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">All Transactions</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        {{-- Controls (Search + Export) --}}
        <div class="mb-6 flex items-center gap-4 border border-gray-300 bg-white p-4 rounded-md shadow-sm">
            {{-- Search --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex space-x-2 items-center flex-1">
                
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}" 
                    placeholder="Search transactions..." 
                    class="w-full rounded-md border border-gray-300 bg-white text-gray-900 placeholder-gray-400 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400 transition"
                />

                <button 
                    type="submit" 
                    class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-md font-semibold transition"
                    aria-label="Search transactions"
                >
                Search
                </button>
            </form>

            {{-- Export --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex-shrink-0">
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
                
                <button 
                    type="submit" 
                    class="bg-gray-900 hover:bg-gray-800 text-white px-5 py-2 rounded-md font-semibold transition"
                    aria-label="Export transactions as CSV"
                >
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
                    {{ $filter === $option 
                    ? 'bg-gray-900 text-white' 
                    : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }}"
                >
                {{ ucfirst($option) }}
                </a>
            @endforeach

            {{-- Custom Date Range Button --}}
            <button
                type="button"
                id="openCustomDateModal"
                class="px-4 py-2 rounded-md font-semibold transition
                {{ $filter === 'custom' 
                    ? 'bg-gray-900 text-white' 
                    : 'bg-gray-200 hover:bg-gray-300 text-gray-700' }}"
            >
                Custom
            </button>

            {{-- Category Filter --}}
            <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-2" id="categoryFilterForm">
                <input type="hidden" name="filter" value="category">
                <select 
                    name="category_id" 
                    class="border border-gray-300 rounded-md px-3 py-2 w-60 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                    onchange="document.getElementById('categoryFilterForm').submit()"
                >
                <option value="" disabled selected class="text-gray-400">Choose Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected($categoryId == $cat->id)>{{ $cat->name }}</option>
                @endforeach
                </select>
            </form>
            </div>

            <!-- Custom Date Range Modal -->
            <div
            id="customDateModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
            aria-hidden="true"
            >
            <div class="bg-white rounded-md shadow-lg max-w-md w-full p-6 relative">
                <button
                type="button"
                id="closeCustomDateModal"
                class="absolute top-3 right-3 text-gray-600 hover:text-gray-900"
                aria-label="Close modal"
                >
                &times;
                </button>
                <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-col gap-4">
                <input type="hidden" name="filter" value="custom">
                <div>
                    <label for="start_date" class="block mb-1 font-semibold text-gray-700">Start Date</label>
                    <input 
                    id="start_date"
                    type="date" 
                    name="start_date" 
                    value="{{ $startDate }}" 
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    required
                    >
                </div>
                <div>
                    <label for="end_date" class="block mb-1 font-semibold text-gray-700">End Date</label>
                    <input 
                    id="end_date"
                    type="date" 
                    name="end_date" 
                    value="{{ $endDate }}" 
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    required
                    >
                </div>
                <button 
                    type="submit" 
                    class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-md font-medium transition"
                >
                    Apply
                </button>
                </form>
            </div>
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
                            <td colspan="6" class="text-center py-6 text-md text-gray-500 italic font-bold">No transactions found</td>
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
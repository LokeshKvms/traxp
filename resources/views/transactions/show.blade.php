<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-black leading-tight">Transaction Details</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex flex-col">
        <h1 class="text-2xl sm:text-3xl font-bold mb-4 text-center">
            @if ($transaction->type === 'cash_in')
                Income
            @else
                Expense
            @endif
        </h1>

        <div class="bg-white shadow-md rounded-lg p-6 sm:p-8 flex flex-col md:flex-row md:justify-between md:items-start">
            {{-- Left Column --}}
            <div class="md:w-1/2 space-y-6 md:space-y-8 md:border-r md:border-gray-200 md:pr-8 mb-6 md:mb-0">
                <div>
                    <h3 class="text-gray-800 text-lg font-semibold mb-1">Date &amp; Time</h3>
                    <p class="text-gray-700">
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d h:i A') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-gray-800 text-lg font-semibold mb-1">Category</h3>
                    <p class="text-gray-700">{{ $transaction->category->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-800 text-lg font-semibold mb-1">Description</h3>
                    <p class="text-gray-700">{{ $transaction->desc ?? '-' }}</p>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="md:w-1/2 my-auto flex flex-col items-center md:items-end">
                <div class="mb-6 text-center md:text-right">
                    <h3 class="text-gray-800 text-lg font-semibold mb-2">Amount</h3>
                    <p class="text-black font-extrabold text-3xl sm:text-4xl">
                        @if ($transaction->type === 'cash_in')
                            + ₹ <span class="text-green-600">{{ number_format($transaction->amount, 2) }}</span>
                        @else
                            - ₹ <span class="text-red-600">{{ number_format($transaction->amount, 2) }}</span>
                        @endif
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row flex-wrap justify-center md:justify-end gap-3 w-full md:w-auto">
                    <a href="{{ route('transactions.edit', $transaction) }}"
                        class="bg-black text-white text-center px-6 py-3 rounded-md font-semibold hover:bg-gray-800 transition w-full sm:w-auto">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                        onsubmit="return confirm('Are you sure you want to delete this transaction?');"
                        class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto bg-gray-300 text-black px-6 py-3 rounded-md font-semibold hover:bg-gray-400 transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('transactions.index') }}"
                class="text-gray-700 hover:text-black hover:underline font-medium">
                ← Back to all transactions
            </a>
        </div>
    </div>
</x-app-layout>

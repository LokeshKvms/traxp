<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-black leading-tight">Transaction Details</h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-5 px-6 sm:px-8 lg:px-10 flex flex-col">
        <h1 class="text-3xl font-bold mb-3 text-center">
            @if ($transaction->type === 'cash_in')
                Income
            @else
                Expense
            @endif
        </h1>
        <div class="bg-white shadow-md rounded-lg p-8 flex flex-col md:flex-row md:justify-between md:items-start">
            {{-- Left Column --}}
            <div class="md:w-1/2 space-y-8 border-r border-gray-200 pr-8">
                <div>
                    <h3 class="text-gray-800 text-lg font-semibold mb-1">Date &amp; Time</h3>
                    <p class="text-gray-700">
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d h:i A') }}</p>
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
            <div class="md:w-1/2 md:mt-0 flex flex-col items-center md:items-end">
                <div class="my-8 text-center md:text-right">
                    <h3 class="text-gray-800 text-lg font-semibold mb-2">Amount</h3>
                    <p class="text-black font-extrabold text-4xl">
                        @if ($transaction->type === 'cash_in')
                            <label> + ₹ <span
                                    class="text-green-600">{{ number_format($transaction->amount, 2) }}</span></label>
                        @else
                            <label> - ₹ <span
                                    class="text-red-600">{{ number_format($transaction->amount, 2) }}</span></label>
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap justify-center md:justify-end space-x-4 space-y-2 md:space-y-0">
                    <a href="{{ route('transactions.edit', $transaction) }}"
                        class="inline-block bg-black text-white px-6 py-3 rounded-md font-semibold hover:bg-gray-800 transition">
                        Edit
                    </a>

                    <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                        onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-block bg-gray-300 text-black px-6 py-3 rounded-md font-semibold hover:bg-gray-400 transition">
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

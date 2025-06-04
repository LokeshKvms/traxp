<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transaction Details</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">

            <div class="mb-4">
                <strong>Date & Time:</strong>
                <p>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d h:i A');}}</p>
            </div>

            <div class="mb-4">
                <strong>Category:</strong>
                <p>{{ $transaction->category->name ?? 'N/A' }}</p>
            </div>

            <div class="mb-4">
                <strong>Reason/Description:</strong>
                <p>{{ $transaction->desc ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <strong>Amount:</strong>
                <p class="text-green-600 font-bold" >
                    @if ($transaction->type === 'cash_in')
                        +{{ number_format($transaction->amount, 2) }}
                    @else
                        -{{ number_format($transaction->amount, 2) }}
                    @endif
                </p>
            </div>

            <div class="flex space-x-4 mt-6">
                <a href="{{ route('transactions.edit', $transaction) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                    Edit
                </a>

                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                        Delete
                    </button>
                </form>
            </div>

            <div class="mt-6">
                <a href="{{ route('transactions.index') }}" class="text-indigo-600 hover:underline">
                    ← Back to all transactions
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Transaction</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="bg-white shadow rounded-lg p-6">
            @csrf
            @method('PUT')

            {{-- Transaction Type --}}
            <div class="mb-6">
                <label class="block font-medium text-gray-700 mb-2">Transaction Type</label>
                <input type="hidden" id="type" name="type" value="{{ old('type', $transaction->type) }}">

                <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0 justify-center">
                    <button type="button" id="cash_in_btn"
                        class="sm:w-1/3 px-4 py-2 rounded-md font-semibold text-white 
                               {{ old('type', $transaction->type) === 'cash_in' ? 'bg-green-600' : 'bg-green-400' }} 
                               hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-600"
                        data-type="cash_in">
                        Cash In
                    </button>

                    <button type="button" id="cash_out_btn"
                        class="sm:w-1/3 px-4 py-2 rounded-md font-semibold text-white 
                               {{ old('type', $transaction->type) === 'cash_out' ? 'bg-red-600' : 'bg-red-400' }} 
                               hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600"
                        data-type="cash_out">
                        Cash Out
                    </button>
                </div>

                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Fields --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Amount --}}
                <div>
                    <label for="amount" class="block font-medium text-gray-700 mb-1">Amount</label>
                    <input type="number" step="0.01" min="0" id="amount" name="amount"
                        value="{{ old('amount', $transaction->amount) }}" required
                        class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black" />
                    @error('amount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block font-medium text-gray-700 mb-1">Category</label>
                    <select id="category_id" name="category_id" required
                        class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black"
                        data-old="{{ old('category_id') }}">
                        <option value="">-- Select Category --</option>
                    </select>
                    @error('category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="sm:col-span-2">
                    <label for="desc" class="block font-medium text-gray-700 mb-1">Reason / Description</label>
                    <textarea id="desc" name="desc" rows="2"
                        class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black">{{ old('desc', $transaction->desc) }}</textarea>
                    @error('desc')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date & Time --}}
                <div class="sm:col-span-2">
                    <label for="transaction_date" class="block font-medium text-gray-700 mb-1">Date & Time</label>
                    <input type="datetime-local" id="transaction_date" name="transaction_date"
                        value="{{ old('transaction_date', \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d\TH:i')) }}"
                        required
                        class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black" />
                    @error('transaction_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row justify-end mt-6 space-y-2 sm:space-y-0 sm:space-x-2">
                <a href="{{ route('transactions.index') }}"
                    class="px-4 py-2 bg-gray-300 text-center rounded-md hover:bg-gray-400 text-gray-800">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-black rounded-md text-white hover:bg-gray-800">
                    Update
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    window.categoriesByType = @json($categories);
    window.selectedCategoryId = "{{ old('category_id', $transaction->category_id) }}";
</script>

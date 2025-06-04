<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">Add Transaction</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('transactions.store') }}" method="POST"
              class="bg-white shadow-md rounded-lg p-6">
            @csrf

            {{-- Type --}}
            <div class="mb-4">
                <label for="type" class="block font-medium text-gray-900 mb-1">Transaction Type</label>
                <select id="type" name="type" required
                        class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black">
                    <option value="">-- Select Type --</option>
                    <option value="cash_in" @selected(old('type') === 'cash_in')>Cash In</option>
                    <option value="cash_out" @selected(old('type') === 'cash_out')>Cash Out</option>
                </select>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Amount --}}
                <div class="mb-4">
                    <label for="amount" class="block font-medium text-gray-900 mb-1">Amount</label>
                    <input type="number" step="0.01" min="0" id="amount" name="amount" value="{{ old('amount') }}" required
                    class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black" />
                    @error('amount')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Category --}}
                <div class="mb-4">
                    <label for="category_id" class="block font-medium text-gray-900 mb-1">Category</label>
                    <select id="category_id" name="category_id" required
                    class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Reason/Description --}}
            <div class="mb-4">
                <label for="desc" class="block font-medium text-gray-900 mb-1">Description</label>
                <textarea id="desc" name="desc" rows="1"
                class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black">{{ old('desc') }}</textarea>
                @error('desc')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date & Time --}}
            <div class="mb-6">
                <label for="transaction_date" class="block font-medium text-gray-900 mb-1">Date & Time</label>
                <input type="datetime-local" id="transaction_date" name="transaction_date"
                value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}" required
                       class="block w-full rounded-md border border-gray-300 bg-white text-gray-900 focus:border-black focus:ring-black" />
                @error('transaction_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('transactions.index') }}"
                class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400 text-gray-900">Cancel</a>
                <button type="submit"
                class="px-4 py-2 bg-black rounded-md text-white hover:bg-gray-800">Add</button>
            </div>
        </form>
    </div>
</x-app-layout>

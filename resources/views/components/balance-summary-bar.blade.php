@php
    $balanceClass = $balance < 0 ? 'text-red-600' : ($balance > 0 ? 'text-green-600' : 'text-gray-900');
    $balanceBg = $balance < 0 ? 'bg-rose-50' : ($balance > 0 ? 'bg-teal-50' : 'bg-gray-50');

@endphp

<div class="border shadow mt-4 border-gray-300 px-6 py-4 flex max-w-3xl justify-evenly text-gray-700 text-md font-bold rounded-md mx-auto {{ $balanceBg }}">
    <div class="mb-2 sm:mb-0">
        <span class="text-green-600 font-bold">Total Cash In:</span> ₹{{ number_format($totalCashIn, 2) }}
    </div>
    <div class="mb-2 sm:mb-0">
        <span class="text-red-600 font-bold">Total Cash Out:</span> ₹{{ number_format($totalCashOut, 2) }}
    </div>
    <div>
        {{-- <span class="text-gray-900 font-semibold">Balance:</span> ₹{{ number_format($balance, 2) }} --}}
        <span class="text-gray-900 font-bold">Balance:</span>
        <span class="font-bold {{ $balanceClass }}">₹{{ number_format($balance, 2) }}</span>
    </div>
</div>
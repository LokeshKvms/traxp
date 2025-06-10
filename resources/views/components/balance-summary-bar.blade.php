@php
    $balanceClass = $balance < 0 ? 'text-red-600' : ($balance > 0 ? 'text-green-600' : 'text-gray-900');
    $balanceBg = $balance < 0 ? 'bg-rose-50' : ($balance > 0 ? 'bg-teal-50' : 'bg-gray-50');
@endphp

<div class="border shadow mt-4 border-gray-300 px-6 py-4 max-w-3xl mx-auto rounded-md {{ $balanceBg }}
    flex flex-col md:flex-row md:justify-evenly text-gray-700 text-md font-bold
    space-y-4 md:space-y-0 md:space-x-6">
    <div class="text-center md:text-left">
        <span class="text-green-600 font-bold">Total Cash In:</span> ₹{{ App\Helpers\NumberFormatter::formatIndianNumber($totalCashIn, 2) }}
    </div>
    <div class="text-center md:text-left">
        <span class="text-red-600 font-bold">Total Cash Out:</span> ₹{{ App\Helpers\NumberFormatter::formatIndianNumber($totalCashOut, 2) }}
    </div>
    <div class="text-center md:text-left">
        <span class="text-gray-900 font-bold">Net Amount:</span>
        <span class="font-bold {{ $balanceClass }}">₹{{ App\Helpers\NumberFormatter::formatIndianNumber($balance, 2) }}</span>
    </div>
</div>

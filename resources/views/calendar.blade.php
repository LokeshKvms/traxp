@php
    $startOfMonth = $currentMonth->copy()->startOfMonth();
    $endOfMonth = $currentMonth->copy()->endOfMonth();

    $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    $prevMonthDate = $currentMonth->copy()->subMonth();
    $nextMonthDate = $currentMonth->copy()->addMonth();

    $currentYear = $currentMonth->year;
    $currentMonthNumber = $currentMonth->month;
    $years = range($currentYear - 5, $currentYear + 5);
    $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-4 px-4">
            <div class="w-full md:w-auto">
                <a href="{{ route('calendar', ['year' => $prevMonthDate->year, 'month' => $prevMonthDate->format('m')]) }}"
                    class="block text-center bg-black text-white font-semibold px-4 py-2 rounded hover:bg-gray-800 w-full md:w-auto">
                    &laquo; Prev
                </a>
            </div>

            <div id="calendar-header" class="text-center flex-grow cursor-pointer select-none">
                <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ $currentMonth->format('F Y') }} Transactions Calendar
                </h2>

                <!-- Modal -->
                <div id="calendar-modal"
                    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                        <form id="calendar-form" action="{{ route('calendar') }}" method="GET" class="space-y-6">
                            <h1 class="text-xl font-bold">Pick the month and year of your choice</h1>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="month-select" class="block font-semibold mb-1">Month:</label>
                                    <select name="month" id="month-select"
                                        class="w-full bg-black text-white rounded px-3 py-2 cursor-pointer font-semibold">
                                        @foreach ($months as $num => $name)
                                            <option value="{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}"
                                                @if ($num == $currentMonthNumber) selected @endif>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="year-select" class="block font-semibold mb-1">Year:</label>
                                    <select name="year" id="year-select"
                                        class="w-full bg-black text-white rounded px-3 py-2 cursor-pointer font-semibold">
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}"
                                                @if ($year == $currentYear) selected @endif>{{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <button type="submit"
                                    class="bg-black text-white font-semibold px-6 py-2 rounded hover:bg-gray-800 w-full sm:w-auto">
                                    Go
                                </button>
                                <button type="button" id="cancel-button"
                                    class="bg-red-600 text-white font-semibold px-6 py-2 rounded hover:bg-red-700 w-full sm:w-auto">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-auto">
                <a href="{{ route('calendar', ['year' => $nextMonthDate->year, 'month' => $nextMonthDate->format('m')]) }}"
                    class="block text-center bg-black text-white font-semibold px-4 py-2 rounded hover:bg-gray-800 w-full md:w-auto">
                    Next &raquo;
                </a>
            </div>
        </div>
    </x-slot>


    <div class="py-3 px-2 sm:px-4">
        <div class="text-center mb-4">
            <x-balance-summary-bar :$totalCashIn :$totalCashOut :$balance />
        </div>

        <div class="max-w-5xl mx-auto bg-white py-4 px-2 sm:px-6 rounded-lg shadow space-y-6">
            <div class="grid grid-cols-7 gap-1 sm:gap-2 text-[10px] sm:text-xs md:text-sm">
                {{-- Weekday headers --}}
                @foreach ($weekDays as $day)
                    <div
                        class="font-bold bg-gray-200 border border-gray-300 p-1 sm:p-2 text-center min-h-[30px] sm:min-h-[40px] leading-tight text-[10px] sm:text-xs">
                        {{ $day }}
                    </div>
                @endforeach

                @php
                    $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
                @endphp

                {{-- Empty cells before the first day of the month --}}
                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="bg-gray-50 border border-gray-300 min-h-[60px] sm:min-h-[80px] sm:text-wrap"></div>
                @endfor

                {{-- Days of the month --}}
                @for ($day = 1; $day <= $endOfMonth->day; $day++)
                    @php
                        $date = $currentMonth->copy()->day($day);
                        $key = $date->format('Y-m-d');
                        $startDate = $date->format('Y-m-d');
                        $endDate = $date->copy()->addDay()->format('Y-m-d');
                        $url =
                            route('transactions.index') . "?filter=custom&start_date={$startDate}&end_date={$endDate}";
                        $isToday = $date->isToday();
                    @endphp

                    <a href="{{ $url }}"
                        class="flex flex-col items-center justify-between overflow-clip border border-gray-300 rounded p-1 sm:p-2 min-h-[60px] sm:min-h-[80px]
                              {{ $isToday ? 'bg-indigo-100' : 'bg-gray-50' }} text-gray-900 no-underline hover:bg-indigo-50 text-[10px] sm:text-xs text-center">
                        <div class="font-bold mb-1 truncate">{{ $day }}</div>

                        @if (isset($data[$key]))
                            <div class="text-green-600 font-semibold text-[10px] sm:text-xs truncate">
                                ₹{{ number_format($data[$key]['cash_in']) }}
                            </div>
                            <div class="text-red-600 font-semibold text-[10px] sm:text-xs truncate">
                                ₹{{ number_format($data[$key]['cash_out']) }}
                            </div>
                        @endif
                    </a>
                @endfor

                @php
                    $lastDayOfWeek = $endOfMonth->dayOfWeek;
                    $emptyAfter = 6 - $lastDayOfWeek;
                @endphp

                {{-- Fill empty cells after the last day of the month --}}
                @for ($i = 0; $i < $emptyAfter; $i++)
                    <div class="bg-gray-50 border border-gray-300 min-h-[60px] sm:min-h-[80px]"></div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Modal toggle script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('calendar-header').addEventListener('click', function() {
                document.getElementById('calendar-modal').classList.remove('hidden');
            });

            document.getElementById('cancel-button').addEventListener('click', function(e) {
                document.getElementById('calendar-modal').classList.add('hidden');
                location.reload();
            });
        });
    </script>
</x-app-layout>

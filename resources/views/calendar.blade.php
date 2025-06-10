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
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="navigations flex flex-col sm:flex-row items-center justify-between gap-2 sm:gap-4 mb-4 px-4">
            <a href="{{ route('calendar', ['year' => $prevMonthDate->year, 'month' => $prevMonthDate->format('m')]) }}" class="nav-button">&laquo; Prev</a>
            <div id="calendar-header" style="cursor:pointer; user-select:none; flex-grow:1; text-align:center;">
                <h2 id="calendar-title" class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ $currentMonth->format('F Y') }} Transactions Calendar
                </h2>

                <!-- Modal background -->
                <div id="calendar-modal" class="modal hiddens">
                    <div class="modal-content">
                        <form id="calendar-form" action="{{ route('calendar') }}" method="GET">
                            <div class="flex flex-col space-y-6">
                                <h1>Pick the month and year of your choice</h1>
                                <div class="grid grid-cols-2">
                                    <div>
                                        <label for="month-select">Month:</label>
                                        <select name="month" id="month-select" class="cal-button">
                                            @foreach ($months as $num => $name)
                                            <option value="{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}" @if ($num == $currentMonthNumber) selected @endif>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="year-select">Year:</label>
                                        <select name="year" id="year-select" class="nav-button w-30">
                                            @foreach ($years as $year)
                                            <option value="{{ $year }}" @if ($year == $currentYear) selected @endif>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="space-x-4">
                                    <button type="submit" class="nav-button">Go</button>
                                    <button type="button" id="cancel-button" class="nav-button">Cancel</button>
                                </div>
                                    
                            </div>
                        </form>
                    </div>
            </div>

            </div>
            <a href="{{ route('calendar', ['year' => $nextMonthDate->year, 'month' => $nextMonthDate->format('m')]) }}" class="nav-button">Next &raquo;</a>
        </div>
    </x-slot>

    <div class="py-3 px-4 sm:px-6 min-h-screen">
        
        <div class="text-center mb-4">
            <x-balance-summary-bar :$totalCashIn :$totalCashOut :$balance/>
        </div>

        <div class="max-w-5xl mx-auto bg-white py-6 px-4 sm:px-6 rounded-lg shadow space-y-6">

            <div class="calendar grid grid-cols-7 gap-1 sm:gap-2 text-xs sm:text-sm">
                {{-- Weekday headers --}}
                @foreach ($weekDays as $day)
                    <div class="weekday">{{ $day }}</div>
                @endforeach

                @php
                    $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
                @endphp

                {{-- Empty cells before the first day of the month --}}
                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="day empty-day"></div>
                @endfor

                {{-- Days of the month --}}
                @for ($day = 1; $day <= $endOfMonth->day; $day++)
                    @php
                        $date = $currentMonth->copy()->day($day);
                        $key = $date->format('Y-m-d');
                        $startDate = $date->format('Y-m-d');
                        $endDate = $date->copy()->addDay()->format('Y-m-d');
                        $url = route('transactions.index') . "?filter=custom&start_date={$startDate}&end_date={$endDate}";
                        $isToday = $date->isToday();
                    @endphp

                    <a href="{{ $url }}" class="day border p-2 sm:p-3 flex flex-col items-center justify-between rounded {{ $isToday ? 'bg-indigo-100' : 'bg-gray-50' }}">
                        <div class="date-number">{{ $day }}</div>

                        @if (isset($data[$key]))
                            <div class="text-green-600 font-medium text-center">+ ₹{{ number_format($data[$key]['cash_in'], 2) }}</div>
                            <div class="text-red-600 font-medium text-center">- ₹{{ number_format($data[$key]['cash_out'], 2) }}</div>
                        @endif
                    </a>
                @endfor

                {{-- Fill empty cells after the last day of the month to complete the last week --}}
                @php
                    $lastDayOfWeek = $endOfMonth->dayOfWeek;
                    $emptyAfter = 6 - $lastDayOfWeek;
                @endphp

                @for ($i = 0; $i < $emptyAfter; $i++)
                    <div class="day empty-day"></div>
                @endfor
            </div>

        </div>

        
    </div>

</x-app-layout>

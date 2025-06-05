@php
    use Carbon\Carbon;

    // Use currentMonth from controller
    $startOfMonth = $currentMonth->copy()->startOfMonth();
    $endOfMonth = $currentMonth->copy()->endOfMonth();

    // Weekday labels
    $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Calculate previous and next month for navigation
    $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
@endphp

<style>
    .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
    .weekday { border: 1px solid #ccc; padding: 10px; min-height: 50px; position: relative; }
    .day, .day a {
        border: 1px solid #ccc;
        padding: 10px;
        min-height: 100px;
        position: relative;
        display: block;
        text-decoration: none;
        color: inherit;
        cursor: pointer;
    }
    .weekday { font-weight: bold; background-color: #f0f0f0; text-align: center; }
    .date-number { font-weight: bold; margin-bottom: 5px; }
    .cash-in { color: green; font-weight: 900; text-align: center; }
    .cash-out { color: red;  font-weight: 900; text-align: center; }
    .empty-day { background-color: #fafafa; }
    .navigation { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .nav-button {
        background-color: #1d4ed8;
        color: white;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <div class="navigation">
            <a href="{{ route('calendar', ['month' => $prevMonth]) }}" class="nav-button">&laquo; Prev</a>
            <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
                {{ $currentMonth->format('F Y').' Transactions Calendar' }}
            </h2>
            <a href="{{ route('calendar', ['month' => $nextMonth]) }}" class="nav-button">Next &raquo;</a>
        </div>
    </x-slot>

    <div class="py-12 px-6 pl-10 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 py-8 bg-white space-y-16">

            <div class="calendar">
                {{-- Weekday headers --}}
                @foreach ($weekDays as $day)
                    <div class="weekday">{{ $day }}</div>
                @endforeach

                {{-- Calculate the day of week for the 1st of the month --}}
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
                    @endphp

                    <a href="{{ $url }}" class="day">
                        <div class="date-number">{{ $day }}</div>

                        @if (isset($data[$key]))
                            <div class="cash-in">+ ₹{{ number_format($data[$key]['cash_in'], 2) }}</div>
                            <div class="cash-out">- ₹{{ number_format($data[$key]['cash_out'], 2) }}</div>
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

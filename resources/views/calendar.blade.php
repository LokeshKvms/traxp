@php
    use Carbon\Carbon;

    // Use currentMonth from controller
    $startOfMonth = $currentMonth->copy()->startOfMonth();
    $endOfMonth = $currentMonth->copy()->endOfMonth();

    // Weekday labels
    $weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

    // Calculate previous and next month for navigation
    $prevMonthDate = $currentMonth->copy()->subMonth();
    $nextMonthDate = $currentMonth->copy()->addMonth();

    // For dropdown
    $currentYear = $currentMonth->year;
    $currentMonthNumber = $currentMonth->month;
    $years = range($currentYear - 5, $currentYear + 5);
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
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
    .navigation { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
    .nav-button {
        background-color: #1d4ed8;
        color: white;
        padding: 6px 12px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
    }

    /* Dropdown styles */
    #calendar-form select {
        font-weight: 600;
        border-radius: 5px;
        border: none;
        background-color: #1d4ed8;
        color: white;
        padding: 6px 10px;
        cursor: pointer;
    }

    #calendar-form button {
        font-weight: 600;
        border-radius: 5px;
        border: none;
        color: white;
        cursor: pointer;
    }

    #calendar-form button[type="submit"] {
        background-color: #2563eb;
    }

    #cancel-button {
        background-color: #dc2626;
    }

    .modal {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0,0,0,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    /* Utility class to hide modal */
    .hidden {
        display: none !important;
    }

</style>

<x-app-layout>
    <x-slot name="header">
        <div class="navigation">
            <a href="{{ route('calendar', ['year' => $prevMonthDate->year, 'month' => $prevMonthDate->format('m')]) }}" class="nav-button">&laquo; Prev</a>
            <div id="calendar-header" style="cursor:pointer; user-select:none; flex-grow:1; text-align:center;">
                <h2 id="calendar-title" class="font-semibold text-2xl text-gray-900 leading-tight">
                    {{ $currentMonth->format('F Y') }} Transactions Calendar
                </h2>

                <!-- Modal background -->
                <div id="calendar-modal" class="modal hidden">
                    <div class="modal-content">
                        <form id="calendar-form" action="{{ route('calendar') }}" method="GET" style="margin-top: 0;">
                            <select name="month" id="month-select" class="nav-button" style="margin-right:5px;">
                                @foreach ($months as $num => $name)
                                    <option value="{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}" @if ($num == $currentMonthNumber) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                            <select name="year" id="year-select" class="nav-button">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" @if ($year == $currentYear) selected @endif>{{ $year }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="nav-button" style="margin-left:10px;">Go</button>
                            <button type="button" id="cancel-button" class="nav-button" style="background-color: #dc2626; margin-left: 10px;">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
            <a href="{{ route('calendar', ['year' => $nextMonthDate->year, 'month' => $nextMonthDate->format('m')]) }}" class="nav-button">Next &raquo;</a>
        </div>
    </x-slot>

    <div class="py-12 px-6 pl-10 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 py-8 bg-white space-y-16">

            <div class="calendar">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const title = document.getElementById('calendar-title');
            const modal = document.getElementById('calendar-modal');
            const cancelBtn = document.getElementById('cancel-button');

            // Open modal on title click
            title.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            // Close modal on cancel button
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // Optional: close modal when clicking outside form content
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

    </script>
</x-app-layout>

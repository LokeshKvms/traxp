@vite(['resources/js/pages/insights-page.js'])

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Expense Insights
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">
                        Total Cash In
                    </h3>
                    <p class="text-3xl font-bold text-green-500 truncate">
                        ₹{{ App\Helpers\NumberFormatter::formatIndianNumber($totalCashIn, 2) }}
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">
                        Total Cash Out
                    </h3>
                    <p class="text-3xl font-bold text-red-500 truncate">
                        ₹{{ App\Helpers\NumberFormatter::formatIndianNumber($totalCashOut, 2) }}
                    </p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">
                        Balance
                    </h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 truncate">
                        ₹{{ App\Helpers\NumberFormatter::formatIndianNumber($balance, 2) }}
                    </p>
                </div>
            </div>

            {{-- Charts Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Cash In by Category --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">
                        Cash In by Category
                    </h3>
                    <canvas id="cashInChart" class="w-full h-48 sm:h-64"></canvas>
                </div>

                {{-- Cash Out by Category --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">
                        Cash Out by Category
                    </h3>
                    <canvas id="cashOutChart" class="w-full h-48 sm:h-64"></canvas>
                </div>

                {{-- Top 5 Spending Categories --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 flex flex-col">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300 text-left">
                        Top 5 Spending Categories
                    </h3>
                    <div class="flex-grow flex items-center">
                        <canvas id="topSpendingChart" class="w-full h-48 sm:h-64"></canvas>
                    </div>
                </div>

                {{-- Monthly Income vs Expense Trend --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300
          col-span-1 sm:col-span-2 lg:col-span-3">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">
                        Monthly Income vs Expense (Last 12 Months)
                    </h3>
                    <canvas id="monthlyTrendChart" class="w-full h-56 sm:h-72"></canvas>
                </div>

                {{-- Daily Cash Flow (Last 30 Days) --}}
                <div
                    class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300
          col-span-1 sm:col-span-2 lg:col-span-3">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">
                        Daily Cash Flow (Last 30 Days)
                    </h3>
                    <canvas id="dailyFlowChart" class="w-full h-48 sm:h-64"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.expenseData = {
            cashInData: @json($cashInData),
            cashOutData: @json($cashOutData),
            monthlyTrend: @json($monthlyTrend),
            months: @json($months),
            topSpendingCategories: @json($topSpendingCategories),
            dailyFlow: @json($dailyFlow),
            dailyDates: @json($dailyDates),
        };
    </script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-app-layout>

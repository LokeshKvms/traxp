<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Expense Insights</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Total Cash In</h3>
                    <p class="text-3xl font-bold text-green-500">₹{{ number_format($totalCashIn, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Total Cash Out</h3>
                    <p class="text-3xl font-bold text-red-500">₹{{ number_format($totalCashOut, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-center hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">Balance</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">₹{{ number_format($balance, 2) }}</p>
                </div>
            </div>

            {{-- Charts Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Cash In by Category --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Cash In by Category</h3>
                    <canvas id="cashInChart" class="w-full h-64"></canvas>
                </div>

                {{-- Cash Out by Category --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Cash Out by Category</h3>
                    <canvas id="cashOutChart" class="w-full h-64"></canvas>
                </div>

                {{-- Monthly Income vs Expense Trend --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 col-span-1 sm:col-span-2 lg:col-span-3">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Monthly Income vs Expense (Last 12 Months)</h3>
                    <canvas id="monthlyTrendChart" class="w-full h-72"></canvas>
                </div>

                {{-- Top 5 Spending Categories --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Top 5 Spending Categories</h3>
                    <canvas id="topSpendingChart" class="w-full h-64"></canvas>
                </div>

                {{-- Daily Cash Flow (Last 30 Days) --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-xl transition-shadow duration-300 sm:col-span-2">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Daily Cash Flow (Last 30 Days)</h3>
                    <canvas id="dailyFlowChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cashInData = @json($cashInData);
            const cashOutData = @json($cashOutData);
            const monthlyTrend = @json($monthlyTrend);
            const months = @json($months);
            const topSpendingCategories = @json($topSpendingCategories);
            const dailyFlow = @json($dailyFlow);
            const dailyDates = @json($dailyDates);

            const pieOptions = {
                animation: {
                    animateRotate: true,
                    duration: 1500,
                    easing: 'easeOutCirc'
                },
                plugins: {
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: context => {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return `${label}: ₹${value.toLocaleString()}`;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#4B5563',
                            font: { size: 14 }
                        }
                    }
                }
            };


            const barOptions = {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: '#4B5563', font: { size: 14 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `₹${ctx.parsed.y.toLocaleString()}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#4B5563' }
                    },
                    x: {
                        ticks: { color: '#4B5563' }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            };

            const lineOptions = {
                ...barOptions,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                stacked: false
            };

            // Pie: Cash In by Category
            new Chart(document.getElementById('cashInChart'), {
                type: 'pie',
                data: {
                    labels: cashInData.map(item => item.category || 'Uncategorized'),
                    datasets: [{
                        data: cashInData.map(item => item.total),
                        backgroundColor: ['#34d399', '#10b981', '#059669', '#047857', '#065f46', '#22c55e', '#16a34a']
                    }]
                },
                options: pieOptions
            });

            // Pie: Cash Out by Category
            new Chart(document.getElementById('cashOutChart'), {
                type: 'pie',
                data: {
                    labels: cashOutData.map(item => item.category || 'Uncategorized'),
                    datasets: [{
                        data: cashOutData.map(item => item.total),
                        backgroundColor: ['#f87171', '#ef4444', '#dc2626', '#b91c1c', '#991b1b', '#ef4444', '#b91c1c']
                    }]
                },
                options: pieOptions
            });

            // Line: Monthly Trend
            new Chart(document.getElementById('monthlyTrendChart'), {
                type: 'line',
                data: {
                    labels: months.map(m => {
                        const date = new Date(m + '-01');
                        return date.toLocaleString('default', { month: 'short', year: 'numeric' });
                    }),
                    datasets: [
                        {
                            label: 'Cash In',
                            data: months.map(m => {
                                const found = monthlyTrend.find(t => t.month === m);
                                return (found && found.cash_in != null) ? found.cash_in : 0;
                            }),
                            borderColor: '#34d399',
                            backgroundColor: 'rgba(52, 211, 153, 0.3)',
                            fill: true,
                            tension: 0.3,
                        },
                        {
                            label: 'Cash Out',
                            data: months.map(m => {
                                const found = monthlyTrend.find(t => t.month === m);
                                return (found && found.cash_out != null) ? found.cash_out : 0;
                            }),
                            borderColor: '#f87171',
                            backgroundColor: 'rgba(248, 113, 113, 0.3)',
                            fill: true,
                            tension: 0.3,
                        }
                    ]
                },
                options: lineOptions
            });

            // Bar: Daily Flow
            new Chart(document.getElementById('dailyFlowChart'), {
                type: 'bar',
                data: {
                    labels: dailyDates.map(d => {
                        const date = new Date(d);
                        return `${date.getDate()}/${date.getMonth() + 1}`;
                    }),
                    datasets: [
                        {
                            label: 'Cash In',
                            data: dailyDates.map(d => {
                                const found = dailyFlow.find(f => f.date === d);
                                return (found && found.cash_in != null) ? found.cash_in : 0;
                            }),
                            backgroundColor: '#34d399'
                        },
                        {
                            label: 'Cash Out',
                            data: dailyDates.map(d => {
                                const found = dailyFlow.find(f => f.date === d);
                                return (found && found.cash_out != null) ? found.cash_out : 0;
                            }),
                            backgroundColor: '#f87171'
                        }
                    ]
                },
                options: barOptions
            });

            // Bar: Top 5 Spending Categories
            new Chart(document.getElementById('topSpendingChart'), {
                type: 'bar',
                data: {
                    labels: topSpendingCategories.map(item => item.category || 'Uncategorized'),
                    datasets: [{
                        label: 'Amount',
                        data: topSpendingCategories.map(item => item.total),
                        backgroundColor: '#ef4444',
                    }]
                },
                options: barOptions
            });

        });
    </script>

</x-app-layout>

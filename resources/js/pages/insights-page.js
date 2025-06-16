document.addEventListener('DOMContentLoaded', () => {
    const {
        cashInData,
        cashOutData,
        monthlyTrend,
        months,
        topSpendingCategories,
        dailyFlow,
        dailyDates
    } = window.expenseData;

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
            legend: { position: 'top', labels: { color: '#4B5563', font: { size: 14 } } },
            tooltip: { callbacks: { label: ctx => `₹${ctx.parsed.y.toLocaleString()}` } }
        },
        scales: {
            y: {
                type: 'logarithmic',
                max: 20000,
                beginAtZero: false,
                ticks: { color: '#4B5563', callback: value => Number(value.toString()) }
            },
            x: { ticks: { color: '#4B5563' } }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' }
    };

    const barOptionsCat = {
        responsive: true,
        plugins: {
            legend: { position: 'top', labels: { color: '#4B5563', font: { size: 14 } } },
            tooltip: { callbacks: { label: ctx => `₹${ctx.parsed.y.toLocaleString()}` } }
        },
        scales: {
            y: { ticks: { color: '#4B5563' } },
            x: { ticks: { color: '#4B5563' } }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' }
    };

    const lineOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'top', labels: { color: '#4B5563', font: { size: 14 } } },
            tooltip: { callbacks: { label: ctx => `₹${ctx.parsed.y.toLocaleString()}` } }
        },
        scales: {
            y: { max: 30000, beginAtZero: false, ticks: { color: '#4B5563' } },
            x: { ticks: { color: '#4B5563' } }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' },
        interaction: { mode: 'index', intersect: false },
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
            datasets: [{
                    label: 'Cash In',
                    data: months.map(m => {
                        const found = monthlyTrend.find(t => t.month === m);
                        return found?.cash_in ?? 0;
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
                        return found?.cash_out ?? 0;
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
            datasets: [{
                    label: 'Cash In',
                    data: dailyDates.map(d => {
                        const found = dailyFlow.find(f => f.date === d);
                        return found?.cash_in ?? 0;
                    }),
                    backgroundColor: '#34d399'
                },
                {
                    label: 'Cash Out',
                    data: dailyDates.map(d => {
                        const found = dailyFlow.find(f => f.date === d);
                        return found?.cash_out ?? 0;
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
        options: barOptionsCat
    });
});

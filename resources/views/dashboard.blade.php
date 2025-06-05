<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6 pl-10 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-16">

            <x-card-row heading="Daily">
                    <x-card link="/transactions?filter=daily" :front="'Daily Income'" :back="$totalCashInDaily" type="cash-in" />
                    <x-card link="/transactions?filter=daily" :front="'Daily Expenses'" :back="$totalCashOutDaily" type="cash-out" />
                    <x-card link="/transactions?filter=daily" :front="'Daily Balance'" :back="$totalCashInDaily - $totalCashOutDaily"/>
            </x-card-row>

            <x-card-row heading="Weekly">
                    <x-card link="/transactions?filter=weekly" :front="'Weekly Income'" :back="$totalCashInWeekly" type="cash-in" />
                    <x-card link="/transactions?filter=weekly" :front="'Weekly Expenses'" :back="$totalCashOutWeekly" type="cash-out" />
                    <x-card link="/transactions?filter=weekly" :front="'Weekly Balance'" :back="$totalCashInWeekly - $totalCashOutWeekly"/>
            </x-card-row>

            <x-card-row heading="Monthly">
                    <x-card link="/transactions?filter=monthly" :front="'Monthly Income'" :back="$totalCashInMonthly" type="cash-in" />
                    <x-card link="/transactions?filter=monthly" :front="'Monthly Expenses'" :back="$totalCashOutMonthly" type="cash-out" />
                    <x-card link="/transactions?filter=monthly" :front="'Monthly Balance'" :back="$totalCashInMonthly - $totalCashOutMonthly"/>
            </x-card-row>

             <x-card-row heading="Yearly">
                    <x-card link="/transactions?filter=yearly" :front="'Yearly Income'" :back="$totalCashInYearly" type="cash-in" />
                    <x-card link="/transactions?filter=yearly" :front="'Yearly Expenses'" :back="$totalCashOutYearly" type="cash-out" />
                    <x-card link="/transactions?filter=yearly" :front="'Yearly Balance'" :back="$totalCashInYearly - $totalCashOutYearly"/>
            </x-card-row>

            <x-card-row heading="Total">
                    <x-card link="/transactions" :front="'Total Income'" :back="$totalCashIn" type="cash-in"/>
                    <x-card link="/transactions" :front="'Total Expenses'" :back="$totalCashOut" type="cash-out" />
                    <x-card link="/transactions" :front="'Total Balance'" :back="$totalCashIn - $totalCashOut"/>
            </x-card-row>

        </div>
    </div>
</x-app-layout>

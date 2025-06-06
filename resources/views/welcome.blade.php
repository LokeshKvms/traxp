<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Traxp - Smart Expense Tracker</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }

            .fade-in {
                animation: fadeIn 1s ease-in-out;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800">

        <!-- Top Navigation -->
        <nav class="w-full fixed top-0 left-0 bg-gray-50 border-b z-10">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('https://placehold.co/42') }}" alt="Traxp Logo" class="h-8 w-auto mr-1">
                    <span class="text-xl font-bold text-gray-700">Traxp</span>
                </a>

                <!-- Auth Links -->
                @if (Route::has('login'))
                    <div class="space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-md text-gray-700 hover:text-gray-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-md text-gray-700 hover:text-gray-600">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-md text-gray-700 hover:text-gray-600 ml-2">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>


        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center text-center bg-white px-6 fade-in">
            <div>
                <h1 class="text-5xl md:text-6xl font-bold text-gray-700 mb-6">Track Your Expenses Smartly</h1>
                <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-xl mx-auto">
                    Traxp helps you manage your money better with simplicity—no more spreadsheets.
                </p>
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-gray-600 text-white text-lg font-semibold rounded-md shadow hover:bg-gray-700 transition">
                    Get Started Free
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-20 bg-gray-100">
            <div class="max-w-6xl mx-auto px-6 text-center">
                <h2 class="text-4xl font-bold text-gray-800 mb-12">What Makes Traxp Awesome?</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                    <!-- Fast Expense Logging -->
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <div class="text-gray-500 text-3xl mb-4">🧾</div>
                        <h3 class="text-xl font-semibold mb-2">One-Tap Expense Entry</h3>
                        <p class="text-gray-600">Add your transactions in seconds — no clutter, no confusion.</p>
                    </div>

                    <!-- Full Expense Control -->
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <div class="text-gray-500 text-3xl mb-4">🛠️</div>
                        <h3 class="text-xl font-semibold mb-2">Total Transaction Control</h3>
                        <p class="text-gray-600">Easily create, edit, and delete income or expenses whenever you need.</p>
                    </div>

                    <!-- Smart Calendar -->
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <div class="text-gray-500 text-3xl mb-4">📅</div>
                        <h3 class="text-xl font-semibold mb-2">Cash Flow Calendar</h3>
                        <p class="text-gray-600">Visualize your cash in and out for any day, right on an interactive calendar.</p>
                    </div>

                    <!-- Data Privacy -->
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <div class="text-gray-500 text-3xl mb-4">🔒</div>
                        <h3 class="text-xl font-semibold mb-2">Privacy-First by Design</h3>
                        <p class="text-gray-600">Your data is encrypted, secured, and never shared. Period.</p>
                    </div>

                    <!-- Smart Insights -->
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <div class="text-gray-500 text-3xl mb-4">📈</div>
                        <h3 class="text-xl font-semibold mb-2">Real-Time Spending Insights</h3>
                        <p class="text-gray-600">Track totals by day, week, month, or year with beautifully simple summary cards.</p>
                    </div>

                    <!-- Transaction Table -->
                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                        <div class="text-gray-500 text-3xl mb-4">📋</div>
                        <h3 class="text-xl font-semibold mb-2">Detailed History View</h3>
                        <p class="text-gray-600">Browse all your activity in a clean, sortable table with powerful filters.</p>
                    </div>
                </div>
            </div>
        </section>


        <!-- Footer -->
        <footer class="bg-white border-t mt-20 pl-10">
            <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-3 mb-4 md:mb-0">
                <img src="{{ asset('https://placehold.co/42') }}" alt="Traxp Logo" class="h-8 w-auto">
                <span class="text-xl font-extrabold text-gray-800 tracking-wide">Traxp</span>
                </a>

                <!-- Copyright -->
                <p class="text-center md:text-left text-gray-500">&copy; 2025 Traxp. All rights reserved.</p>

                <!-- Important Notice Link -->
                <div class="mt-4 md:mt-0">
                <a href="#" class="text-red-600 font-semibold hover:underline transition duration-200">
                    Terms & Conditions
                </a>
                </div>

            </div>
        </footer>

    </body>
</html>

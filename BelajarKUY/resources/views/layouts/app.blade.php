<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BelajarKUY</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body 
    class="bg-slate-50 text-slate-800 antialiased"
    style="font-family: 'Poppins', sans-serif;"
>

    <div class="min-h-screen">

        <!-- Navbar -->
        <nav class="flex justify-between items-center px-10 py-5 bg-white shadow-md">

            <h1 class="text-3xl font-bold text-blue-600">
                BelajarKUY
            </h1>

            <ul class="flex gap-8 font-medium">
                <li>
                    <a href="#" class="hover:text-blue-600 transition">
                        Home
                    </a>
                </li>

                <li>
                    <a href="#" class="hover:text-blue-600 transition">
                        Course
                    </a>
                </li>

                <li>
                    <a href="#" class="hover:text-blue-600 transition">
                        Mentor
                    </a>
                </li>

                <li>
                    <a href="#" class="hover:text-blue-600 transition">
                        About
                    </a>
                </li>
            </ul>

            <button class="bg-blue-600 text-white px-5 py-2 rounded-xl shadow-lg hover:scale-105 transition duration-300">
                Login
            </button>

        </nav>

        <!-- Header -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Content -->
        <main>

            <!-- Hero Section -->
            <section class="px-10 py-20 flex items-center justify-between">

                <div class="max-w-xl">

                    <h1 class="text-6xl font-bold leading-tight">
                        Tingkatkan Skill Bersama
                        <span class="text-blue-600">
                            BelajarKUY
                        </span>
                    </h1>

                    <p class="mt-6 text-slate-600 text-lg">
                        Platform pembelajaran online modern untuk mahasiswa dan pelajar Indonesia.
                    </p>

                    <button class="mt-8 bg-blue-600 text-white px-8 py-4 rounded-2xl shadow-xl hover:scale-105 transition duration-300">
                        Mulai Belajar
                    </button>

                </div>

                <img 
                    src="{{ asset('images/hero.png') }}"
                    class="w-[500px]"
                    alt="Hero Image"
                >

            </section>

            <!-- Popular Course -->
            <section class="px-10 py-10">

                <h2 class="text-4xl font-bold mb-10">
                    Popular Course
                </h2>

                <div class="grid grid-cols-3 gap-8">

                    <!-- Card 1 -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:scale-105 transition duration-300">

                        <img 
                            src="{{ asset('images/course1.jpg') }}"
                            class="h-52 w-full object-cover"
                        >

                        <div class="p-6">

                            <h3 class="text-2xl font-bold">
                                UI/UX Design
                            </h3>

                            <p class="mt-3 text-slate-600">
                                Belajar design modern dari dasar hingga mahir.
                            </p>

                            <button class="mt-5 bg-blue-600 text-white px-5 py-3 rounded-xl">
                                Lihat Course
                            </button>

                        </div>

                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:scale-105 transition duration-300">

                        <img 
                            src="{{ asset('images/course2.jpg') }}"
                            class="h-52 w-full object-cover"
                        >

                        <div class="p-6">

                            <h3 class="text-2xl font-bold">
                                Web Development
                            </h3>

                            <p class="mt-3 text-slate-600">
                                Kuasai HTML, CSS, Laravel, dan React modern.
                            </p>

                            <button class="mt-5 bg-blue-600 text-white px-5 py-3 rounded-xl">
                                Lihat Course
                            </button>

                        </div>

                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:scale-105 transition duration-300">

                        <img 
                            src="{{ asset('images/course3.jpg') }}"
                            class="h-52 w-full object-cover"
                        >

                        <div class="p-6">

                            <h3 class="text-2xl font-bold">
                                Data Science
                            </h3>

                            <p class="mt-3 text-slate-600">
                                Pelajari data analysis dan machine learning.
                            </p>

                            <button class="mt-5 bg-blue-600 text-white px-5 py-3 rounded-xl">
                                Lihat Course
                            </button>

                        </div>

                    </div>

                </div>

            </section>

            <!-- Mentor -->
            <section class="px-10 py-20">

                <h2 class="text-4xl font-bold mb-10">
                    Mentor Terbaik
                </h2>

                <div class="grid grid-cols-3 gap-8">

                    <div class="bg-white p-8 rounded-3xl shadow-xl text-center">

                        <img 
                            src="{{ asset('images/mentor1.jpg') }}"
                            class="w-32 h-32 rounded-full mx-auto object-cover"
                        >

                        <h3 class="mt-5 text-2xl font-bold">
                            John Doe
                        </h3>

                        <p class="text-slate-500">
                            UI/UX Expert
                        </p>

                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-xl text-center">

                        <img 
                            src="{{ asset('images/mentor2.jpg') }}"
                            class="w-32 h-32 rounded-full mx-auto object-cover"
                        >

                        <h3 class="mt-5 text-2xl font-bold">
                            Jane Smith
                        </h3>

                        <p class="text-slate-500">
                            Fullstack Developer
                        </p>

                    </div>

                    <div class="bg-white p-8 rounded-3xl shadow-xl text-center">

                        <img 
                            src="{{ asset('images/mentor3.jpg') }}"
                            class="w-32 h-32 rounded-full mx-auto object-cover"
                        >

                        <h3 class="mt-5 text-2xl font-bold">
                            Michael Lee
                        </h3>

                        <p class="text-slate-500">
                            Data Scientist
                        </p>

                    </div>

                </div>

            </section>

            {{ $slot }}

        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white py-10 mt-20 text-center">

            <h2 class="text-3xl font-bold">
                BelajarKUY
            </h2>

            <p class="mt-4 text-slate-400">
                Platform pembelajaran online modern untuk masa depan lebih baik.
            </p>

            <p class="mt-6 text-slate-500 text-sm">
                © 2026 BelajarKUY. All rights reserved.
            </p>

        </footer>

    </div>

</body>
</html>
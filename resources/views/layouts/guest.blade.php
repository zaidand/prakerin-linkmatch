<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/yayasanbg.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @php
        $isAuthPage = request()->routeIs('login') || request()->routeIs('register');
        @endphp

        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0
                {{ $isAuthPage ? 'bg-cover bg-center bg-no-repeat' : 'bg-gray-100' }}"
            style="{{ $isAuthPage ? "background-image: url('".asset('images/lapangan-pentek.jpeg')."');" : '' }}"
        >
            {{-- overlay agar teks/form tetap kebaca --}}
            <div class="min-h-screen w-full flex flex-col sm:justify-center items-center
                {{ $isAuthPage ? 'bg-black/40' : '' }}"
            >
                {{-- logo/brand (biarkan sesuai kode kamu yang sudah ada) --}}
                <div>
                <a href="/">
                    <img
                        src="{{ asset('images/yayasanbg.png') }}"
                        alt="Logo Yuppentek"
                        class="h-60 w-auto"
                    >
                </a>
            </div>
                {{ $logo ?? '' }}

                <div
                    class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg shadow-md
                        {{ $isAuthPage ? 'bg-white/15 backdrop-blur-md border border-white/30' : 'bg-white' }}"
                >
                    {{ $slot }}
                </div>
            </div>
        </div>

    </body>
</html>

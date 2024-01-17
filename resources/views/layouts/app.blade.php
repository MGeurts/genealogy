<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') || localStorage.setItem('darkMode', 'system')
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    x-bind:class="{ 'dark': darkMode === 'dark' || (darkMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches) }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Genealogy') }} @yield('title')</title>

    <!-- favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-96x96.png') }}" sizes="96x96">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chart.js'])

    <!-- Styles -->
    @livewireStyles
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-white dark:bg-black">
        <!-- TALL Notifications -->
        <livewire:toasts />

        <!-- Header -->
        @include('layouts.partials.header')

        <!-- Offcanvas -->
        @include('layouts.partials.offcanvas')

        <!-- Heading -->
        @if (isset($heading))
            @include('layouts.partials.heading')
        @endif

        <!-- Banner -->
        <x-banner />

        <!-- Content -->
        <main class="mx-auto px-2 md:px-5 flex flex-grow bg-gray-100 dark:bg-gray-900">
            {{ $slot }}
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')

        <!-- Back to top button -->
        @include('layouts.partials.back_to_top')
    </div>

    <!-- Scripts -->
    @livewireScripts
    @stack('scripts')
</body>

</html>

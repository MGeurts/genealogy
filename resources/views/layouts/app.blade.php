<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme(null, true)" x-bind:class="{ 'dark bg-gray-900': darkTheme, 'bg-gray-100': !darkTheme }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Genealogy') }} @yield('title')</title>

    {{-- favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-16x16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon/favicon-96x96.png') }}" sizes="96x96">

    {{-- fonts --}}
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    {{-- scripts --}}
    <tallstackui:script />
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/chart.js'])

    {{-- styles --}}
    @livewireStyles
    @filamentStyles
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">
        {{-- TallStackUI notifications --}}
        <x-ts-toast />

        {{-- offcanvas menu --}}
        @include('layouts.partials.offcanvas')

        {{-- header --}}
        @include('layouts.partials.header')

        {{-- content --}}
        <main class="mx-auto px-2 flex flex-grow">
            {{ $slot }}
        </main>

        {{-- footer --}}
        @include('layouts.partials.footer')
    </div>

    {{-- scripts --}}
    @livewireScripts
    @filamentScripts
    @stack('scripts')
</body>

</html>

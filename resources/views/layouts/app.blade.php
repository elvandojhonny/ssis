<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Dashboard') | SSIS
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')

</head>


<body class="ssis-body">

<div class="page ssis-app">

    {{-- ===================================================== --}}
    {{-- SIDEBAR --}}
    {{-- ===================================================== --}}

    @include('layouts.partials.sidebar')


    {{-- ===================================================== --}}
    {{-- MAIN WRAPPER --}}
    {{-- ===================================================== --}}

    <div class="page-wrapper ssis-wrapper">


        {{-- ================================================= --}}
        {{-- NAVBAR --}}
        {{-- ================================================= --}}

        @include('layouts.partials.navbar')


        {{-- ================================================= --}}
        {{-- MAIN CONTENT --}}
        {{-- ================================================= --}}

        <main class="page-body ssis-content">

            <div class="container-xl">

                @yield('content')

            </div>

        </main>


        {{-- ================================================= --}}
        {{-- FOOTER --}}
        {{-- ================================================= --}}

        @include('layouts.partials.footer')

    </div>

</div>


{{-- ========================================================= --}}
{{-- MOBILE SIDEBAR OVERLAY --}}
{{-- ========================================================= --}}

<div
    id="sidebar-overlay"
    class="ssis-sidebar-overlay"
></div>


@stack('scripts')

</body>

</html>
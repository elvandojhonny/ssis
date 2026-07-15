<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard') | SSIS</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
<div class="page">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    <div class="page-wrapper">

        {{-- Navbar --}}
        @include('layouts.partials.navbar')

        <main class="page-body">
            <div class="container-xl">

                @yield('content')

            </div>
        </main>

        {{-- Footer --}}
        @include('layouts.partials.footer')

    </div>
</div>

@stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', 'Terjadi Kesalahan') | SSIS
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="ssis-body">

    <div
        class="min-vh-100 d-flex align-items-center justify-content-center"
    >

        <div class="container-tight">

            @yield('content')

        </div>

    </div>

</body>

</html>
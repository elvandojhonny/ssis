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
        @yield('title', 'Ujian') | SSIS
    </title>


    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    @stack('styles')


    <style>

        /*
        |--------------------------------------------------------------------------
        | EXAM LAYOUT
        |--------------------------------------------------------------------------
        |
        | Layout khusus halaman pengerjaan CBT.
        |
        | Tidak memiliki:
        |
        | - Sidebar
        | - Navbar
        | - Footer
        | - Navigasi utama SSIS
        |
        */

        html,
        body {
            min-height: 100%;
            margin: 0;
        }


        body.exam-body {
            min-height: 100vh;
            background:
                var(
                    --tblr-bg-surface-secondary,
                    #f6f8fb
                );
        }


        .exam-page {
            width: 100%;
            min-height: 100vh;
        }


        .exam-content {
            width: 100%;
            min-height: 100vh;
            padding:
                24px;
        }


        .exam-container {
            width: 100%;
            max-width: 1600px;
            margin:
                0 auto;
        }


        /*
         * Tablet.
         */
        @media (
            max-width: 991.98px
        ) {

            .exam-content {
                padding:
                    16px;
            }

        }


        /*
         * Mobile.
         */
        @media (
            max-width: 575.98px
        ) {

            .exam-content {
                padding:
                    12px;
            }

        }

    </style>

</head>


<body class="exam-body">


<div class="exam-page">

    <main class="exam-content">

        <div class="exam-container">

            @yield('content')

        </div>

    </main>

</div>


@stack('scripts')


</body>

</html>
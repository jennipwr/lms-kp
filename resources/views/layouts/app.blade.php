<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/favicon.png') }}" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>

<body>

    <!-- Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
        data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- Main wrapper -->
        <div class="body-wrapper">

            {{-- Header --}}
            @if (View::exists('layouts.header'))
                @include('layouts.header')
            @endif

            <!-- Content -->
            <div class="container-fluid">

                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif

            </div>

        </div>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>

</body>

</html>
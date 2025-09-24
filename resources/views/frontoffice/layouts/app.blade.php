<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title', 'Bookly - Bookstore eCommerce')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="author" content="Templates Jungle">
    <meta name="keywords" content="@yield('keywords', 'Tailwind CSS eCommerce HTML CSS Template')">
    <meta name="description"
        content="@yield('description', 'Bookly is Bookstore eCommerce TailwindCSS Website Template')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <!-- Laravel Mix Assets -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Vendor Styles (after app.css for overrides) -->
    <link rel="stylesheet" type="text/css" href="{{ asset('template/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('template/css/style.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">

    @stack('styles')
</head>

<body>
    @include('frontoffice.layouts.partials.svg-icons')

    @include('frontoffice.layouts.partials.preloader')

    @include('frontoffice.layouts.partials.search-popup')

    @include('frontoffice.layouts.partials.header')

    <main>
        @yield('content')
    </main>

    @include('frontoffice.layouts.partials.footer')

    <!-- Scripts -->
    <script src="{{ asset('template/js/jquery-1.11.0.min.js') }}"></script>
    <!-- Swiper.js -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js" defer></script>
    <script src="{{ asset('template/js/plugins.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/js/script.js') }}"></script>

    <!-- Laravel Mix JS -->
    <script src="{{ mix('js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>
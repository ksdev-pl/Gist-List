<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title', 'Gist List')</title>
        <meta name="description" content="Clear Organization of Your Gists">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="{{ url('/css/all.css') }}">
        @include('_css_urls')
        @yield('styles')
    </head>
    <body>
        <!--[if lt IE 9]>
            <p class="browserupgrade">
                You are using an <strong>outdated</strong> browser.
                Please <a href="http://browsehappy.com/">upgrade your browser</a>.
            </p>
        <![endif]-->

        <!-- Preloader -->
        <div class="mask">
            <div id="loader"></div>
        </div>

        @yield('content')

        @yield('store')
        <script src="{{ url('/js/all.js') }}"></script>
        @include('_messages')
        @yield('scripts')
    </body>
</html>

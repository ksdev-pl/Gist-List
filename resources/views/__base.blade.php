<!doctype html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>@yield('title', 'Gist List')</title>
        <meta name="description" content="Clear Organization of Your Gists">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="{{ url('/css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
        @yield('styles')
    </head>
    <body>
        <!-- Preloader -->
        {{--<div class="mask">
            <div id="loader"></div>
        </div>--}}

        <div id="app">
            @yield('content')
        </div>

        <script src="{{ url('/js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
        @yield('scripts')
    </body>
</html>

<!doctype html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="Clear Organization of Your Gists">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gist List')</title>
    <link rel="stylesheet" href="{{ url('/css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @yield('styles')
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <!-- Preloader -->
    <div class="mask">
        <div id="loader"></div>
    </div>

    <div id="app">
        @yield('content')
    </div>

    @if (isset($state) && $state instanceof \Illuminate\Support\Collection)
        <script>
            window.state = {!! $state !!}
        </script>
    @endif
    <script src="{{ url('/js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
    @yield('scripts')
</body>
</html>

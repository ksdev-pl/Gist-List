<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Clear Organization of Your Gists">
    <title>@yield('title', 'Gist List')</title>

    <link href="{{ url(elixir('css/all.css')) }}" rel="stylesheet">
    @include('_css_urls')
    @yield('styles')
</head>
<body>
<!-- Preloader -->
<div class="mask">
    <div id="loader"></div>
</div>

@yield('content')

<script src="{{ url(elixir('js/all.js')) }}"></script>
@include('_messages')
@yield('scripts')
</body>
</html>

<style>
    #loader {
        background:url("{{ url('/img/loader.gif') }}") no-repeat center;
    }
    @font-face {
        font-family: 'Glyphicons Halflings';
        src: url("{{ url('/fonts/glyphicons-halflings-regular.eot') }}");
        src: url("{{ url('/fonts/glyphicons-halflings-regular.eot?#iefix') }}") format("embedded-opentype"),
        url("{{ url('/fonts/glyphicons-halflings-regular.woff2') }}") format("woff2"),
        url("{{ url('/fonts/glyphicons-halflings-regular.woff') }}") format("woff"),
        url("{{ url('/fonts/glyphicons-halflings-regular.ttf') }}") format("truetype"),
        url("{{ url('/fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular') }}") format("svg");
    }
    @font-face {
        font-family: 'FontAwesome';
        src: url("{{ url('/fonts/fontawesome-webfont.eot?v=4.4.0') }}");
        src: url("{{ url('/fonts/fontawesome-webfont.eot?#iefix&v=4.4.0') }}") format("embedded-opentype"),
        url("{{ url('/fonts/fontawesome-webfont.woff2?v=4.4.0') }}") format("woff2"),
        url("{{ url('/fonts/fontawesome-webfont.woff?v=4.4.0') }}") format("woff"),
        url("{{ url('/fonts/fontawesome-webfont.ttf?v=4.4.0') }}") format("truetype"),
        url("{{ url('/fonts/fontawesome-webfont.svg?v=4.4.0#fontawesomeregular') }}") format("svg");
        font-weight: normal;
        font-style: normal;
    }
    @font-face {
        font-family: 'octicons';
        src: url("{{ url('/fonts/octicons.eot?#iefix') }}") format('embedded-opentype'),
        url("{{ url('/fonts/octicons.woff')}}") format('woff'),
        url("{{ url('/fonts/octicons.ttf')}}") format('truetype'),
        url("{{ url('/fonts/octicons.svg#octicons')}}") format('svg');
        font-weight: normal;
        font-style: normal;
    }
</style>

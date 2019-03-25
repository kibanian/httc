<html>
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <div id="app">
            <base-component></base-component>
        </div>
    </body>
    <script src="{{ mix('js/app.js') }}"></script>
</html>
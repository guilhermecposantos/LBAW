<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Eventopia</title>
        <link rel="icon" href="../../img/icon.png">
        <script type="text/javascript" src="{{ url('js/app.js') }}" defer>
        </script>
    </head>
    <body>
        <img id="logo" src="../../img/logo.png" alt="logo" width="200px">
            @yield('content')
    </body>
</html>
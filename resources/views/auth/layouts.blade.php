<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>เจริญมั่น คอนกรีต</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- <link rel="icon" href="../favicon.ico" type="image/x-icon" /> --}}

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/icon-kit/css/iconkit.min.css')}}">
        <link rel="stylesheet" href="{{asset('auth/css/theme.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/main.css')}}">
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    </head>

    <body>
        @yield('content')

    </body>
</html>

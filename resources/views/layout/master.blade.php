<!DOCTYPE html>
<html>
<head>
  <title>เจริญมั่นคอนกรีต</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
  {!! Html::style('font/font.css') !!}
  {!! Html::style('assets/plugins/@mdi/font/css/materialdesignicons.min.css') !!}
  {{-- {!! Html::style('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') !!} --}}
  <!-- end plugin css -->



  @stack('plugin-styles')

  <!-- common css -->

  {!! Html::style('css/app.css') !!}
  {!! Html::style('css/main.css') !!}
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}">

  <div class="container-scroller" id="app">
    @include('layout.header')
    <div class="container-fluid page-body-wrapper">
      @include('layout.sidebar')
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>

      </div>
    </div>
  </div>

  <!-- base js -->
  {!! Html::script('js/app.js') !!}
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  {{-- {!! Html::script('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') !!} --}}
  <!-- end base js -->



  <!-- common js -->
  {!! Html::script('assets/js/off-canvas.js') !!}
  {!! Html::script('assets/js/hoverable-collapse.js') !!}
  {!! Html::script('assets/js/misc.js') !!}
  {!! Html::script('assets/js/settings.js') !!}
  {!! Html::script('assets/js/todolist.js') !!}
  {!! Html::script('/plugins/daterangepicker/moment.min.js') !!}
  {!! Html::script('js/jquery.min.js') !!}
  {!! Html::script('/plugins/daterangepicker/daterangepicker.min.js') !!}
  
  <!-- plugin js -->
    @stack('plugin-scripts')
  <!-- end plugin js -->

  <!-- end common js -->

  @stack('custom-scripts')
</body>
</html>

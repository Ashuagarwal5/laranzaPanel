<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="{{asset('dashboard/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('dashboard/css/animate.min.css')}}">
  <!-- <link rel="stylesheet" href="css/font-awesome.min.css"> -->
  <link rel="stylesheet" href="{{asset('dashboard/css/template.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet"> 
<link href="{{asset('dashboard/css/dashboard.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('dashboard/css/charts/apexcharts.css')}}">
@yield('style')
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/ico/favicon.ico')}}">
</head>
<body>
    @include('dashboard.panels.header')
    <div class="container-fluid">
        <div class="row">
            @include('dashboard.layout._leftmenu')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @yield('content')
            </main>
        </div>
    </div>
    <script src="{{asset('dashboard/js/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.form.js') }}"></script>
  <script src="{{ asset('assets/js/formClass.js') }}"></script>

  <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
  <script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="{{asset('dashboard/js/bootstrap.min.js')}}"></script>
    <script src="https://kit.fontawesome.com/a57a87ec82.js" crossorigin="anonymous"></script>
    <script src="{{asset('dashboard/js/wow.min.js')}}"></script>
    
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=615fda409f6bb0001305baa9&product=sop' async='async'></script>
    @yield('scripts')
    <script>
        $(document).ready(function() {
            new WOW().init();
        });
        </script>
</body>
</html>
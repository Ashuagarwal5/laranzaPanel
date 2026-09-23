@php($website_setting  = App\WebsiteSetting::getGeneralSetting())


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" />
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="{{asset('assets/default/img/favicon.png')}}" rel="icon" />
  <title>@section('title')@if(!empty($website_setting->site_title)){!!$website_setting->site_title!!}@else{{config('constants.frontend.DefaultTitle')}}@endif @show</title>
  @if(isset($website_setting->meta_description))
  <meta name="description" content="{!! $website_setting->meta_description !!}">
  <meta name="keywords" content="{!! $website_setting->meta_keywords !!}">
  @endif

  <link href="{{asset('assets/default/css/style.css')}}" rel="stylesheet" />

  <link href="{{asset('assets/default/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{asset('assets/default/fontawesome/css/font-awesome.min.css')}}" rel="stylesheet" />
  <link href="{{asset('assets/default/css/flags.css')}}"  rel="stylesheet" />

  <link href="https://fonts.googleapis.com/css?family=Questrial&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Caudex&display=swap" rel="stylesheet">

  <link href="{{asset('assets/default/css/toastr.css')}}" rel="stylesheet">

       <link href="{{asset('assets/default/owl-carousel/owl.carousel.css')}}" rel="stylesheet">
     <link href="{{asset('assets/default/owl-carousel/owl.theme.css')}}" rel="stylesheet">
      <link href="{{asset('assets/default/owl-carousel/owl.transitions.css')}}" rel="stylesheet">

  @yield('header_styles')
</head>
<body >

 <div class="header-top">
  <div class="container-fluid">
   <div class="row">
     <div class="col-md-12">
       <div class="logo logo-dsp"><a href="{{route('home')}}">
        <img style="width:350px;" src='{{ asset("uploads/logo/".$website_setting->logo)}}' alt="Prime Comfort" class="img-responsive" /></a>
      </div>
    </div>
  </div>
</div>
</div>

<div class="logo logo-mobile"><a href="{{route('home')}}">
  <img src='{{ asset("uploads/logo/".$website_setting->logo)}}' alt="Hempstrol" class="img-responsive" /></a>
</div>
<div class="clearfix"></div>
<div class="select-lang">
  <form>
    <div class="form-group">
      <div id="options"
      data-input-name="country2"
      data-selected-country="{{session()->get('country_code') ? session()->get('country_code') : 'IN'}}">
    </div>
  </div>
</form>
</div>

<div class="clearfix"></div>


<!-- Content -->
@yield('content')


<!--global js start-->



<script src="{{asset('assets/default/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/default/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/default/js/jquery.flagstrap.min.js')}}"></script>
<script src="{{asset('assets/default/js/toastr.min.js')}}"></script>
<script src="{{asset('assets/default/js/jquery.form.js')}}"></script>
<script src="{{asset('assets/default/js/formClass.js')}}"></script>



<!--global js end-->
<!-- begin page level js -->
@yield('footer_scripts')
<!-- end page level js -->
</body>

</html>

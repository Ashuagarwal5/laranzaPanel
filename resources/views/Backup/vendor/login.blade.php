@extends('layouts/default')

{{-- Page title --}}
@section('title')
Seller Login
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">


@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

<!-- page wapper-->
<div class="columns-container account-bg">
  <div class="container" id="columns">
    <!-- breadcrumb -->
    <!--<div class="breadcrumb clearfix"> <a class="home" href="#" title="Return to Home">Home</a> <span class="navigation-pipe">&nbsp;</span> <span class="navigation_page">Change Password</span> </div>-->
    <!-- ./breadcrumb -->
    <!-- row -->
    <div class="ac-menu">
      <div class="column col-xs-12 col-sm-5" id="left_column">
        <!-- block category -->
        <div class="account-menu">
		@include('notifications')


        <div class="loginformseller">
		<form class="form-horizontal seller_login_form" method="post" action="{{ URL::to('sellerpanel/signin') }}" >
	   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
	   <div id="login_message" ></div>

        <h3 class="page-heading"><i class="fa fa-user"></i>Seller Login</h3>
        <div class="box-authentication" style="padding:30px 0;">
                        <h3>Login Now !</h3>

                        <label for="emmail_login">Email address</label>
                        <input type="text" name="email_login" class="form-control" id="emmail_login"  value="{!! old('email_login') !!}">



                        <label for="password_login">Password</label>
                        <input type="password" name="password_login" class="form-control" id="password_login">

                        <p class="forgot-pass"><a href="#">Forgot your password?</a></p>
       <!-- <button class="button btn btn-block btn-pink btn-login"><i class="fa fa-lock"></i> Login Now</button>-->
                      <button class="button btn btn-block btn-pink btnsbmt-login" type="submit" ><i class="fa fa-lock"></i>  Login Now</button>


                    </div>


       </form>

       </div>

        <div class="clr"></div>

          <div class="emailformseller"   style="display:none">


                  </div>



        </div>


      </div>
      <div class="center_column col-xs-12 col-sm-7 right-page" id="center_column" style="min-height:auto;">
        <!-- page heading-->
        <h2 class="page-heading"> <span class="page-heading-title2" style="text-transform:capitalize;">Seller Sign up <abbr style="font-size:13px; margin-top:5px;">(If you allready not have an account)</abbr></span> </h2>
        <!-- Content page -->
        <div class="account clearfix">

                <div class=" col-sm-12">
              <div class="row">
              <div class="ac-carop">
                <!--<div class="col-sm-6">
                <div class="facility">
                <ul>
               <li> <i class="fa fa-check"></i> Lorem ipsum dolor sit amet</li>
                 <li> <i class="fa fa-check"></i> consectetuer adipiscing elit </li>
                  <li> <i class="fa fa-check"></i> Aenean commodo ligula eget dolor</li>
                 <li> <i class="fa fa-check"></i> Aenean massa Cum sociis natoque</li>
                  <li> <i class="fa fa-check"></i> penatibus et magnis dis parturient</li>
                  <li> <i class="fa fa-check"></i> montes nascetur ridiculus mus</li>
                   <li> <i class="fa fa-check"></i> Lorem ipsum dolor sit amet</li>

                </ul>
                <div class="clr"></div>
                </div>
                </div>-->
                <div class="n-feature col-sm-8">
              <ul class="login-features">

                         <li><i><img width="40" height="40" alt="" src="{{ asset('assets/default/images/simple-icon-56x56.png') }}"></i>
                <div>
                  <h4>Simple</h4>
                  <p>Use our advance but easy to use tools to send gifts and Greitting to your friends .</p>
                </div>
              </li>
                            <li><i><img width="40" height="40" alt="" src="{{ asset('assets/default/images/secure-icon-56x56.png') }}"></i>
                <div>
                  <h4>Satisfaction Guaranteed</h4>
                  <p>Your satisfaction is our aim. We provide high level of customer support.</p>
                </div>
              </li>
                            <li><i><img width="40" height="40" alt="" src="{{ asset('assets/default/images/guaranteed-icon-56x56.png') }}"></i>
                <div>
                  <h4>Everything for FREE</h4>
                  <p>All of our services are absolutely for FREE.</p>
                </div>
              </li>

                         <li><i><img width="40" height="40" alt="" src="{{ asset('assets/default/images/simple-icon-56x56.png') }}"></i>
                <div>
                  <h4>Simple</h4>
                  <p>User our advance but easy to use tools to search and book a provider.</p>
                </div>
              </li>
                            <li><i><img width="40" height="40" alt="" src="{{ asset('assets/default/images/secure-icon-56x56.png') }}"></i>
                <div>
                  <h4>Satisfaction Guaranteed</h4>
                  <p>Your satisfaction is our aim. We provide high level of customer support.</p>
                </div>
              </li>


            </ul>
                 <div class="clr"></div>
                </div>
                <div class="clr"></div>
                </div>
                </div>

                </div>
                <center><div class="col-sm-6 col-sm-offset-3">  <a href="{{ route('seller.registration') }}" class="btn btn-pink btn-lg btn-block btn-sign"> Register Now !</a></div></center>

        </div>
        <!-- ./Content page -->
      </div>
     <div class="clr"></div>
    </div>
    <!-- ./row-->
  </div>
</div>
<!-- ./page wapper-->



    <!-- //Container End -->
@stop

{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->


 <script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/default/js/seller.js') }}" type="text/javascript"></script>

    <!--page level js ends-->

@stop

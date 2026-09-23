@php ($siteSettingList=App\WebsiteSetting::getWebsiteSettingAdmin())
<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dolovery Seller Landing - Start Selling</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>


    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!--global css starts-->





	 <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/font-awesome/css/font-awesome.min.css') }}">
  <!--  <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/select2/css/select2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/responsive.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/jquery-ui.css') }}">-->
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/sellerlanding/css/landing/reset.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/sellerlanding/css/template.css') }}">
		   <link rel="stylesheet" type="text/css" href="{{asset('assets/default/css/smart-forms.css')}}" />


	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">


<!--     <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|PT+Sans:400,700" rel="stylesheet">
     <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">-->
     <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">

    <!--end of global css-->
    <!--page level css-->

    @yield('header_styles')
    <!--end of page level css-->
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

<div class="lp-element lp-pom-root" id="lp-pom-root">
<div xmlns="" id="lp-pom-root-color-overlay"></div>
<div class="lp-positioned-content">
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-178">
<p class="lplh-22">
<span style="color:#333; display:block;text-align:center;"><span style="font-size:14px;"><span style="font-family: 'Open Sans', sans-serif ; text-align: center; white-space: pre-wrap;">Copyrights © 2019 Dolovery . All Rights Reserved. </span></span></span>
</p>
</div>

<div class="lp-element lp-pom-box" id="lp-pom-box-183">
<div id="lp-pom-box-183-color-overlay"></div>
<div class="lp-element lp-pom-image" id="lp-pom-image-393">
<div class="lp-pom-image-container" style="overflow: hidden;">
<a href="{{ (config('constants.sociallink.facebook')) }}" target="_blank">
	<!--<img  alt="" src="{{ asset('assets/default/landingimages/facebook.png') }}">-->
    <i class="fa fa-facebook"></i>
	</a>
</div>
</div>
<div class="lp-element lp-pom-image" id="lp-pom-image-394">
<div class="lp-pom-image-container" style="overflow: hidden;">
<a href="{{ (config('constants.sociallink.twitter')) }}" target="_blank">
	<!--<img  alt="" src="{{ asset('assets/default/landingimages/twitter.png') }}">-->
    <i class="fa fa-twitter"></i>
</a>
</div>
</div>
<div class="lp-element lp-pom-image" id="lp-pom-image-395">
<div class="lp-pom-image-container" style="overflow: hidden;">
<a href="{{ (config('constants.sociallink.googlepuls')) }}" target="_blank">
		<!--<img  alt="" src="{{ asset('assets/default/landingimages/google-plus.png') }}">-->
        <i class="fa fa-google-plus"></i>
</a>
</div>
</div>
<div class="n1">
<a href="{{ (config('constants.sociallink.pinterest')) }}" target="_blank"> <i class="fa fa-pinterest-p"></i></a>
</div>
<div class="n2">
 <a href="{{ (config('constants.sociallink.linkedin')) }}" target="_blank"><i class="fa fa-linkedin"></i></a>
</div>
</div>





<a class="lp-element lp-pom-button" onclick="hrefvalue(this)" id="lp-pom-button-245">
	<span class="">Apply For Selling</span></a>
<div class="lp-element lp-pom-box" id="lp-pom-box-256">
<div id="lp-pom-box-256-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-260">
<p class="lplh-22" style="text-align: center;">
<strong><span style="font-size:20px;"><span style="font-family: 'Open Sans', sans-serif;">Easy Building</span></span></strong>
</p>
<p class="lplh-26" style="text-align: center;">
<span style="font-size:16px;"><span style="color: rgb(169, 169, 169); font-family: 'Open Sans', sans-serif ; font-size:13px;">Upload your products/catalog, customize your store and manage your orders in a very convenient way.</span></span>
</p>
</div>
<a class="lp-element lp-pom-button" onclick="hrefvalue(this)" id="lp-pom-button-263" href="#"><span class="">Apply Now</span></a>
<div class="lp-element lp-pom-image" id="lp-pom-image-406">
<div class="lp-pom-image-container" style="overflow: hidden;">
	<center><img  alt="" src="{{ asset('assets/default/landingimages/b1.png') }}"></center>
</div>
</div>
</div>
<div class="businessmen-img"><img  alt="" src="{{ asset('assets/default/sellerlanding/img/businessman.png') }}" ></div>
<div class="lp-element lp-pom-box" id="lp-pom-box-376">
<div id="lp-pom-box-376-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-375">
<p class="lplh-8">
<span style="color:#696969;"><span style="font-family: 'Open Sans', sans-serif;"><span style="font-size:22px;">Provide Basic Information</span></span></span>
</p>
<p class="lplh-18">
<span style="color: rgb(169, 169, 169); font-family: 'Open Sans', sans-serif ; font-size: 16px; line-height: 26px;">We need your basic business details, Business Identity Number, TIN/VAT number and bank account details</span>
</p>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-463">
<div id="lp-pom-box-463-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-464">
<p class="lplh-58" style="text-align: center;">
<span style="color:#b1373a;"><span style="font-size:48px;"><span style="line-height:23px;">1</span></span></span>
</p>
</div> 
</div>
</div>
<div class="lp-element lp-pom-image" id="lp-pom-image-385">
<div class="lp-pom-image-container" style="overflow: hidden;">
<a href="{{route('home')}}">
<img  src="{{URL::to(App\Helpers\Thumbnail::image("logo/$siteSettingList->logo","300","75","f")) }}"  alt="Dolovery" >
</a>
</div>
</div>


<div class="lp-element lp-pom-text nlh" id="lp-pom-text-387">
<div class="bmg-newad" style="text-align:left; background:none !important;">

        <p class="pull-left" style="color:#fff;">Sellers Support No.:
0123456</p>

  <div id="user-info-top" class="user-info pull-right">
                <div class="dropdown" style=" margin-right:9px;">
                @if(Sentinel::guest())
                    <a class="current-open mele" aria-haspopup="true" aria-expanded="false" href="javascript:" onclick="hrefvalue(this)"><span style="color:#fff; text-transform:capitalize;text-decoration:none !important; ">Seller Login</span></a>
                  
				
				
				@else
                <a class="current-open mele" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript:"><span style="color:#fff; text-transform:capitalize;text-decoration:none !important; ">Seller Dashboard</span></a>
                @endif

							<ul class="dropdown-menu mega_dropdown" role="menu">
							@if(Sentinel::guest())

							@else
							

							@if(Sentinel::getUser()->inRole('seller'))

							<li><a href="{{ route('sellerdashboard') }}">Seller Dashboard</a>

							@endif

							@if(Sentinel::getUser()->inRole('admin'))

							<li><a href="{{ URL::to('admin') }}">Admin</a>
							@endif

							<li><a href="{{ route('seller_logout') }}">Logout</a>

							</li>
							@endif

							</ul>


                </div>
            </div>
        <div class="clr"></div>
        </div>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-389">
<p class="lplh-32" style="text-align: center;">
<span style="color:#ffffe0;"><span><span style="font-size:20px;">Create your online store with us, it’s simple, free, secure and proven by hundreds of sellers across the world</span></span></span>
</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-391">
<p class="lplh-58" style="text-align: center;">
<span style="font-size:48px;"><strong><span style=""><font color="#ffffff">START SELLING ONLINE NOW</font></span></strong></span>
</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-392">
<p class="lplh-29" style="text-align: center;">
<span style="color:#dd4549;"><span style="font-size:18px;"><span style="font-family:;">A great chance of more exposure for your business!</span></span></span>
</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-398">
<p class="lplh-8">
<span style="font-size:16px;"><span style="color:#808080;"><span style="font-family:">TRUSTED BY </span></span></span>
</p>
<p class="lplh-16">
<span style="font-size:16px;"><span style="font-family:;"><span style="color:#696969;"><strong>TOP COMPANIES</strong></span></span></span>
</p>
</div>
<div class="compnies-11">
<ul>
<li><img  alt="" src="{{ asset('assets/default/sellerlanding/img/demo-1.png') }}" ></li>
<li><img  alt="" src="{{ asset('assets/default/sellerlanding/img/demo-2.png') }}" ></li>
<li><img  alt="" src="{{ asset('assets/default/sellerlanding/img/demo-3.png') }}" ></li>
<li><img  alt="" src="{{ asset('assets/default/sellerlanding/img/demo-4.png') }}" ></li>
</ul>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-407">
<div id="lp-pom-box-407-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-408">
<p class="lplh-22" style="text-align: center;">
<strong><span style="font-size:20px;"><span style="font-family:;">Increase Sale</span></span></strong>
</p>
<p class="lplh-26" style="text-align: center;">
<span style="font-size:16px;"><span style="color: rgb(169, 169, 169);  font-size:13px;">Sell your items to millions of customer across the World and get exposure to your business </span></span>
</p>
</div>
<div class="lp-element lp-pom-image" id="lp-pom-image-410">
<div class="lp-pom-image-container" style="overflow: hidden;">
<center>
<img  alt="" src="{{ asset('assets/default/landingimages/b2.png') }}">
</center>
</div>
</div>
<a class="lp-element lp-pom-button" id="lp-pom-button-457" href="#" onclick="hrefvalue(this)"><span class="">Apply Now</span></a>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-411">
<div id="lp-pom-box-411-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-412">
<p class="lplh-22" style="text-align: center;">
<strong><span style="font-size:20px;"><span style="font-family:;">SAVE MONEY</span></span></strong>
</p>
<p class="lplh-26" style="text-align: center;">
<span style="font-size:16px;"><span style="color: rgb(169, 169, 169);  font-size:13px;">Free setup of your store, free promotion & advert campaigns by us, lower commission rate</span></span>
</p>
</div>
<div class="lp-element lp-pom-image" id="lp-pom-image-414">
<div class="lp-pom-image-container" style="overflow: hidden;">
	<center><img  alt="" src="{{ asset('assets/default/landingimages/b3.png') }}" ></center>
</div>
</div>
<a class="lp-element lp-pom-button" id="lp-pom-button-458" href="#" onclick="hrefvalue(this)"><span class="">Apply Now</span></a>
</div>





<div class="lp-element lp-pom-text nlh" id="lp-pom-text-467">
<p class="lplh-38" style="text-align: center;">
<font><span style="font-size: 28px;"><b>HOW TO START</b></span></font>
</p>
<p class="lplh-16" style="text-align: center;">
<span><span style="font-size:13px;"><span style="color: rgb(169, 169, 169);">Setup an online store with us is very easy, just few steps & get started</span></span></span>
</p>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-469">
<div id="lp-pom-box-469-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-470">
<p class="lplh-8">
<span style="color:#696969;"><span style="font-family: 'Open Sans', sans-serif;"><span style="font-size:22px;">List your items</span></span></span>
</p>
<p class="lplh-18">
<span style="color: rgb(169, 169, 169); font-family: 'Open Sans', sans-serif ; font-size: 16px; line-height: 26px;">Add your items with your own seller panel, Your listed items will be published in our powerfull App.</span>
</p>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-471">
<div id="lp-pom-box-471-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-472">
<p class="lplh-58" style="text-align: center;">
<span style="color:#b1373a;"><span style="font-size:48px;"><span style="line-height:23px;">2</span></span></span>
</p>
</div>
</div>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-473">
<div id="lp-pom-box-473-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-474">
<p class="lplh-8">
<span style="color:#696969;"><span style="font-family: 'Open Sans', sans-serif;"><span style="font-size:22px;">Fulfill and ship orders</span></span></span>
</p>
<p class="lplh-18">
<span style="color: rgb(169, 169, 169); font-family: 'Open Sans', sans-serif ; font-size: 16px; line-height: 26px;">Ship orders to customers and get paid directly to your bank account</span>
</p>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-475">
<div id="lp-pom-box-475-color-overlay"></div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-476">
<p class="lplh-58" style="text-align: center;">
<span style="color:#b1373a;"><span style="font-size:48px;"><span style="line-height:23px;">3</span></span></span>
</p>
</div>
</div>
</div>
<a class="lp-element lp-pom-button" id="lp-pom-button-478" onclick="hrefvalue(this)" href="#"><span class="">	GET STARTED TODAY</span></a>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-479">
<p class="lplh-42" style="text-align: center;">
<span style="color:#ffffff;"><span style="font-size:26px;"><span style="font-family: 'Open Sans', sans-serif;"><span style="font-weight: bolder; text-align: center;">WHAT ARE YOU WAITING FOR?</span></span></span></span>
</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-480">
<p class="lplh-32" style="text-align: center;">
<span style="color:#808080;"><span style="font-family: 'Open Sans', sans-serif ; font-size: 16px; text-align: center;">Get your business to grow with the most powerful ecommerce marketplace</span></span>
</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-481">
<p style="text-align: center;">

</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-482">
<p class="lplh-38" style="text-align: center;">
<font><span style="font-size: 28px;"><b>What our sellers says</b></span></font>
</p>
<p class="lplh-16" style="text-align: center;">
<span style="font-size:16px;"><span style="color: rgb(169, 169, 169); font-family: 'Open Sans', sans-serif ; line-height:26px;">Our seller love to work with us, getting appreciations by many of them</span></span>
</p>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-487">
<div id="lp-pom-box-487-color-overlay"></div>
<div class="lp-element lp-pom-box" id="lp-pom-box-166">
<div id="lp-pom-box-166-color-overlay"></div>
<div class="lp-element lp-pom-image" id="lp-pom-image-221">
<div class="lp-pom-image-container" style="overflow: hidden;">

<img  alt="" src="{{ asset('assets/default/landingimages/stars.png') }}" >
</div>
</div>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-167">
<div id="lp-pom-box-167-color-overlay"></div>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-169">
<p style="text-align: center;">
<span style="font-family: 'Open Sans', sans-serif;"><span style="color:#808080;">Successfully selling items, transparent system, and quick payments.  Our sales figures are increasing rapidly, </span></span>
</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-170">
<p class="lplh-35" style="text-align: center;">
<span style="color:#82b541;"><strong><span style="font-size:22px;"><span style="font-family: 'Open Sans', sans-serif;">By Intercon Demo</span></span></strong></span>
</p>
</div>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-490">
<div id="lp-pom-box-490-color-overlay"></div>
<div class="lp-element lp-pom-box" id="lp-pom-box-172">
<div id="lp-pom-box-172-color-overlay"></div>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-492">
<p class="lplh-35" style="text-align: center;">
<span style="color:#3399ff;"><strong><span style="font-size:22px;"><span style="font-family: 'Open Sans', sans-serif;">By Company Logo</span></span></strong></span>
</p>
</div>
<div class="lp-element lp-pom-text nlh" id="lp-pom-text-493">
<p style="text-align: center;">
<span style="font-family: 'Open Sans', sans-serif;"><span style="color:#808080;">Very user friendly interface of seller panel, easy to use, their client support is also awesome</span></span>
</p>
</div>
<div class="lp-element lp-pom-box" id="lp-pom-box-494">
<div id="lp-pom-box-494-color-overlay"></div>
<div class="lp-element lp-pom-image" id="lp-pom-image-495">
<div class="lp-pom-image-container" style="overflow: hidden;">

<img  alt="" src="{{ asset('assets/default/landingimages/stars.png') }}" >
</div>
</div>
</div>
</div>

</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-386">
<div id="lp-pom-block-386-color-overlay"></div>
<div class="lp-pom-block-content">

</div>
</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-122">

<div id="lp-pom-block-122-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-123">
<div id="lp-pom-block-123-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-230">
<div id="lp-pom-block-230-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-255">
<div id="lp-pom-block-255-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>

<div class="lp-element lp-pom-block" id="lp-pom-block-418">
<div id="lp-pom-block-418-color-overlay"></div>
<div class="lp-pom-block-content">
<div class="col-xs-12">
            <div class="col-md-4 col-sm-6 col-xs-12">    
                <div class="aboutus-image float-left hidden-sm"><img  alt="" src="{{ asset('assets/default/sellerlanding/img/about-business.png') }}" ></div>
                </div>
            <div class="col-md-8 col-sm-6 col-xs-12">
                <div class="aboutus-content ">
                    <h1>About us <span>Dolovery</span></h1>
                    <h4>Dolovery Details</h4>
                    <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has oots in a piece of classitin literature from 45 BC, making it over 2000 years old. Richard McClint professor at Hamden dney College irginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature.</p>

                </div>
            </div>    
            </div>
</div>
</div>


<div class="lp-element lp-pom-block" id="lp-pom-block-271">
<div id="lp-pom-block-271-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-164">
<div id="lp-pom-block-164-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-477">
<div id="lp-pom-block-477-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>
<div class="lp-element lp-pom-block" id="lp-pom-block-176">
<div id="lp-pom-block-176-color-overlay"></div>
<div class="lp-pom-block-content"></div>
</div>
<div id="powered-by-spacer"></div>
<div id="powered-by-unbounce" style="display: block !important;position:absolute !important;visibility:visible !important;text-indent: 0 !important;bottom:0px !important;width:100% !important;height:28px !important;overflow:hidden !important;background:#0d0e0f !important;z-index:999999 !important;text-align:center !important;font-size:11px !important;color:#666 !important;font-weight:bold !important;">
<a href="{{route('home')}}" rel="nofollow" style="display: inline !important;position:static !important;visibility:visible !important;text-indent: 0 !important;z-index:999999 !important;font-size:11px !important;color:#fff !important;font-family:'trebuchet ms' !important;font-weight:bold !important;line-height:26px !important;color:#aaa !important;" title="Build, Publish and Test Landing Pages with Unbounce">Dolovery</a>
</div>
</div>


<!--<div id="lp-pom-image-462-holder" class="lp-pom-image-holder"></div>-->

        <div id="modal-regular" class="modal fade" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div style="text-align:center;"> <img style="float:center;" src="" alt="" class="loading"></div>
	  </div>
	</div>
    <div class="clr"></div>
</div>


		<script src="{{ asset('assets/default/lib/jquery/jquery-1.11.2.min.js') }}"></script>
		<script src="{{ asset('assets/default/lib/bootstrap/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('assets/default/js/toastr.min.js') }}"></script>
		<script src="{{ asset('assets/default/js/formClass.js') }}"></script>
		<script src="{{ asset('assets/default/js/jquery.form.js') }}"></script>
		<script src="//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>


<script>
	var popUp ="";
	@if (!Sentinel::check())
		var route_val = "{{ route('login.popup','seller')}}"
		var popUp =true;
	@elseif(Sentinel::check())
		<? $this->userId = Sentinel::getUser()->id;?>
		@if(App\UserService::checkService($this->userId))
			var route_val ="{{ route('sellerdashboard')}}"
		@else
			var route_val ="{{ route('seller.registration')}}"
		@endif
	@endif
function hrefvalue(val)
{
	if(popUp==true)
	{
			$(val).attr({
			href:route_val,
			'data-toggle':"modal",
			'data-target':"#modal-regular"
		});
	}
	else
	$(val).attr('href',route_val);

}

$('body').on('hidden.bs.modal', '.modal', function () {
  	$(this).removeData('bs.modal');
});

function openModel(url,position)
{
	$.get(url, function(data){
		$('#'+position).find('.modal-content').html(data);
		return false;
	});
}

  function ajaxPageCallBack(data)
{
  if(data.callback_type =="registration temp"){
     $('.tmp_re_div').hide();
     $('.Verifyotp_div').show();
	  }

	   if (data.callback_type == "forgot_option_main") {
    $('.login_forgot_option').hide();
    $('.login_otp_main').show();
  }



}

function check_forgot_option(el)
{
      var value=    $(el).val();

    if( value == 'otp_mobile'){


           $('.mobile_no_input').show();
            $('.email_input').hide();

		}
		else if( value == 'tmp_pass_email'){
           $('.email_input').show();
             $('.mobile_no_input').hide();

		}


}


$(document).on("click", ".forget-pas-pop", function (event) {
    $('.login_div').hide();
    //$('.tmp_re_div').hide();
    $('.login_forgot_option').show();
  });

  $(document).on("click", ".back_login_forgot_option", function (event) {


    $('.login_forgot_option').hide();
      $('.login_div').show();
  });


</script>

</body>

</html>

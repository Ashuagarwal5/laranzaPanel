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



     <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
<style>
.terms-div p {
	font-size:13px;
	line-height:24px;
	margin-bottom:10px;
	color:#666;
	}
.terms-div ul,.terms-div ol {
	list-style:inside circle !important;
	padding-left:3px;
	}

.terms-div ul li,.terms-div ol li {
	font-size:13px;
	color:#666;
	line-height:24px;

	}
.terms-div h4 {
	color:#555;
	padding:15px 0;
	font-weight:700;
	font-size:14px;
	}	
.terms-div {
    background: #f5f5f5 none repeat scroll 0 0;
    height: 700px;
    overflow: auto;
    padding: 10px;
	border:solid 1px #ddd;
}
.formmessage {
	 color: red;
    position: absolute;
    top: -15px;
	}
.m-div {
	line-height:16px;
	}					
</style> 

    <!--end of global css-->
    <!--page level css-->

    @yield('header_styles')
    <!--end of page level css-->
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

<div class="lp-element lp-pom-root" id="lp-pom-root">
<div xmlns="" id="lp-pom-root-color-overlay1"></div>

<div class="lp-positioned-content1">

	     <!-- page wapper-->
<div class="columns-container account-bg">
  <div class="container" id="columns">
    <!-- breadcrumb -->
    <!--<div class="breadcrumb clearfix"> <a class="home" href="#" title="Return to Home">Home</a> <span class="navigation-pipe">&nbsp;</span> <span class="navigation_page">Change Password</span> </div>-->
    <!-- ./breadcrumb -->
    <!-- row -->
    <div class="ac-menu">
      <div class="column col-xs-12 col-sm-12" id="left_column">
        <!-- block category -->
        <div class="account-menu">
        <h3 class="page-heading"><i class="fa fa-user"></i>Seller Account Registration </h3>

<ul id="wizardStatus">
   <li><span class="stap">1</span> Basic Information</li>
  <li> <span class="stap">2</span> Terms & Condition</li>
  <li class="current"><span class="stap">3</span> Success</li>

</ul>

 
	
<div class="basic">
<div class="panel">


  <div class="col-sm-12">  <div class="form-group">
  
    <div class="col-sm-12">
   <br>
   <br>
   <br>
    	<div class="alert alert-success"><center>
		Thank you for sing-up as seller, after necessary verification we will make your account active, it may take 24-48 Hours to get activated a seller account
		In the meanwhile you are suggested update your profile related information
		Access Your Seller Panel - 
			 <a href="{{route('sellerdashboard')}}">Click Here</a> !!
    	</center></div>

    </div>
  </div></div>
  <div class="clr"></div>
@include('notifications')


        <div class="clr"></div>
        </div>


      </div>

     <div class="clr"></div>
    </div>
    <!-- ./row-->
  </div>
</div>
<!-- ./page wapper-->
</div>
</div>
</div>
</div>
<div class="clr"></div>



</body>
</html>

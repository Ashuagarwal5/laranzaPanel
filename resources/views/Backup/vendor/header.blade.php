<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1 ,user-scalable=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
    @section('title')
        | Vendor Panel
        @show

    </title>

<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500" rel="stylesheet"> 
    <!-- Bootstrap core CSS -->
			<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">
			<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/bootstrap/css/bootstrap.min.css') }}" />
			<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/style.css') }}" />
			<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/dashboard.css') }}" />
			<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/smart-forms.css') }}" />
			<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/lib/font-awesome/css/font-awesome.min.css') }}" />


  <style>
.panel-heading h3 {
	margin-top:0;
	}

</style>

<!--page level css-->
    @yield('header_styles')
    <!--end of page level css-->
  </head>

<body>



  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

         <?php
				  $id= Sentinel::getUser()->id;
				  $logo=App\SellerDetails::getLogo(Sentinel::getUser()->id);


				?>
        <a class="navbar-brand" style="color:#fff; padding-top:20px;" href="{{route('sellerdashboard')}}">{{App\Helpers\limitedcharacter::DisplayLimitesCharacter($logo->company_name,25)}}</a>

        </div>
        <div id="navbar" class="navbar-collapse collapse">


          <ul class="nav navbar-nav navbar-right">
        
<!--
<li class="dropdown">
       <a href="#" class="dropdown-toggle noti-tg" data-toggle="dropdown"><span class="label label-pill label-danger count" style="border-radius:10px;"></span> <span class="glyphicon glyphicon-bell" style="font-size:18px;"></span></a>
       <ul class="dropdown-menu noti-list text-center" style="width: 300px; height: 200px; overflow: auto"></ul>



      </li>
-->
<!--
      <li class="dropdown">
       <a href="#" class="dropdown-toggle anc-tg" data-toggle="dropdown"><span class="label label-pill label-danger anc-count" style="border-radius:10px;"></span> <span class="glyphicon glyphicon-bullhorn " style="font-size:18px;"></span></a>
       <ul class="dropdown-menu anc-list text-center"  style="width: 300px; height: 200px; overflow: auto"></ul>
      </li>
-->
             <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Welcome  {{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }} <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{!! route('sellerdashboard') !!}">Dashboard</a></li>
            <li><a href="{{ route('seller.company') }}">Company Profile</a></li>
            <li><a href="{{ route('seller.accountsetting') }}">Change Password</a></li>
            <li><a href="{{ route('seller_logout') }}">Logout</a></li>
            </ul>
        </li>
        
        
<li class="dis-none-ds {!! (Request::is('sellerpanel') ? 'active' : '') !!}" {!! (Request::is('sellerpanel') ? 'id="active"' : '') !!}><a href="{!! route('sellerdashboard') !!}"> <i aria-hidden="true" class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="dis-none-ds {!! (Request::is('seller.product.create') ? 'active' : '') !!}" {!! (Request::is('seller.product.create') ? ' id="active"' : '') !!}><a href="{!! route('seller.product.create') !!}"> <i aria-hidden="true" class="fa fa-cart-plus"></i> Add Product</a></li>
			<li class="dis-none-ds {!! (Request::is('sellerpanel/product') ? 'active' : '') !!}" {!! (Request::is('sellerpanel/product') ? ' id="active"' : '') !!}><a href="{!! route('productlist') !!}"> <i aria-hidden="true" class="fa fa-book"></i> Manage Products</a></li>
			
		
			

            <li class="dis-none-ds {!! (Request::is('sellerpanel/my-review') ? 'active' : '') !!}" {!! (Request::is('sellerpanel/my-review') ? ' id="active"' : '') !!}><a href="{!! route('seller.review') !!}"> <i aria-hidden="true" class="fa fa-star"></i> Rating & Reviews</a></li>

<li class="dis-none-ds {!! (Request::is('sellerpanel/orders') ? 'active' : '') !!}" {!! (Request::is('sellerpanel/orders') || Request::is('sellerpanel/orders/*') || Request::is('sellerpanel/orders-inquiry/*') || Request::is('sellerpanel/orders/show/*') ? ' id="active"' : '') !!}> <a href="#orderss" data-toggle="collapse"><i class="fa fa-list-ol" aria-hidden="true"></i>
				Orders</a>
				<ul id="orderss" class="category-level-2 dropdown-menu-tree panel-collapse collapse{!! (Request::is('sellerpanel/orders') || Request::is('sellerpanel/orders/*')  || Request::is('sellerpanel/orders/show/*')? 'in' : '') !!}">
				
					<li {!! ( Request::is('sellerpanel/orders/all-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','all-orders') }}">All Orders</a>
					</li>
					
					<li {!! ( Request::is('sellerpanel/orders/pending-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','pending-orders') }}">Pending Orders</a>
					</li>
					
					<li {!! ( Request::is('sellerpanel/orders/completed-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','completed-orders') }}">Completed Orders</a>
					</li>
					
					<li {!! ( Request::is('sellerpanel/orders/failed-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','failed-orders') }}">Failed Orders</a>
					</li>
					
					
					
				</ul>
			</li>
			

<li class="dis-none-ds {!! (Request::is('sellerpanel/report') ? 'active' : '') !!}" {!! (Request::is('sellerpanel/report') || Request::is('sellerpanel/report/*') || Request::is('sellerpanel/report/show/*') ? ' id="active"' : '') !!}> <a href="#reportss" data-toggle="collapse"><i class="fa fa-file" aria-hidden="true"></i>
				Report</a>
				<ul id="reportss" class="category-level-2 dropdown-menu-tree panel-collapse collapse{!! (Request::is('sellerpanel/orders') || Request::is('sellerpanel/report/*')  || Request::is('sellerpanel/report/show/*')? 'in' : '') !!}">
				
					<li {!! ( Request::is('sellerpanel/report/sales') || Request::is('sellerpanel/report/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.report.sales') }}">Sales Report</a>
					</li>
					
					<li {!! ( Request::is('sellerpanel/report/inventory') || Request::is('sellerpanel/inventory/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.report.inventory') }}">Inventory Report</a>
					</li>
					
					
					
				</ul>
			</li>





			<li class="dis-none-ds {!! (Request::is('sellerpanel/company-profile') ? 'active' : '') !!}" {!! (Request::is('sellerpanel/profile') || Request::is('sellerpanel/company-profile')  || Request::is('sellerpanel/account-setting') ||Request::is('sellerpanel/bank-setting') ? ' id="active"' : '') !!}> <a href="#dasmenu2s" data-toggle="collapse"><i class="fa fa-cogs" aria-hidden="true"></i>
				GENERAL SETTING</a>
				<ul id="dasmenu2s" class="category-level-2 dropdown-menu-tree panel-collapse collapse{!! (Request::is('sellerpanel/profile') || Request::is('sellerpanel/company-profile') || Request::is('sellerpanel/account-setting') || Request::is('sellerpanel/bank-setting') ? 'in' : '') !!}">
					<li {!! (Request::is('sellerpanel/company-profile') ? 'class="active" id="active"' : '') !!}><a href="{{ route('seller.company') }}">Company Profile Settings</a></li>
					<li {!! (Request::is('sellerpanel/profile') ? 'class="active" id="active"' : '') !!}><a href="{{ route('seller.profile') }}">Profile Settings</a></li>
					<li {!! (Request::is('sellerpanel/account-setting') ? 'class="active" id="active"' : '') !!}><a href="{{ route('seller.accountsetting') }}">Account Settings </a></li>
					<li {!! (Request::is('sellerpanel/bank-setting') ? 'class="active" id="active"' : '') !!}><a href="{{ route('seller.banksetting') }}">Bank Account Settings </a></li>
				</ul>
			</li>
			<li class="dis-none-ds"><a href="{{ route('seller_logout') }}"> <i aria-hidden="true" class="fa fa-sign-out"></i> Logout</a></li>
            
          <!--  <li><a href="{{ route('seller_logout') }}"><i class="fa fa-sign-out" aria-hidden="true" style="border:none;"></i>
</a></li> -->
          </ul>


 <div class="col-sm-5">
	 
   
          
          
          </div>
        </div>
      </div>
    </nav>
                 <div id="modal-regular" class="modal fade" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div style="text-align:center;"> <img style="float:center;" src="" alt="" class="loading"></div>
	  </div>
	</div>
</div>
 <input class="serach_select" type="hidden" name="serach_select"  style="display:none">
 
 
 
 <div id="modal-large" class="modal fade" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div style="text-align:center;"> <img style="float:center;" src="" alt="" class="loading"></div>
	  </div>
	</div>
</div>



<!-- Content -->
    @yield('content')
<!-- Footer Section Start -->



  <script type="text/javascript" src="{{ asset('assets/default/lib/jquery/jquery-1.11.2.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/default/lib/bootstrap/js/bootstrap.min.js') }}"></script>
  	<script type="text/javascript" src="{{ asset('assets/default/js/formClass.js') }}"></script>
  	<script type="text/javascript" src="{{asset('assets/admin/js/formClass.js')}}"></script>

	<script type="text/javascript" src="{{ asset('assets/default/js/jquery.form.js') }}"></script>
	<script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/default/js/seller.js') }}" type="text/javascript"></script>

<script>
	 var seller_noti_url =   "{{ route('seller.noti-get') }}";
	 var seller_anc_url =  "{{ route('seller.anc-get') }}";
</script>

    <script>
    $(function () {

                  $('body').on('hidden.bs.modal', '.modal', function () {
                       $(this).removeData('bs.modal');
                 });
            });
    </script>


<script type="text/javascript">
$(document).ready(function () {
    $(".btn-select").each(function (e) {
        var value = $(this).find("ul li.selected").html();
        if (value != undefined) {
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value").html(value);
        }
    });
});

$(document).on('click', '.btn-select', function (e) {
    e.preventDefault();
    var ul = $(this).find("ul");
    if ($(this).hasClass("active")) {
        if (ul.find("li").is(e.target)) {
            var target = $(e.target);
            target.addClass("selected").siblings().removeClass("selected");
            var value = target.html();
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value").html(value);
        }
        ul.hide();
        $(this).removeClass("active");
    }
    else {
        $('.btn-select').not(this).each(function () {
            $(this).removeClass("active").find("ul").hide();
        });
        ul.slideDown(300);
        $(this).addClass("active");
    }
});

$(document).on('click', function (e) {
    var target = $(e.target).closest(".btn-select");
    if (!target.length) {
        $(".btn-select").removeClass("active").find("ul").hide();
    }
});

</script>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

   <!-- begin page level js -->
    @yield('footer_scripts')
    <!-- end page level js -->


</body>

</html>

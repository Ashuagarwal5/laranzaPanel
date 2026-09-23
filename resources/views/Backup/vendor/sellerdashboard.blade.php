@extends('vendor/header')

{{-- Page title --}}
@section('title')
Vendor Dashboard
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

    <div class="container-fluid">
      <div class="row">
         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Welcome to @if(isset($sellerdetails->company_name)){{ $sellerdetails->company_name	}} @endif</h1>

<div class="panel">
<div class="panel-heading"><h2 class="pull-left">Seller Panel – Dashboard</h2>

          <div class="clr">
            @include('notifications')
          </div>
</div>
<div class="panel-body">
<div  class="account clearfix ">
<div class="panel">
<div class="panel-heading"><h3>About Company </h3></div>
 <div class="col-sm-3 title row image-containor">

              <div class="pro-pic text-center">
				  <center>
					  <form class="file_upload" id="file_upload" method="POST" action="{{route('sellerdashboard')}}" enctype="multipart/form-data" id="">
					    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
					  <div class="form-group fileinput fileinput-new" data-provides="fileinput">
								
								@if(!empty($sellerdetails->company_logo))
								<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
								<img alt="Comapny Logo"  class="img-responsive text-center" src="{{ URL::to(App\Helpers\Thumbnail::image("/seller/$sellerdetails->user_id/$sellerdetails->company_logo","200","150","ff=ffffff"))}}" id="comp_logo"><br>
								</div>
								@else
								

									<div class="fileinput-preview thumbnail" id="preview" data-trigger="fileinput" style="width: 200px; height: 150px;">
																		<img alt="Comapny Logo"  class="img-responsive text-center" src="{{asset('assets/default/sellerlanding/img/no-image.jpg')}}" id="comp_logo"><br>
									</div>	
								@endif
								
								<div>
<!--
								<span class="btn btn-default btn-file">
								<span class="fileinput-new">Change Company Logo</span>
								<span class="fileinput-exists">Change Company Logo</span>
								<input type="file" name="company_logo" onchange="filechange()" class="company_logo" id="company_logo" @if(isset($sellerdetails->company_logo)) @else required @endif>
						
								</span>	
-->
								</div>
								
							</div> 
                </center>


              </div>
            </div>
   <section class="profile-info col-sm-9">
              <ul class="clearfix">
                <li><span class="s-left1 col-sm-3"> Company Name :</span><span class="s-right col-sm-8"> @if(isset($sellerdetails->company_name)){{ $sellerdetails->company_name	}} @endif</span>
                  <section class="clearfix"></section>
                </li>
                <li><span class="s-left1 col-sm-3">Company Address :</span><span class="s-right col-sm-8">{{ $sellerdetails->address	}} ,{{ $sellerdetails->city	}}</span>
                  <section class="clr"></section>
                </li>
				<li><span class="s-left1 col-sm-3">Account Status :</span><span class="s-right col-sm-8"> {{ $sellerdetails->status	}}</span>
				<section class="clearfix"></section>
				</li>
				

              </ul>
              <div class="my-bts clearfix"><a class="btn btn-success btn-ctm" href="{{ route('seller.company') }}"><i class="fa fa-edit"></i> Edit Company Profile</a>  </div>
            </section>
   <div class="clr"></div>
            </div>
            
  <div class="row">          
 <div class="col-sm-6">            
<div class="panel">

   <section class="profile-info">
	   <div class="panel-heading"><h3>About You </h3></div>
              <ul class="clearfix">
                <li><span class="s-left1 col-sm-3"> Name:</span><span class="s-right col-sm-8"> {{ $userdetail->first_name}} &nbsp; {{ $userdetail->last_name}}</span>
                  <section class="clearfix"></section>
                </li>
                <li><span class="s-left1 col-sm-3">Email Address:</span><span class="s-right col-sm-8">{{ $userdetail->email}}</span>
                  <section class="clearfix"></section>
                </li>
                <li><span class="s-left1 col-sm-3">Position:</span><span class="s-right col-sm-8"> {{ $sellerdetails->designation}}</span>
                  <section class="clearfix"></section>
                </li>
                <li><span class="s-left1 col-sm-3"> Mobile No.:</span><span class="s-right col-sm-8">{{ $userdetail->mobileno}}</span>
                  <section class="clearfix"></section>
                </li>

              </ul>
              <div class="my-bts clearfix"><a class="btn btn-success btn-ctm" href="{{ route('seller.profile') }}"><i class="fa fa-edit"></i> Edit Profile</a>  <a class="btn btn-success btn-ctm" href="{{ route('seller.accountsetting') }}"><i class="fa fa-edit"></i> Change Password</a>  </div>
            </section>
       <div class="clr"></div>
           </div>  
</div>           
           
           
           
<div class="col-sm-6">                 
    <div class="panel">        
   <section class="profile-info">
	   <div class="panel-heading"><h3>Store Info </h3></div>
              <ul class="clearfix">
                <li><span class="s-left1 col-sm-3"> Store Name:</span><span class="s-right col-sm-8"> @if(isset($sellerdetails->shop_name)){{ $sellerdetails->shop_name	}} @endif</span>
                  <section class="clearfix"></section>
                </li>
                <li><span class="s-left1 col-sm-3"> Store Location:</span><span class="s-right col-sm-8">{{ $sellerdetails->location_on_map? $sellerdetails->location_on_map : 'N/A' }}</span>
                  <section class="clearfix"></section>
                </li>
                <li><span class="s-left1 col-sm-3">Store Category:</span><span class="s-right col-sm-8"> {{ $sellerdetails->category_name}}</span>
                  <section class="clearfix"></section>
                </li>
                 <li><span class="s-left1 col-sm-3">Store Phone Number:</span><span class="s-right col-sm-8"> {{ $sellerdetails->store_phone_number? $sellerdetails->store_phone_number : 'N/A'}}</span>
                  <section class="clearfix"></section>
                </li>
                

              </ul>
              <div class="my-bts clearfix"><a class="btn btn-success btn-ctm" href="{{ route('seller.company') }}"><i class="fa fa-edit"></i> Edit Store Info</a></div>
            </section>          
       
   <div class="clr"></div>
           </div>
 
 </div>
 </div>

           
                     


 <div class="row">
<div class="col-sm-12">
<div class="panel">
<div class="panel-heading"><h3>Statistics </h3></div>

<table class="table table-editt table-striped">
	<thead >
		<tr>
			<th>Particular</th>
			<th>This Week</th>
			<th>This Month</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Products Added :</td>
			<td>{{$wnoOfProduct}}</td>
			<td>{{$current_month_product}}</td>
			<td>{{$noOfProduct}}</td>
		</tr>	
		<tr>
			
			<td>Item Ordered :</td>
			<td>{{$wnoOfOrder}}</td>
			<td>{{$monthoOfOrder}}</td>
			<td>{{$noOfOrder}}</td>
		</tr>	
		<tr>	
			
			<td>Order Amount</td>
			<td>{{config('constants.frontend.currency')}} {{$wtotalOrder}}</td>
			<td>{{config('constants.frontend.currency')}} {{$monthtotalOrder}}</td>
			<td>{{config('constants.frontend.currency')}} {{$totalOrder}}</td>
		</tr>	
	</tbody>
</table>


   <div class="clr"></div>
            </div>
     </div>
</div>


<div class="clr"></div>
</div>
</div>
</div>
        </div>
      </div>
    </div>



    <!-- //Container End -->
@stop

{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->
   
    <script src="{{ asset('assets/default/js/jquery.canvasjs.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/default/js/canvasjs.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
var tabData = JSON.parse('{!!$sale_value!!}');

</script>
<script src="{{ asset('assets/default/js/columnChart.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/default/js/seller.js') }}" type="text/javascript"></script>




    <!--page level js ends-->

@stop


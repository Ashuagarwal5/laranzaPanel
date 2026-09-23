@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
View Seller Details
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link href="{{ asset('assets/vendors/toastr/css/toastr.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/x-editable/bootstrap-editable.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/admin/css/user_profile.css') }}" rel="stylesheet"/>

@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <!--section starts-->
        <h1>Seller Profile</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#">Sellers</a>
            </li>
            <li class="active">Seller Profile</li>
        </ol>
    </section>
    <!--section ends-->


   <section class="content">
		<div class="row">
            <div class="col-lg-12">
				<ul class="nav  nav-tabs" style="background:#fff;">
                    <li class="active">
                        <a href="#tab1"  data-toggle="tab">
                            <i class="fa fa-building-o" data-name="user" data-size="16" data-c="#000" data-hc="#000" data-loop="true"></i>
                            Company Profile</a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">
                            <i class="fa fa-user" data-name="user" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            Seller Profile</a>
                    </li>
                    
                    <li>
                        <a href="#tab5" data-toggle="tab">
                            <i class="fa fa-shopping-cart" data-name="user" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            Order History</a>
                    </li>

                     <li>
                        <a href="#tab3" data-toggle="tab">
                            <i class="fa fa-key" data-name="key" data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                            Change Password</a>
                    </li>
  
                    <li>
                        <a href="#tab4" data-toggle="tab">
                            <i class="fa fa-area-chart"  data-size="16" data-loop="true" data-c="#000" data-hc="#000"></i>
                           Statistics</a>
                    </li>
                    
                    <button type="button" style="float:right; margin: 5px;" class="btn btn-danger"  id="backbtn" data-url="{{route('sellers')}}"> <span style="height:37px;" >
							<i class="glyphicon glyphicon-chevron-left"></i></span>
						Back
					</button>

                </ul>
                
				<div  class="tab-content mar-top">
				
				@include('admin.seller.company')
				@include('admin/seller/seller')
				@include('admin/seller/orderhistory')
				@include('admin/seller/password')
				@include('admin/seller/statistics')
				
				</div>
                
			</div>
		</div>
   </section>



@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- Bootstrap WYSIHTML5 -->
 
<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}" type="text/javascript"></script>

<script  src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
   <script src="{{ asset('assets/vendors/toastr/js/toastr.min.js') }}" ></script>
   
   <script src="{{ asset('assets/admin/js/seller.js') }}" ></script>
  
     
    <script type="text/javascript">
		
		
		var seller_status_url ='{!! route('seller.aprv',$seller->slug) !!}';
		
		
        $(document).ready(function () {
          
            
            

            
  $('#change-password').on('submit',function(e){
   var error=""; 
    e.preventDefault(e);

        $.ajax({

        type:"POST",
        url: '{{ route('seller.pass', $seller->slug) }}',
        data:$(this).serialize(),
        dataType: 'json',
        beforeSend:function(){
			
			$('.formmessage').remove();
			
			}
        ,
        success: function(response){
            
           
            if(response.success==true)
		           	{
						
					 $( '#change-password' ).each(function(){
			this.reset();
			});	
						toastr[response.status]("Password Change Suucessfully", "Notifications");
						
						
					}
            
                               
                               
           },error: function(data){
			    
			var data = data.responseJSON;
                       
                       
                       $.each(data, function( key, value ) {
                               
                               
                      console.log(key + " => " + value); // view in console for error messages
                      var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';
                      $('input[name="' + key + '"], select[name="' + key + '"]').addClass('inputTxtError').after(msg);
                               
                         
                                            
                      
                 });
                 
             
                       
          }
       
    });
 });      
            
            
            
            
            
        });
        
        
        
        
        
        
        
    </script>
@stop

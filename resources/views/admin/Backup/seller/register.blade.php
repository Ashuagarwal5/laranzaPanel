@extends('admin/layouts/default')
@section('title')
   Seller Manager
@stop
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
<style>
 #map {
        height: 300px;
      }
      /* Optional: Makes the sample page fill the window. */
  html, body {
	height: 100%;
	margin: 0;
	padding: 0;
  }
  #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 50%;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }


</style>
@stop
@section('content')
    <section class="content-header">
        <h1>Add Seller</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Admin</li>
            <li class="active">Add Seller</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="pen" data-size="20" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Add Seller
                        </h3>
                                <span class="pull-right ">
                                  <button class="btn btn-danger" type="button" id="backbtn" data-url='{{ URL::previous()}}'>

                                                <i class="glyphicon glyphicon-chevron-left"></i>

                                            Back
                                        </button>
                                </span>
                    </div>

                    <div class="panel-body">
                        
                     <form id="seller-reg-form" class="form-horizontal c-register colum-manage ajaxformclass" method="post" action="{{ route('admin.seller.store') }}">
	   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
	   <div class="alert ajax_report alert-info alert-hide" role="alert" style="display:none; text-align: center;">
	<span class="close" >&times;</span>
	<span class="ajax_message"><strong>Please wait! </strong>Your action is in proccess...</span>
  </div>
<div class="basic">
	
	




 <div class="panel-heading"><h3>About Company</h3></div>
<div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company / Firm Name</label>
    <div class="col-sm-12">
      <input type="text" name="company_name" value="{!! old('company_name') !!}" class="form-control" placeholder="Company Name">
      {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!} 
    </div>
  </div></div>
 <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label"> Industry</label>
    <div class="col-sm-12">
     <select  name="category" class="form-control">
		 
   <option value="">Select Categoy</option>
		 @foreach(App\Category::getAllMainCat() as $key=>$val)
	   
		<option value="{{ $val->id}}" {{ old('category') == $val->id ?       'selected="selected"' : '' }}>{{ $val->category_name }}</option>
		
		@endforeach
     </select>
     {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
<div class="clr"></div>
 <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Town/Area/Landmark</label>
    <div class="col-sm-12">
    <input type="text" name="city" placeholder="Company City" class="form-control" value="{!! old('city') !!}">
     {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>

  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company City</label>
    <div class="col-sm-12">
    <select name="state" class="form-control">
      <option value="">Please Select</option>
        @foreach($state as $key=>$val)
	   
		<option value="{{ $val->state_id}}" {{ old('state') == $val->state_id ?       'selected="selected"' : '' }}>{{ $val->st_name }}</option>
		
		@endforeach

     </select>
     {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  <div class="clr"></div>
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Telephone</label>
    <div class="col-sm-12">
      <input type="text" name="company_telephone" value="{!! old('company_telephone') !!}" class="form-control" placeholder="Company Telephone">
  {!! $errors->first('company_telephone', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Zip Code</label>
    <div class="col-sm-12">
    <input type="text" name="postcode"  value="{!! old('postcode') !!}" placeholder="Post Code" class="form-control">
     {!! $errors->first('postcode', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  <div class="clr"></div>
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Address </label>
    <div class="col-sm-12">
      <textarea name="address" class="form-control" rows="3">{!! old('address') !!}</textarea>
      {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>

<div class="clr"></div>
 
  


<div class="" id="contact_info" >
 <div class="panel-heading"><h3>About Contact Person</h3></div>


<div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">First Name</label>
    <div class="col-sm-12">
      <input type="text" name="first_name"  value="@if(isset($userinfo->first_name)){{$userinfo->first_name}}@endif" class="form-control" placeholder="First Name">
   {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
    </div>
     
  </div>
  
  </div>
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Last Name</label>
    <div class="col-sm-12">
      <input type="text" name="last_name" value="@if(isset($userinfo->last_name)){{$userinfo->last_name}}@endif" class="form-control" placeholder="Last Name">
  {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
  
   </div>
  </div></div>
  <div class="clr"></div>
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Mobile Number</label>
    <div class="col-sm-12">
      <input type="text" name="mobileno"  value="@if(isset($userinfo->mobileno)){{$userinfo->mobileno}}@endif"class="form-control" placeholder="Mobile Number">
       {!! $errors->first('mobileno', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Email</label>
   <div class="col-sm-12">
      <input type="text" name="email"  class="form-control" placeholder="Email"  value="@if(isset($userinfo->email)){{$userinfo->email}}@endif" >
       {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Password</label>
    <div class="col-sm-12">
      <input type="password" class="form-control" name="password" placeholder="Enter Password">
      {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Confirm Password</label>
    <div class="col-sm-12">
      <input type="password" class="form-control" name="password_confirm" placeholder="Enter Confirm Password">
       {!! $errors->first('password_confirm', '<span class="help-block">:message</span>') !!}
    </div>

  </div></div>
  
  
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Position</label>
   <div class="col-sm-12">
   <select name="position" class="form-control">
   <option value=""> Select Position</option>
			<option value="Owner"> Owner</option>
			<option value="Director">Director</option>
			<option value="MD">MD</option>
			<option value="CEO">CEO</option>
			<option value="Manager">Manager</option>
			<option value="Co-Founder">Co-Founder</option>
			<option value="Founder">Founder</option>
			<option value="Employee">Employee</option>
			<option value="Other">Other</option>
   </select>
    
    </div>
  </div></div>
   <div class="clr"></div>
  <div class="panel-heading"><h3>Store Detail</h3></div> 
  <div class="col-sm-6"> 
	   <div class="form-group">
    <label  class="col-sm-12 control-label">Store Name</label>
   <div class="col-sm-12">
      <input type="text" name="store_name"  class="form-control" placeholder="Store Name"  value="" ><br>
<!--
      <small class="sm-textt"> It can a text with length from 6 to 25 characters.</small>
-->
    

    </div>
    <label  class="col-sm-12 control-label">Store Phone Number</label>
   <div class="col-sm-12">
      <input type="text" name="store_phone_number"  class="form-control" placeholder="Store Phone Number"  value="" ><br>
    </div>
    
  </div></div>
  
	 <div class="col-sm-6"> 
	   <div class="form-group">
    <label  class="col-sm-12 control-label">Store Logo</label>
   <div class="col-sm-12">
      <input accept="image/*" type="file" name="shop_logo"  class="form-control" >
    

    </div>
  </div></div>
  			
						
  
     <div class="clr"></div>
  
  <div class="col-sm-12">  
	 
    <label  class="col-sm-12 control-label">Store Location on Google Map</label>
    <div class="col-sm-12">
		<input id="pac-input" class="form-control" type="text" name="location_on_map"  placeholder="Find Your Store On Google Map"  value="" >	
		<input id="latitude" type="hidden" name="latitude">	
		<input id="longitude" type="hidden" name="longitude">	
	</div>
   <div class="col-sm-12">
	  <div id="map"></div>
    </div>
 
  </div>
     
    
  
  <div class="clr"></div>
 
  <div class="clr"></div>
  </div>



 
  
   <div class="clr"></div>
  

  <div class="clr"></div>
 <div class="col-sm-6" id="submit_btn">

  <div class="form-group">
    <div class=" col-sm-10">
     <button style="text-align: center;
margin-left: 415px;
margin-top: 22px;" type="submit" >  <a class="btn btn-pink btn-lg btn-sign">Submit</a></button>
    </div>
  </div>

  </div>
</form>


        <div class="clr"></div>
        </div>
   
                        
					</div>
			</div>
        </div>
        <!--row end-->
        </div>
    </section>
@stop
@section('footer_scripts')
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script>
	$('#backbtn').click(function(){
	var url=$(this).attr('data-url');

	window.location.href=url;

});
$(document).on("submit", ".ajax_form", function(event) {
      var posturl = $(this).attr('action');
      var callbackFunction = $(this).attr('data-callback_function');
      if (callbackFunction) {
          if (callbackForm() == false) {
              return false;
          }
      }
      var formid = '#' + $(this).attr('id');
      
      $(this).ajaxSubmit({
          url: posturl,
          dataType: 'json',
          type: "POST",
          beforeSend: function() {
              $(".submit").attr("disabled", 'disabled');
              $('.formmessage').hide();
              $('#wait-div').show();
          },
          success: function(response) {
              $(".submit").removeAttr("disabled", 'disabled');
             $(formid).find('.form-group').removeClass('has-error');
             toastr[response.msgType](response.msg, response.msgHead); 
             
                  $(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
                  if (response.status == "success") {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
                  } else {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
						 $.each(response.errorArray, function( key, value ) {
						  console.log(key + " => " + value);
						  var msg = '<label class="error formmessage" for="'+key+'"  style="color:ef6f6c">'+value+'</label>';
						   
						   $(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').closest('.form-group').addClass('has-error');
						   
						   $(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);
						  });
                  
                  
                 
              }
              if (response.slideToTop){
                  //$('html, body').animate({scrollTop: 0 }, 'slow');
                  $('html, body').animate({
					scrollTop: $(formid).offset().top-290
					},800);
			  }
              if (response.url)
                  window.location.href = response.url;
              if (response.selfReload)
                  window.location.reload();
              if (response.status == 'success') {
                  //$(formid)[0].reset();
              }
              if (response.redirect == 'yes') {
                  window.location.href = response.redirectUrl;
              }
          },
          error: function(response) {
              var data = response.responseJSON;
              $(".submit").removeAttr("disabled", 'disabled');
             
          }
      });
      return false;
  });


</script>

<script>
     
      function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -33.8688, lng: 151.2195},
          zoom: 13,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          
          places.forEach(function(place) {
			  
			  //==== Lat Lng ===//
			
			$("#latitude").val(place.geometry.location.lat());
			$("#longitude").val(place.geometry.location.lng());
			  
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYTUE6jXvJR0gJC3BY3lGrYvXPNviZ_rg&libraries=places&callback=initAutocomplete"
         async defer></script>





@stop


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
        <h1>Edit Seller</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Admin</li>
            <li class="active">Edit Seller</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="pen" data-size="20" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Edit Seller
                        </h3>
                                <span class="pull-right ">
                                  <button class="btn btn-danger" type="button" id="backbtn" data-url='{{ URL::previous()}}'>

									<i class="glyphicon glyphicon-chevron-left"></i>

									Back
								</button>
                                </span>
                    </div>

                    <div class="panel-body">
                        
                     <form id="edit-seller-form" class="form-horizontal c-register colum-manage ajaxformclass" method="post" action="@if($sellerData->user_id){{ route('admin.seller.update',$sellerData->user_id) }}@endif">
					   
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
      <input type="text" name="company_name" value="@if($sellerData->company_name) {{$sellerData->company_name}} @endif" class="form-control" placeholder="Company Name">
      {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!} 
    </div>
  </div></div>
 <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label"> Industry</label>
    <div class="col-sm-12">
     <select class="form-control" disabled="">
		 
		 <option>{{App\Category::getCatNameById($sellerData->category)}}</option>
   
     </select>
    
    </div>
  </div></div>
<div class="clr"></div>
 <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Town/Area/Landmark</label>
    <div class="col-sm-12">
    <input type="text" name="city" placeholder="Company Town/Area/Landmark" class="form-control" value="@if($sellerData->city) {{$sellerData->city}} @endif">
     {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>

  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company City</label>
    <div class="col-sm-12">
    <select name="state" class="form-control">
      <option value="">Please Select</option>
        @foreach($state as $key=>$val)
	   
		<option @if($sellerData->state && $sellerData->state == $val->state_id) selected="" @endif value="{{ $val->state_id}}">{{ $val->st_name }}</option>
		
		@endforeach

     </select>
     {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  <div class="clr"></div>
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Telephone</label>
    <div class="col-sm-12">
      <input type="text" name="company_telephone" value="@if($sellerData->company_telephone) {{$sellerData->company_telephone}} @endif" class="form-control" placeholder="Company Telephone">
  {!! $errors->first('company_telephone', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Zip Code</label>
    <div class="col-sm-12">
    <input type="text" name="postcode"  value="@if($sellerData->postcode) {{$sellerData->postcode}} @endif" placeholder="Post Code" class="form-control">
     {!! $errors->first('postcode', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
  <div class="clr"></div>
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company Address </label>
    <div class="col-sm-12">
      <textarea name="address" class="form-control" rows="3">@if($sellerData->address) {{$sellerData->address}} @endif</textarea>
      {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>

<div class="clr"></div>
 
  


<div class="" id="contact_info" >
 <div class="panel-heading"><h3>About Contact Person</h3></div>


<div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">First Name</label>
    <div class="col-sm-12">
      <input type="text" name="first_name"  value="@if($sellerData->first_name) {{$sellerData->first_name}} @endif" class="form-control" placeholder="First Name">
   {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
    </div>
     
  </div>
  
  </div>
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Last Name</label>
    <div class="col-sm-12">
      <input type="text" name="last_name" value="@if($sellerData->last_name) {{$sellerData->last_name}} @endif" class="form-control" placeholder="Last Name">
  {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
  
   </div>
  </div></div>
  <div class="clr"></div>
 
  
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Position</label>
   <div class="col-sm-12">
   <select name="position" class="form-control">
   <option value=""> Select Position</option>
			<option @if($sellerData->designation && $sellerData->designation == 'Owner') selected="" @endif value="Owner"> Owner</option>
			<option @if($sellerData->designation && $sellerData->designation == 'Director') selected="" @endif value="Director">Director</option>
			<option @if($sellerData->designation && $sellerData->designation == 'MD') selected="" @endif value="MD">MD</option>
			<option @if($sellerData->designation && $sellerData->designation == 'CEO') selected="" @endif value="CEO">CEO</option>
			<option @if($sellerData->designation && $sellerData->designation == 'Manager') selected="" @endif value="Manager">Manager</option>
			<option @if($sellerData->designation && $sellerData->designation == 'Co-Founder') selected="" @endif value="Co-Founder">Co-Founder</option>
			<option @if($sellerData->designation && $sellerData->designation == 'Founder') selected="" @endif value="Founder">Founder</option>
			<option @if($sellerData->designation && $sellerData->designation == 'Employee') selected="" @endif value="Employee">Employee</option>
			<option @if($sellerData->designation && $sellerData->designation == 'Other') selected="" @endif value="Other">Other</option>
   </select>
    
    </div>
  </div></div>
   <div class="clr"></div>
  <div class="panel-heading"><h3>Store Detail</h3></div> 
  
  <div class="col-sm-6"> 
	   <div class="form-group">
    <label  class="col-sm-12 control-label">Store Name</label>
   <div class="col-sm-12">
      <input type="text" name="store_name" class="form-control" placeholder="Store Name"  value="@if($sellerData->shop_name) {{$sellerData->shop_name}} @endif" ><br>
<!--
      <small class="sm-textt"> It can a text with length from 6 to 25 characters.</small>
-->
   
    </div>
    
    <label  class="col-sm-12 control-label">Store Phone Number</label>
   <div class="col-sm-12">
      <input type="text" name="store_phone_number"  class="form-control" placeholder="Store Phone Number"  value="@if($sellerData->store_phone_number) {{$sellerData->store_phone_number}} @endif" ><br>
    </div>
    
    
  </div></div>
  
  
  <div class="col-sm-6"> 
	   <div class="form-group">
    <label  class="col-sm-12 control-label">Store Logo</label>
   <div class="col-sm-12">
	   @if($sellerData->company_logo)
	   <img src="{{URL::to(App\Helpers\Thumbnail::image("seller/$sellerData->user_id/$sellerData->company_logo","160","160","ff=ffffff")) }}">
	   @endif
      <input accept="image/*" type="file" name="shop_logo"  class="form-control" >
    

    </div>
  </div></div>
  
				
						
  
     <div class="clr"></div>
     
  <div class="col-sm-12">  
	 
    <label  class="col-sm-12 control-label">Store Location on Google Map</label>
    <div class="col-sm-12">
		<input id="pac-input" class="form-control" type="text" name="location_on_map"  placeholder="Find Your Store On Google Map"  value="@if($sellerData->location_on_map) {{$sellerData->location_on_map}} @endif" >	
		<input id="latitude" type="hidden" name="latitude" value="@if($sellerData->latitude) {{$sellerData->latitude}} @endif">	
		<input id="longitude" type="hidden" name="longitude" value="@if($sellerData->longitude) {{$sellerData->longitude}} @endif">	
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
 <div class="col-sm-6" id="submit_btn" style="margin-top:10px;">

  <div class="form-group">
    <div class=" col-sm-10">
     <button style="text-align: center;
margin-left: 415px;
margin-top: 22px;"  type="submit" class="submit" >  <a class="btn btn-pink btn-lg btn-sign">Submit</a></button>
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
</script>

<script>
	
      function initAutocomplete() {
		  
		var lats = "{{$sellerData->latitude}}";
		var lngs = "{{$sellerData->longitude}}";

		lats = parseFloat(lats);
		lngs = parseFloat(lngs);
		
		var myLatLng = {lat: lats, lng: lngs};

		  
        var map = new google.maps.Map(document.getElementById('map'), {

			center: myLatLng,
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
        
        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
        });
        
        markers.push(marker);

        
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
			
			
			
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }
          
          
           var marker = [];

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
			
			
			//~ var marker = new google.maps.Marker({
			  //~ position: {lat : place.geometry.location.lat() , lng : place.geometry.location.lng()},
			  //~ map: map,
			//~ });
			  
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


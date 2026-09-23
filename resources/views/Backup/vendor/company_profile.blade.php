@extends('vendor/header')
{{-- Page title --}}
@section('title')
CorporateCompanyProfile
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/smart-forms.css') }}" />
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">
<style>
 #map {
        height: 300px;
      }
      /* Optional: Makes the sample page fill the window. */
      /*
  html, body {
	height: 100%;
	margin: 0;
	padding: 0;
  }
   */
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
{{-- content --}}
@section('content')
	<!-- //Container Start -->
<div class="container-fluid">
      <div class="row">
        @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Profile Settings</h1>

@include('notifications')



<div class="panel first">
<div class="panel-heading"><h2 class="pull-left">Company Profile</h2>
          <div class="clr"></div>
</div>
<div class="panel-body">
<div  class="account clearfix ">
	
	
<form class="form-horizontal smart-forms seller-company" action="{!! route('seller.company') !!}" method="post" enctype="multipart/form-data">


   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
      <div class="panel">
           <div class="panel-heading"><h3>About Company</h3><small>Please fill basic information about the company</small></div>
            <fieldset id="account">

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Company/Firm Name :</label>
                <div class="col-sm-10">
                  <input name="company_name" value="{{ $sellerdetails->company_name	}}" placeholder="Company Name " id="company_name" class="form-control" type="text">
                  {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname"> Category/Industry :</label>
                <div class="col-sm-10">
                  <input value="{{ $sellerdetails->category_name	}}" placeholder="Category/Industry" id="" class="form-control" type="text" readonly>
                  {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
             
            
             
             
             
              
               <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Company Town/Area/Landmark :</label>
                <div class="col-sm-10">
                  <input name="city" value="{{ $sellerdetails->city	}}" placeholder="Company City " id="city" class="form-control" type="text">
                  {!! $errors->first('city', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
              
                <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Company City :</label>
                <div class="col-sm-10">
                  <select name="state" class="form-control">
								  <option value="">Pelese Select</option>
									@foreach($state as $key=>$val)
								   
									<option value="{{ $val->state_id}}" {{ $sellerdetails->state == $val->state_id ?       'selected="selected"' : '' }}>{{ $val->st_name }}</option>
									
									@endforeach

								 </select>
                  {!! $errors->first('state', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
                <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Company Telephone :</label>
                <div class="col-sm-10">
                  <input name="company_telephone" value="{{ $sellerdetails->company_telephone	}}" placeholder="Company Telephone " id="company_telephone" class="form-control" type="text">
                  {!! $errors->first('company_telephone', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
                  <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Company Zip Code :</label>
                <div class="col-sm-10">
                  <input name="postcode" value="{{ $sellerdetails->postcode	}}" placeholder="Company Post Code " id="postcode" class="form-control" type="text">
                  {!! $errors->first('postcode', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
              
               <div class="form-group required">
                <label class="col-sm-2 control-label" for="">Company Address :</label>
                <div class="col-sm-10">
                  <textarea name="address"  placeholder="Address" id="address" class="form-control" >{{ $sellerdetails->address	}}</textarea>
                {!! $errors->first('address', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
              
   
            


<div class="form-group">
                <div class=" col-sm-12 text-right">
                  <button class="btn btn-pink submitbtn" type="submit">Save Profile</button>
                </div>
                
                
             
             
            </fieldset>
            </div>

    
</div>




</form>


<div class="clr"></div>
</div>
</div>


<div class="panel third">
<div class="panel-heading"><h2 class="pull-left"> Contact Person Profile</h2>
          <div class="clr"></div>
</div>
<div class="panel-body">
<div  class="account clearfix ">
	
	
<form class="form-horizontal smart-forms seller-company" action="{!! route('seller.personal') !!}" method="post" enctype="multipart/form-data">


   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
      <div class="panel">
           <div class="panel-heading"><h3>About Contact Person</h3><small>Please fill basic information about the Person</small></div>
            <fieldset id="account">
				
				 <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">First Name :</label>
                <div class="col-sm-10">
                  <input name="first_name" value="{{ $sellerdetails->first_name	}}" placeholder="First Name " id="" class="form-control" type="text">
                  {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname"> Last Name :</label>
                <div class="col-sm-10">
                  <input name="last_name" value="{{ $sellerdetails->last_name	}}" placeholder="Last Name" id="" class="form-control" type="text" >
                  {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
             
             
             

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Mobile No. :</label>
                <div class="col-sm-10">
                  <input   value="@if(isset($sellerdetails->mobileno)){{$sellerdetails->mobileno}}@endif"class="form-control" placeholder="Mobile Number" class="form-control" readonly type="text">
                </div>
              </div>
              
             <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Email :</label>
                <div class="col-sm-10">
                  <input   value="@if(isset($sellerdetails->email)){{$sellerdetails->email}}@endif"class="form-control" placeholder="Email" class="form-control" readonly type="text">
                </div>
              </div>
             
            
             
               <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Position :</label>
                <div class="col-sm-10">
							<select name="designation"  name="designation" class="form-control">
							<option value=""> Select Designation</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'Owner') selected="" @endif value="Owner"> Owner</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'Director') selected="" @endif value="Director">Director</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'MD') selected="" @endif value="MD">MD</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'CEO') selected="" @endif value="CEO">CEO</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'Manager') selected="" @endif value="Manager">Manager</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'Co-Founder') selected="" @endif value="Co-Founder">Co-Founder</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'Founder') selected="" @endif value="Founder">Founder</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'Employee') selected="" @endif value="Employee">Employee</option>
							<option @if(isset($sellerdetails->designation) && $sellerdetails->designation== 'Other') selected="" @endif value="Other">Other</option>
							</select>
                </div>
              </div>
            
            


<div class="form-group">
                <div class=" col-sm-12 text-right">
                  <button class="btn btn-pink submitbtn" type="submit">Save Profile</button>
                </div>
                
                
             
             
            </fieldset>
            </div>

    
</div>




</form>


<div class="clr"></div>
</div>
</div>




       
       
     
       
       <div class="panel second">
<div class="panel-heading"><h2 class="pull-left">Store Profile</h2>
          <div class="clr"></div>
</div>


<div class="panel-body">
<div  class="account clearfix ">
	
	
<form class="form-horizontal smart-forms seller-company" action="{!! route('seller.store') !!}" method="post" enctype="multipart/form-data">


   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
      <div class="panel">
           <div class="panel-heading"><h3>Store Profile</h3><small>Please fill basic information about the store</small></div>
            <fieldset id="account">

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Store Name :</label>
                <div class="col-sm-10">
                  <input name="shop_name" value="{{ $sellerdetails->shop_name	}}" placeholder="Store Name " id="shop_name" class="form-control" type="text">
                  {!! $errors->first('shop_name', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
              
				<div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Store Phone Number :</label>
                <div class="col-sm-10">
                  <input name="store_phone_number" value="{{ $sellerdetails->store_phone_number	}}" placeholder="Store Phone Number " id="store_phone_number" class="form-control" type="text">
                  {!! $errors->first('store_phone_number', '<span class="help-block">:message</span>') !!} 
                </div>
              </div>
            
             
             
             
              
               <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Company Logo :</label>
                <div class="col-sm-10">
                        <label class="field prepend-icon file">
                            <span class="button btn-primary"> Upload Photo </span>
                			<input  type="file" onchange="document.getElementById('uploader2').value = this.value;" id="company_logo" name="company_logo" class="gui-file" {{ empty($sellerdetails->company_logo) ?  'required="required"' : ''}}>
                            <input readonly placeholder="no file selected" id="uploader2" class="gui-input" type="text">
                            <span class="field-icon"><i class="fa fa-upload"></i></span>
                        </label>
                          {!! $errors->first('company_logo', '<span class="help-block">:message</span>') !!}
                            @if(!empty($sellerdetails->company_logo))
                                        
                                       <img src="{{ URL::to(App\Helpers\Thumbnail::image("/seller/$sellerdetails->user_id/$sellerdetails->company_logo","300","300","ff=ffffff"))}}" height="100px" width="100px">
                        @endif    
                </div>
              </div>
              
              
         
              <div class="clr"></div>
              <div class="col-sm-12">  
             
             <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Store Location on Google Map :</label>
                <div class="col-sm-10">
					<input id="pac-input" class="form-control" type="text" name="location_on_map"  placeholder="Find Your Store On Google Map"  value="@if($sellerdetails->location_on_map) {{$sellerdetails->location_on_map}} @endif" >	
					<input id="latitude" type="hidden" name="latitude" value="@if($sellerdetails->latitude) {{$sellerdetails->latitude}} @endif">	
					<input id="longitude" type="hidden" name="longitude" value="@if($sellerdetails->longitude) {{$sellerdetails->longitude}} @endif">	
					<div id="map"></div>
                </div>
              </div>
             </div>
             
            


<div class="form-group">
                <div class=" col-sm-12 text-right">
                  <button class="btn btn-pink submitbtn" type="submit">Save Profile</button>
                </div>
                
                
             
             
            </fieldset>
            </div>

    
</div>




</form>


<div class="clr"></div>
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
 <script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/default/js/seller.js') }}" type="text/javascript"></script>

<script>

var company_url='{!! route('seller.company') !!}';

</script>
    <!--page level js ends-->
    
    
<script>
	
      function initAutocomplete() {
		  
		var lats = "{{$sellerdetails->latitude}}";
		var lngs = "{{$sellerdetails->longitude}}";

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

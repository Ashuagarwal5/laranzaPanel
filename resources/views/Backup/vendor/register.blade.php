@php ($siteSettingList=App\WebsiteSetting::getWebsiteSettingAdmin())
<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dolovery Vender Landing - Start Selling</title>
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

    <!--end of global css-->
    <!--page level css-->
	
	<style>
	#map {
        height: 300px;
      }
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
	
	
    @yield('header_styles')
    <!--end of page level css-->
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">

<div class="lp-element lp-pom-root" id="lp-pom-root">
<div xmlns="" id="lp-pom-root-color-overlay1"></div>

<div class="lp-positioned-content11">


<div class="columns-container account-bg">
  <div class="container" id="columns">
    <!-- breadcrumb -->
    <!--<div class="breadcrumb clearfix"> <a class="home" href="#" title="Return to Home">Home</a> <span class="navigation-pipe">&nbsp;</span> <span class="navigation_page">Change Password</span> </div>-->
    <!-- ./breadcrumb -->
    <!-- row -->
    <div class="ac-menu">
      <div class="column col-xs-12 col-sm-12" id="left_column" style="background:#f2f2f2;">
        <!-- block category -->
        <div class="account-menu">
        <h3 class="page-heading"><i class="fa fa-user"></i>Vender Account Registration </h3>

<ul id="wizardStatus">
   <li class="current"><span class="stap">1</span> Basic Information</li>
  <li> <span class="stap">2</span> Terms & Condition</li>
  <li><span class="stap">3</span> Success</li>

</ul>
@include('notifications')
 <form class="form-horizontal c-register colum-manage ajaxform" method="post" action="{{ URL::to('sellerpanel/registration') }}">
	   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
<div class="basic">
	
	




<div class="panel">
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
		 
   <option value="">All Categories</option>
		 @foreach(App\Category::getAllMainCat() as $key=>$val)
	   
		<option value="{{ $val->id}}" {{ old('category') == $val->id ?       'selected="selected"' : '' }}>{{ $val->category_name }}</option>
		
		@endforeach
     </select>
     {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>
<div class="clr"></div>
 <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company City</label>
    <div class="col-sm-12">
    <input type="text" name="city" placeholder="Company City" class="form-control" value="{!! old('city') !!}">
     {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
    </div>
  </div></div>

  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Company State</label>
    <div class="col-sm-12">
    <select name="state" class="form-control">
      <option value="">Pelese Select</option>
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
    <label  class="col-sm-12 control-label">Designation</label>
   <div class="col-sm-12">
   <select name="designation" class="form-control">
   <option value=""> Select Your Designation</option>
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
  <div class="panel-heading"><h3>Choose Your Store Name</h3></div> 
  <div class="col-sm-6">  <div class="form-group">
    <label  class="col-sm-12 control-label">Store Name</label>
   <div class="col-sm-12">
      <input type="text" name="store_name"  class="form-control" placeholder="Store Name"  value="" ><br>
      <small class="sm-textt"> It can a text with length from 6 to 15 characters, no special or special characters are allowed, i.e. thegrocery, grocerystore</small>
    

    </div>
  </div></div>
  
  
  
  <div class="clr"></div>
  
  <div class="col-sm-12">
    <label  class="col-sm-12 control-label">Store Location on Google Map</label>
    <div class="col-sm-12">
		<input id="pac-input" class="form-control" type="text" name="location_on_map"  placeholder="Find Your Store On Google Map"  value="" >	
		<input id="latitude" type="hidden" name="latitude" value="">	
		<input id="longitude" type="hidden" name="longitude" value="">	
	</div>
   <div class="col-sm-12">
	  <div id="map"></div>
    </div>
  </div>
  
  <div class="clr"></div>
  </div>



 
  
   <div class="clr"></div>
  </div>


  <div class="clr"></div>
 <div class="col-sm-6" id="submit_btn">

  <div class="form-group">
    <div class=" col-sm-10">
     <!-- <button type="submit" class="btn btn-pink btn-lg btn-sign">Register</button>-->
     <button type="submit" >  <a class="btn btn-pink btn-lg btn-sign">Submit</a></button>
    </div>
  </div>

  </div>
</form>


        <div class="clr"></div>
        </div>


      </div>

     <div class="clr"></div>
    </div>
    <!-- ./row-->
  <div class="clr"></div>
  </div>
  
</div>



</div>
		<script src="{{ asset('assets/default/lib/jquery/jquery-1.11.2.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/default/lib/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
		<script type="text/javascript" src="{{ asset('assets/default/js/formClass.js') }}"></script>
		<script type="text/javascript" src="{{ asset('assets/default/js/jquery.form.js') }}"></script>
		<script src="//ajax.googleapis.com/ajax/libs/webfont/1.4.7/webfont.js"></script>
</div>


<script>
	
      function initAutocomplete() {
		  
		var myLatLng = {lat: -33.8688, lng: 151.2195}; 

		  
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



</body>
</html>

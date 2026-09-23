@extends('admin/layouts/default')
@section('title')
    Branch Stores Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
<section class="content-header">
    <h1>
Branch Stores Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Dashboard
            </a>
        </li>
        <li>Branch Stores Manager</li>
        <li class="active">
          @if(isset($data))
	  Edit 
	  @else
		Create
		  @endif
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                           @if(isset($data))
						  Edit 
						  @else
							Create
							  @endif Branch Stores
                        </h3>
				   <div class="pull-right">
						<a href="{{ route('admin.branch_stores') }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>
                  </div>
             </div>
                    <div class="panel-body">
							
                        <form method="post" id="page-form" enctype="multipart/form-data">

							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

                          <div class="form-group has-success">
                                <label for="validate-text">Branch Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="branch_name" value="@if(isset($data->branch_name)){{$data->branch_name}}@else{{old('branch_name')}}@endif" id="validate-text" placeholder="Enter Branch Stories Title">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								<div class="has-error">
									  {!! $errors->first('branch_name', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>
                            
                            	<div class="form-group has-success">
                                <label for="validate-text">Branch Stores Image </label><br/>
                                @if(isset($data->branch_image))
                                <img src="{{URL::to(App\Helpers\Thumbnail::image("branch/$data->branch_image","400","170","ff=ffffff")) }}">
                                @endif
                                <div class="input-group">
                                    <input accept="image/*" type="file" class="form-control" name="branch_image" value="@if(isset($data->branch_image)){{$data->branch_image}}@endif" id="validate-text">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
                                <span>Recommended size = 1920 x 450 </span>
								<div class="has-error">
									  {!! $errors->first('branch_image', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>

                              <div class="form-group has-success">
                                <label for="validate-text">Branch State</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="state" value="@if(isset($data->state)){{$data->state}}@else{{old('state')}}@endif" id="validate-text" placeholder="Enter Branch State">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								<div class="has-error">
									  {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>  
                            
                              <div class="form-group has-success">
                                <label for="validate-text">Branch Country</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="country" value="@if(isset($data->country)){{$data->country}}@else{{old('country')}}@endif" id="validate-text" placeholder="Enter Branch Country">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								<div class="has-error">
									  {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>
  
                     							
                            <div class="form-group has-success">
                                <label for="validate-text">Branch City</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="city" value="@if(isset($data->city)){{$data->city}}@else{{old('city')}}@endif" id="validate-text" placeholder="Enter Branch City">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								<div class="has-error">
									  {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>
                     							                   							
                      <div class="form-group has-success">
							<label for="geocomplete">Full Address</label>
						 

							<div class="col-sm-12 input-group">
								<input id="geocomplete" type="text" class="form-control" name="address"
								value="@if(isset($data)){{$data->address}}@endif" placeholder="Type an address" />								
							</div>
							<div class="map_canvas" id="map"></div>
							
							
						  </div>
							
						<div class="form-group has-success">
							<label for="geocomplete">Latitude</label>
							<div class="col-sm-12 input-group">
								<input type="text" class="form-control" name="latitude"
							  value="@if(isset($data)){{$data->latitude}}@endif" id="latitude" placeholder="Enter Latitude" >
							</div>
						</div>		
							
						 <div class="form-group has-success">
							<label for="geocomplete">Longitude</label>
							<div class="col-sm-12 input-group">
								<input type="text" class="form-control" name="longitude" 
							   value="@if(isset($data)){{$data->longitude}}@endif" id="longitude" placeholder="Enter Longitude" >
							</div>
						</div>	
							
								
						 
							
                     							
							<div class="col-md-12 mar-10">
								<div class="col-xs-4 col-md-4"></div>
								<div class="col-xs-4 col-md-2">
                                   <button type="submit"  class="btn btn-primary btn-block btn-md submit">
										Save
									</button>
								</div>
								<div class="col-xs-4 col-md-2">
									<a class="btn btn-warning btn-block btn-md" href="{{ route('admin.branch_stores') }}">Cancel</a>
								</div>
                            </div>
                       </form>
                    
                    </div>
                </div>


        </div>
    </div>
    <!-- row-->
</section>
@stop
@section('footer_scripts')

<script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    <script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
    <script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
    <script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
    <script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>

 <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
    <script src="{{asset('assets/default/js/jquery.geocomplete.js')}}"></script>


<script>	
      $(function(){
		  	
	var options = {
	  map: ".map_canvas"
	};
	
        $("#geocomplete").geocomplete(options)
          .bind("geocode:result", function(event, result){
        //    console.log("Result: " + result.geometry.location.lat+"-------" + result.geometry.location.lat);
        //    console.log("Result: " + result.geometry.location.lat());
	var lat = result.geometry.location.lat();
	//alert(lat);
	var lng = result.geometry.location.lng();
       //alert(lng);
       
		$("#latitude").val(lat);
		$("#longitude").val(lng);

		var map = new google.maps.Map(document.getElementById('map'), {
			  center: {lat: lat, lng: lng},
			  zoom: 8
			});
		var marker = new google.maps.Marker({
			  draggable: false,
			  animation: google.maps.Animation.DROP,
			  position: {lat: lat, lng: lng},
			  map: map,
			});
		//alert("ok");
          })
          .bind("geocode:error", function(event, status){
            console.log("ERROR: " + status);
          })
          .bind("geocode:multiple", function(event, results){
            console.log("Multiple: " + results.length + " results found");
          });
        
        $("#find").click(function(){
          $("#geocomplete").trigger("geocode");
        });
        
        $("#examples a").click(function(){
          $("#geocomplete").val($(this).text()).trigger("geocode");
          return false;
        });
        
      });
 </script>
 
 <script>
$(document).ready(function(){

var longitude = "<?php if(isset($data)) echo $data['longitude'];else echo $lng;?>";
var latitude = "<?php if(isset($data)) echo $data['latitude'];else echo $lat;?>";
//alert("new");
//alert(longitude);
var longitude = parseFloat(longitude);
var latitude = parseFloat(latitude);
//alert(longitude);

var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: latitude, lng: longitude},
          zoom: 8
        });
var marker = new google.maps.Marker({
	  draggable: false,
          animation: google.maps.Animation.DROP,
          position: {lat: latitude, lng: longitude},
          map: map,
        });
});
</script>
   
    @stop

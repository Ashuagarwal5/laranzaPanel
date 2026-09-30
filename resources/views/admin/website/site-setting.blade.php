@extends('admin/layouts/default')
@section('title')
General Settings::CRM
@stop
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
<section class="content-header">
	<h1>Edit Site Setting</h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>Admin</li>
		<li class="active">Edit Site Setting</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">
						<i class="livicon" data-name="pen" data-size="20" data-c="#fff" data-hc="#fff" data-loop="true"></i>
						Edit Site Setting
					</h3>
					{{-- <span class="pull-right ">
						<button class="btn btn-danger" type="button" id="backbtn" data-url='{{ URL::previous()}}'>

							<i class="glyphicon glyphicon-chevron-left"></i>

							Back
						</button>
					</span> --}}
				</div>

				<div class="panel-body">

					<div id="rootwizard">


						<div class="tab-content">
							<div class="tab-pane active" id="tab1">

								<form id="basic" action="{{route('site-setting')}}" method="POST" class="form-horizontal ajax_form" enctype="multipart/form-data">
									<input type="hidden" name="_token" value="{{ csrf_token() }}" />
									<h2 class="hidden">&nbsp;</h2>

									<div class="form-group">
										<label for="site_name" class="col-sm-2 control-label">Site Name</label>
										<div class="col-sm-10">
											<input id="site_name" name="site_name" type="text"
											placeholder="Site Name" class="form-control"
											value="{{$data->site_name}}"/>
										</div>
									</div>

									<div class="form-group">
										<label for="contact_no" class="col-sm-2 control-label">Customer Helpline Number</label>
										<div class="col-sm-10">
											<input id="contact_no" name="contact_no" type="text"
											placeholder="Contact No " class="form-control"
											value="{{$data->contact_no}}"/>
										</div>
									</div>


									<div class="form-group">
										<label for="contact_no" class="col-sm-2 control-label">Contact Email</label>
										<div class="col-sm-10">
											<input id="contact_email" name="contact_email" type="text"
											placeholder="Contact Email " class="form-control"
											value="{{$data->contact_email}}"/>
										</div>
									</div>
									<div class="form-group">
										<label for="contact_no" class="col-sm-2 control-label">App Version</label>
										<div class="col-sm-10">
											<input id="app_version" name="app_version" type="text"
											placeholder="App Version " class="form-control"
											value="{{$data->app_version}}"/>
										</div>
									</div>
									

									{{-- <div class="form-group">
										<label for="contact_no" class="col-sm-2 control-label">Conversation Rate</label>
										<div class="col-sm-10">
											<input id="conversion_rate" name="conversion_rate" type="text"
											placeholder="Conversation Rate " class="form-control"
											value="{{$data->conversion_rate}}"/>
											<span style="color:red;"> Hint : 1 Lebanese pound equals = United States Dollar ?</span>
										</div>
									</div> --}}

									<div class="form-group">
										<label for="address" class="col-sm-2 control-label">Address</label>
										<div class="col-sm-10">
											<textarea name="address" class="form-control">{{$data->address}}</textarea>
										</div>
									</div>


									<div class="form-group">
										<label for="copyright_text" class="col-sm-2 control-label">Copyright Text</label>
										<div class="col-sm-10">
											<textarea name="copyright_text" class="form-control">{{$data->copyright_text}}</textarea>
										</div>
									</div>

									<div class="form-group">
										<label for="contact_no" class="col-sm-2 control-label">Minimum Required Point for Redeem</label>
										<div class="col-sm-10">
											<input id="min_req_points" name="min_req_points" type="text"
											placeholder="App Version " class="form-control"
											value="{{$data->min_req_points}}"/>
										</div>
									</div>
									
							<div class="form-group">
								<label for="signup_bonus" class="col-sm-2 control-label">Signup Bonus</label>
								<div class="col-sm-10">
									<input id="signup_bonus" name="signup_bonus" type="number"
									placeholder="Signup Bonus" class="form-control"
									value="{{$data->signup_bonus}}"/>
								</div>
							</div>
							<div class="form-group">
								<label for="signup_bonus" class="col-sm-2 control-label">1 Point Value In INR (Rs.)</label>
								<div class="col-sm-10">
									<input id="point_value" name="point_value" type="text"
									placeholder="1 Point Value in INR" class="form-control"
									value="{{$data->point_value}}"/>
								</div>
							</div>

							<!--						
							<div class="form-group">
							   <label for="signup_bonus" class="col-sm-2 control-label">Bonus Points on Target</label>
							   <div class="col-sm-10" style="background: #f1f1f1;border-radius: 6px;">
							      <div class="row" style="padding: 6px;">
							         <div class="col-sm-2">Target Points</div>
							         <div class="col-sm-10"><input id="signup_bonus" name="signup_bonus" type="number"
							            placeholder="Signup Bonus" class="form-control"
							            value="{{$data->signup_bonus}}"/></div>
							      </div>
							      <div class="row"  style="padding: 6px;">
							         <div class="col-sm-2">Bonus Points</div>
							         <div class="col-sm-10"><input id="signup_bonus" name="signup_bonus" type="number"
							            placeholder="Signup Bonus" class="form-control"
							            value="{{$data->signup_bonus}}"/></div>
							      </div>
							   </div>
							</div>
							-->
						
						<div class="form-group">
							<label for="publish" class="col-sm-2 control-label">Allow Refund of Points <br/><small>If Redeem Request Cancelled</small></label>
							<div class="col-sm-10">
<input name="refund_points_redeem_cancelled" value="Yes" @if($data->refund_points_redeem_cancelled=='Yes') checked @endif type="radio">&nbspEnable &nbsp;
<input name="refund_points_redeem_cancelled" @if($data->refund_points_redeem_cancelled=='No') checked @endif value="No" type="radio">&nbspDisabled
							 </div>
						</div>
						<!--
						<div class="form-group">
							<label for="publish" class="col-sm-2 control-label">Maintenance Status</label>
							<div class="col-sm-10">
							<input name="redeem_status" value="Active" @if($data->redeem_status=='Active') checked @endif type="radio">&nbspEnable &nbsp;
							<input name="redeem_status" @if($data->redeem_status=='Inactive') checked @endif value="Inactive" type="radio">&nbspDisabled
							 </div>
						</div>
						-->
						<div class="form-group">
							<label for="logo" class="col-sm-2 control-label">Site Logo</label>
							<div class="col-sm-10">
								<div class="fileinput fileinput-new" data-provides="fileinput">
									@if(isset($data->logo))
									<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
										<img src="{{URL::to("uploads/logo/$data->logo") }}" style="max-width:100%; max-height:100%;"><br>
									</div>
									@else
									<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>	
									@endif

									<div>
										<span class="btn btn-warning btn-file">
											<span class="fileinput-new">Site Logo</span>
											<span class="fileinput-exists">Change</span>
											<input accept="image/*" type="file" name="logo">
										</span>	
										@if(!isset($data->logo))
										<a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
										@endif
									</div>
									<div>Recommended Size : 327 x 214 </div>
								</div>	
							</div>
						</div>
							

						<div class="pager wizard">
							<button type="submit" class="btn btn-primary submit" >Submit</button>
						</div>

					</div>
				</form>
			</div>

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
<script src="{{ asset('assets/admin/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
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
          	$('#wait-div').hide();
          	var errMsg = (data && data.message) ? data.message : ('Request failed (' + response.status + '). Please try again.');
          	console.error('site-setting submit failed', response.status, response.responseText);
          	toastr.error(errMsg, 'Error !');
          }
      });
		return false;
	});


</script>
@stop


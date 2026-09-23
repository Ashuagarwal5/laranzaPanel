@extends('admin/layouts/default')
@section('title')
    Email Settings::CRM
@stop
@section('header_styles')
<link href="{{ asset('assets/admin/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/admin/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/admin/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/> 
<link href="{{ asset('assets/admin/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet">

@stop
@section('content')
    <section class="content-header">
        <h1>Edit Email Setting</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Admin</li>
            <li class="active">Edit Email Setting</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="pen" data-size="20" data-c="#fff" data-hc="#fff" data-loop="true"></i>
                            Edit Email Setting
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
				
					<form id="basic" action="{{route('email-setting')}}" method="POST" class="form-horizontal ajax_form">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<h2 class="hidden">&nbsp;</h2>
						
							<div class="form-group">
								<label for="goes_from_name" class="col-sm-2 control-label">Goes From Name</label>
								<div class="col-sm-10">
									<input id="goes_from_name" name="goes_from_name" type="text"
									placeholder="Goes From Name" class="form-control"
									value="{{$data->goes_from_name}}"/>
								</div>
							</div>
							
							<div class="form-group">
								<label for="goes_from_email" class="col-sm-2 control-label">Goes From Email</label>
								<div class="col-sm-10">
									<input id="goes_from_email" name="goes_from_email" type="text"
										   placeholder="Goes From Email" class="form-control"
										   value="{{$data->goes_from_email}}"/>
							</div>
							</div>
							
							<div class="form-group">
								<label for="contact_email" class="col-sm-2 control-label">Contact Email</label>
								<div class="col-sm-10">
								<input id="contact_email" name="contact_email" type="text"
								placeholder="Contact Email" class="form-control"
								value="{{$data->contact_email}}"/>
								 </div>
							</div>
							
							<div class="form-group">
								<label for="contact_email" class="col-sm-2 control-label">Customer Support Email Address</label>
								<div class="col-sm-10">
								<input id="contact_email" name="customer_support_email" type="text"
								placeholder="Customer Support Email" class="form-control"
								value="{{$data->customer_support_email}}"/>
								 </div>
							</div>
							
<!--
							<div class="form-group">
								<label for="contact_email" class="col-sm-2 control-label">Sales Email Address</label>
								<div class="col-sm-10">
								<input id="contact_email" name="sales_email" type="text"
								placeholder="Sales Email" class="form-control"
								value="{{$data->sales_email}}"/>
								 </div>
							</div>
							
							<div class="form-group">
								<label for="contact_email" class="col-sm-2 control-label">Careers Email Address</label>
								<div class="col-sm-10">
								<input id="contact_email" name="careers_email" type="text"
								placeholder="Careers Email" class="form-control"
								value="{{$data->careers_email}}"/>
								 </div>
							</div>
-->
							
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
<script src="{{ asset('assets/admin/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"type="text/javascript"></script>
<script src="{{ asset('assets/admin/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"type="text/javascript"></script>
<script src="{{ asset('assets/admin/vendors/intl-tel-input/js/intlTelInput.min.js') }}"type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/pages/validation.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/admin/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/jquery.form.js') }}"type="text/javascript"></script>
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
@stop


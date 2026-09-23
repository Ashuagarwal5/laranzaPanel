@extends('admin/layouts/default')
{{-- Web site Title --}}
@section('title')Header Menu  Manager::CRM
@stop
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('public/assets/css/pages/jquery.timepicker.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
    <div class="be-content">
        <div class="page-head">
          <h2 class="page-head-title">Header Menu  Manager</h2>
          <ol class="breadcrumb page-head-nav">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{ route('admin.banner') }}">Header Menu  Manager</a></li>
            <li class="active">@if(isset($data)) Edit @else Create @endif</li>
          </ol>
        </div>
        <div class="main-content container-fluid">
          <div class="row">
			  <div class="col-sm-12">
			  <div class="panel panel-default panel-border-color panel-border-color-primary">
                <div class="panel-heading panel-heading-divider">Header Menu Information Form <a href="{{ route('admin.header')}}" class="btn btn-sm btn-danger pull-right"><span class=""></span> Back</a></div>
                <div class="panel-body">
				<form method="post" id="basic_info" class="ajax_form" action="{{$route}}" enctype="multipart/form-data">
				<div class="col-sm-12">
					<div class="alert" style="margin-top:10px;display:none;">
					<a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
					</div>
				  </div>
				  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
				  <div class="form-group col-sm-12">
                        <label for="validate-text">Header Menu Title *</label>
                         <input type="text"  class="form-control input-sm" name="link_showing_name" value="@if(!empty(old('link_showing_name')!='')){{old('link_showing_name')}}@elseif(isset($data->link_showing_name)){!!$data->link_showing_name!!}@endif"  id="validate-text"placeholder="Enter Link Showing Name" >
				@if(!empty($errors->first('link_showing_name')))<div class="btn btn-sm btn-danger">{{ $errors->first('link_showing_name') }}</div>@endif
                            </div>
<!--
				  <div class="form-group col-sm-12">
		<label for="validate-select">Link Type *</label>
			<select class="form-control input-sm selecttype" name="link_type" id="divshow">
				<option value="">Please Select</option>
				<option value="Internal" id=""  @if(!empty($data->link_type))@if($data->link_type =='Internal')  selected="selected"   @endif @endif>Internal</option>
				<option value="External" id="" @if(!empty($data->link_type))@if($data->link_type =='External')  selected="selected"   @endif @endif>External</option>
			</select>
							@if(!empty($errors->first('link_type')))<div class="btn btn-sm btn-danger">{{ $errors->first('link_type') }}</div>@endif

		<div id="hint_text" style="display:none"> Hint : <span id="div1"></span>  &nbsp;For <span id="div2"></span></div>
	</div>
-->

			  <div class="form-group">
				<label for="exampleInputEmail1">Parent Menu</label>
				<select name="parent_menu" class="form-control" id="exampleInputEmail1">
					<option value="">Select Parent Menu</option>
					@foreach(App\HeaderMenu::getHeaderMenu() as $key=>$value)
					<option @if($menu->parent_menu == $value->id) selected="" @endif value="{{$value->id}}">{{$value->link_address }}</option>
					@endforeach
				</select>
			  </div>


<div class="form-group col-sm-12">
                        <label for="validate-text">Header Menu Link *</label>
                         <input type="text"  class="form-control input-sm" name="link_address" value="@if(!empty(old('link_address')!='')){{old('link_address')}}@elseif(isset($data->link_address)){!!$data->link_address!!}@endif"  id="validate-text"placeholder="Enter Link Address" >
				@if(!empty($errors->first('link_address')))<div class="btn btn-sm btn-danger">{{ $errors->first('link_address') }}</div>@endif
                            </div>
							<div class="form-group col-sm-12">
                        <label for="validate-text">Header Menu Order *</label>
                         <input type="text"  class="form-control input-sm" name="shownig_order" value="@if(!empty(old('shownig_order')!='')){{old('shownig_order')}}@elseif(isset($data->shownig_order)){!!$data->shownig_order!!}@endif"  id="validate-text"placeholder="Enter Menu Order" >
				@if(!empty($errors->first('shownig_order')))<div class="btn btn-sm btn-danger">{{ $errors->first('shownig_order') }}</div>@endif
                            </div>
							<div class="form-group col-sm-12">
                        <label for="validate-text">Open In New Tab *</label>
							<div class="input-group ">
                         <input type="radio"   name="blank_target" value="Yes"
				 @if(!empty(old('blank_target')!='')){{old('blank_target')}} @elseif(isset($data->blank_target)){{ $data->blank_target == "Yes" ? 'checked="checked"' : '' }}@endif  id="validate-text" >Yes , Open In New Tab
						 <input type="radio" name="blank_target" value="No" @if(!empty(old('blank_target')!='')){{old('blank_target')}} @elseif(isset($data->blank_target)){{ $data->blank_target == "No" ? 'checked="checked"' : '' }}@endif id="validate-text" >No, Just Open In Parent Page
						    </div>
         <p class="text-right">
    	                  <button type="submit" class="btn btn-space btn-primary">Submit</button>
        		          <a href="{{ URL::to('nkfi_ctrl_admin/header') }}"class="btn btn-default">Cancel</a>
         </p>
                      </form>
                    </div></div></div></div>
</section>
@stop
@section('footer_scripts')
    <script src="{{ asset('assets/admin/lib/parsley/parsley.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"type="text/javascript"></script>
     <script src="{{ asset('assets/admin/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/intl-tel-input/js/intlTelInput.min.js') }}"type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      	//initialize the javascript
		$('.timePicker').timepicker({ 'scrollDefault': 'now' });
      	App.init();
      	$('form').parsley();
      });
    </script>
    <script src="{{ asset('assets/js/pages/validation.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
    <script src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/js/pages/datepicker.js') }}" type="text/javascript"></script>
   <script src="{{ asset('public/assets/js/pages/jquery.timepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/js/jquery.form.js') }}"type="text/javascript"></script>
	<script>
$('#divshow').bind('change', function(event) {
       var data = 	$(this).val();
       if(data!="")
       {
		   $('#hint_text').show();
		   if(data=='Internal')
		   {
				$('#div1').html('Please add page name which is within the website');
				$('#div2').html(data);
		   }
		   else if(data=='External')
		   {
				$('#div1').html('Link should be from any 3rd party website, i.e. http://www.sitename.com');
				$('#div2').html(data);
		   }
	   }
	   else
	   {
		    $('#hint_text').hide();
	   }
});
</script>
<script>
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
              $('.formmessage').remove();
              $('#wait-div').show();
          },
          success: function(response) {
              $(".submit").removeAttr("disabled", 'disabled');
              $("input[type=submit]").html('Processing');
             $('.ajax_form').find('.form-group').removeClass('has-error');
              if (response.messageNot) {
                  $(formid).find('.alert').removeClass('alert-success').removeClass('alert-danger').fadeOut(100);
              } else {
                  $(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
                  if (response.status == "success") {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
                  } else {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
						 $.each(response.errorArray, function( key, value ) {
						  console.log(key + " => " + value);
						  var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';


						$('.ajax_form').find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').closest('.form-group').addClass('has-error');
						   $('.ajax_form').find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').before(msg);
						  });
                  }
              }
              if (response.img_url) {
                  $('#reviewImage').hide();
                  $('#reviewImgResponse').show();
                  $('#reviewImgResponse').attr('src', response.img_url);
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
              $.each(data, function(key, value) {
                  console.log(key + " => " + value);
                  var msg = '<label class="error formmessage" for="' + key + '"  style="color:red">' + value + '</label>';
                  $('.ajax_form').find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);
              });
          }
      });
      return false;
  });
   </script>


   <script>
	 $(document).ready(function(){
      	//initialize the javascript
     	App.init();
     	App.formElements();
		 });
	</script>
    @stop

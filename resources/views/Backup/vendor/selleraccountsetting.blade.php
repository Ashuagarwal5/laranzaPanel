@extends('vendor/header')

{{-- Page title --}}
@section('title')

@parent
@stop

{{-- page level styles --}}
@section('header_styles')

		<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/smart-forms.css') }}" />
		<link href="{{ asset('assets/css/toastr.css') }}" rel="stylesheet" type="text/css"/>


@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	 <div class="container-fluid">
      <div class="row">
        @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Seller Account Settings</h1>

<div class="panel">
<div class="panel-heading"><h2 class="pull-left">Change Your Password</h2>
          <div class="clr"></div>
</div>
<div class="panel-body">
<div  class="account clearfix ">
<form class="form-horizontal smart-forms ajaxform" method="POST" action="{!! route('seller.updateaccountsetting') !!}">
	 <input type="hidden" name="_token" value="{{ csrf_token() }}" />
<div class="panel">

            <fieldset id="account">

              <div class="form-group required" style="display: none;">
                <label class="col-sm-2 control-label">Customer Group</label>
                <div class="col-sm-10">
                  <div class="radio">
                    <label>
                      <input name="customer_group_id" value="1" checked="checked" type="radio">
                      Default</label>
                  </div>
                </div>
              </div>

              <div id="up_pass_user" style="display:none" class="alert alert-danger"></div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname">Old Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="input-password" placeholder="Password" value="{!! old('Password') !!}" name="old_password">
                  {!! $errors->first('old_password', '<span class="help-block">:message</span>') !!}
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">New Password</label>
                   <div class="col-sm-10">
                  <input type="password" class="form-control" id="input-confirm" placeholder="New Password " value="" name="password">
                   {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-mobile">Confirm Password</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="input-confirm" placeholder="Confirm Password" value="" name="confirm">
                  {!! $errors->first('confirm', '<span class="help-block">:message</span>') !!}

                </div>
              </div>

            </fieldset>

            <div class="form-group">
                <div class=" col-sm-10  col-sm-offset-2">
                  <button class="btn btn-pink btn-chnahe-user-pass" type="submit">Submit</button> 
                </div>
              </div>
            </div>

  </form>
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

         <script  src="{{ asset('assets/js/toastr.min.js') }}"  type="text/javascript"></script>
         <script  src="{{ asset('assets/default/js/seller.js') }}"  type="text/javascript"></script>


    <!--page level js ends-->

@stop

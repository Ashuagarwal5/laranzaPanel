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
          <h1 class="page-header">Seller  Profile Settings</h1>

<div class="panel">
<div class="panel-heading"><h2 class="pull-left">Profile Information</h2>
          <div class="clr"></div>
</div>
<div class="panel-body">
<div  class="account clearfix ">
<form class="form-horizontal smart-forms" method="POST" action="{!! route('seller.updateprofile') !!}">
	 <input type="hidden" name="_token" value="{{ csrf_token() }}" />
<div class="panel">
           <div class="panel-heading"><h3>Your Basic Information </h3><small>Tell us about you, it can help us to serve you better</small></div>
            <fieldset id="account">

          <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname">First Name</label>
                <div class="col-sm-10">
                  <input name="first_name" value="@if(isset($sellerdetail)){{$sellerdetail->first_name}} @endif" placeholder="First Name" id="input-firstname" class="form-control" type="text">
                 {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname">Last Name</label>
                <div class="col-sm-10">
                  <input name="last_name" value="@if(isset($sellerdetail)){{$sellerdetail->last_name}} @endif" placeholder="Last Name" id="input-lastname" class="form-control" type="text">
                {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email">E-Mail Address</label>
                <div class="col-sm-10">
                  <input name="email" value="@if(isset($sellerdetail)){{$sellerdetail->email}} @endif" placeholder="E-Mail" id="input-email" class="form-control" type="email" disabled>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-mobile">Mobile No.</label>
                <div class="col-sm-10">
                  <input name="mobileno" value="@if(isset($sellerdetail)){{$sellerdetail->mobileno}} @endif" placeholder="Mobile No" id="input-email" class="form-control" type="Mobile" disabled>
                {!! $errors->first('mobileno', '<span class="help-block">:message</span>') !!}

                </div>
              </div>

            </fieldset>

            <div class="form-group">
                <div class=" col-sm-10  col-sm-offset-2">
                  <button class="btn btn-pink" type="submit">Submit</button> <button class="btn btn-pink" type="submit">Reset</button>
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
    </div>




    <!-- //Container End -->
@stop

{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->

		<script>
		</script>
		<script  src="{{ asset('assets/js/toastr.min.js') }}"  type="text/javascript"></script>
		<script  src="{{ asset('assets/default/js/seller.js') }}"  type="text/javascript"></script>
    <!--page level js ends-->

@stop

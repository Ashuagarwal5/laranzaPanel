@extends('vendor/header')

{{-- Page title --}}
@section('title')

@parent
@stop

{{-- page level styles --}}
@section('header_styles')

      <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/smart-forms.css') }}" />


@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	 <div class="container-fluid">
      <div class="row">
        @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Seller Bank Settings</h1>

<div class="panel">
<div class="panel-heading"><h2 class="pull-left">Bank Information</h2>
          <div class="clr"></div>
</div>
<div class="panel-body">
<div  class="account clearfix ">
<form class="form-horizontal smart-forms" method="POST" action="{!! route('seller.updatebanksetting') !!}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<div class="panel">
				
				<fieldset id="account">

				<div class="form-group required">
					<label class="col-sm-2 control-label" for="input-firstname">Bank Account Name</label>
					<div class="col-sm-10">
					<input name="bank_account_name" value="@if(isset($sellerdetail)){{$sellerdetail->bank_account_name}}@endif" placeholder="Account Name" id="input-firstname" class="form-control" type="text">
					{!! $errors->first('bank_account_name', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				
				<div class="form-group required">
					<label class="col-sm-2 control-label" for="input-lastname">Bank Account Number</label>
					<div class="col-sm-10">
					<input name="account_no" value="@if(isset($sellerdetail)){{$sellerdetail->account_no}}@endif" placeholder="Account Number" id="input-lastname" class="form-control" type="text">
					{!! $errors->first('account_no', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				
				<div class="form-group required">
					<label class="col-sm-2 control-label" for="input-email">Bank Name & Branch</label>
					<div class="col-sm-10">
					<input name="branch" value="@if(isset($sellerdetail)){{$sellerdetail->branch}}@endif" placeholder="Branch" id="input-email" class="form-control" type="text">
					{!! $errors->first('branch', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
					
				<div class="form-group required">
				<label class="col-sm-2 control-label" for="input-mobile">Branch Code</label>
					<div class="col-sm-10">
					<input name="ifsc_code" value="@if(isset($sellerdetail)){{$sellerdetail->ifsc_code}}@endif" placeholder="Branch Code" id="input-email" class="form-control" type="Mobile">
					{!! $errors->first('ifsc_code', '<span class="help-block">:message</span>') !!}

					</div>
				</div>

				</fieldset>

				<div class="form-group">
					<div class=" col-sm-10  col-sm-offset-2">
					<button class="btn btn-pink" type="submit">Submit</button>
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




    <!--page level js ends-->

@stop

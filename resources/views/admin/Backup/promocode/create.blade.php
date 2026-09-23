@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Promo Codes Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
    

<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.multiselect.css') }}">

   
  
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
Promo Codes Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Promo Codes Manager</li>
        <li class="active">
        	@if(isset($Promocode))
            Edit 
            @else
            Add
            @endif Promo Code

        </li>
    </ol>
</section>

<!-- Main content -->
    <section class="content">

		<div class="row">
		<div class="col-lg-12">
			
<div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                         @if(isset($Promocode))
			            Edit 
			            @else
			            Add
			            @endif Promo Code
                        </h3>
						<div class="pull-right">

								<a href="{{ route('promocode') }}" class="btn btn-sm btn-danger"><span class="btn-label">
                                     <i class="glyphicon glyphicon-chevron-left"></i>
                                </span><span style="margin-left:8px">Back</span></a>
						</div>
                    </div>
                    <div class="panel-body">
                    		<form method="post" id="promo_form" >
			 <input type="hidden" name="_token" value="{{ csrf_token() }}" />
		<div class="form-group">
		
			<label for="validate-text">Promo Title</label>

			<div class="input-group">
			<input type="text" class="form-control" name="title" value="@if(isset($Promocode->title)){{$Promocode->title}}@else{{old('title')}}@endif" id="validate-text"
			placeholder="Enter Promo Title">
			<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
			</div>
			<div class="has-error">
				  {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
			</div>
		</div>

	
		<div class="form-group" id="coupon_code_div">
			<label for="validate-length">Coupon Code</label>
			
			<div class="input-group">
				<input type="text" class="form-control" name="coupon_code" value="@if(isset($Promocode->coupon_code)){{$Promocode->coupon_code}}@else{{old('coupon_code')}}@endif" id="coupon_code"
				placeholder="Coupon Code">
				<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
			</div>
			<div class="has-error">
				  {!! $errors->first('coupon_code', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		
		
		<div class="form-group" id="coupon_code_div">
			<label for="validate-length">Use Per Coupon</label>
			
			<div class="input-group">
			<input type="text" class="form-control" name="uses_per_coupon" value="@if(isset($Promocode->uses_per_coupon)){{$Promocode->uses_per_coupon}}@else{{old('uses_per_coupon')}}@endif" id="validate-length"
				placeholder="Use Per Coupon">
				<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
			</div>
			<div class="has-error">
				  {!! $errors->first('uses_per_coupon', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		

		
		
		<div class="form-group">
			<label for="validate-number">Use Per Customer</label>
				<div class="input-group">
				<input type="text" class="form-control" name="uses_per_customer" value="@if(isset($Promocode->uses_per_customer)){{$Promocode->uses_per_customer}}@else{{old('uses_per_customer')}}@endif" id=""
				placeholder="Use Per Customer">
				<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
			</div>
			<div class="has-error">
				  {!! $errors->first('uses_per_customer', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		
		<div class="form-group">
			{{-- <label for="validate-number">Apply Minimum Price</label> --}}
			<div class="input-group">
				<input type="hidden" class="form-control" name="apply_min_price" value="1" id=""
				placeholder="Apply Minimum Price">
			</div>
			{{-- <label for="validate-number">Apply Maximun Price</label> --}}
			<div class="input-group">
				<input type="hidden" class="form-control" name="apply_max_price" value="10000000" id=""
				placeholder="Apply Maximun Price">
			</div>
		</div>
		
		
		<div class="form-group">
			<label>From Date</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-laptop"></i>
				</div>
				<input readonly type="text" data-provide="datepicker" class="form-control" name="from_date" value="@if(isset($Promocode->from_date)){{date('m/d/Y',$Promocode->from_date)}}@else{{old('from_date')}}@endif">
				<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
			</div>
			<div class="has-error">
				  {!! $errors->first('from_date', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		
		<div class="form-group">
			<label>To Date</label>
			<div class="input-group">
				<div class="input-group-addon">
				<i class="fa fa-laptop"></i>
				</div>
				<input readonly type="text" data-provide="datepicker" class="form-control" name="to_date" value="@if(isset($Promocode->to_date)){{date('m/d/Y',$Promocode->to_date)}}@else{{old('to_date')}}@endif">
				<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
			</div>
			<div class="has-error">
				  {!! $errors->first('to_date', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		
		<div class="form-group">
			<label for="validate-phone">Apply As</label>
			
			<div class="input-group">
			<select name="apply_as" class="form-control" id="apply_as">
				<option value="">--Select--</option>
				<option value="percentage of product price discount" @if(isset($Promocode->apply_as))@if($Promocode->apply_as=="percentage of product price discount")selected @endif @endif>Percentage of product price discount</option>
				<option value="fixed amount discount" @if(isset($Promocode->apply_as)) @if($Promocode->apply_as=="fixed amount discount")selected @endif @endif>Fixed amount discount</option>
			</select>	

			<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
		</div>
		<div class="has-error">
				  {!! $errors->first('apply_as', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		
		
		<div class="form-group">
			<label for="validate-number">Discount</label>
			
			<div class="input-group">
				<input type="number" step="0.5" min="0" class="form-control" name="discount" value="@if(isset($Promocode->discount)){{$Promocode->discount}}@else{{old('discount')}}@endif" id="discount"
				placeholder="Use Per Coupon">
				<span class="input-group-addon success">
			<span class="glyphicon glyphicon-ok"></span>
			</span>
			</div>
				<div class="has-error">
				  {!! $errors->first('discount', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
			
			<div class="col-md-12 mar-10">
				<div class="col-xs-4 col-md-4"></div>
				<div class="col-xs-4 col-md-2">
					<button type="submit" id="next_btn" class="btn btn-primary btn-block btn-md btn-responsive">
						Save
					</button>

				</div>
			</div>
				
		</form>


                        </form>
                    </div>
                </div>



		</div>
		</div>
		
	</section>



            <!-- content -->


@stop
@section('footer_scripts')

<script>
	$(".datepicker").datepicker({});
</script>
@stop

@extends('admin/layouts/default')
@section('title')
About Us::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<style>
	.tab {
		overflow: hidden;
		border: 1px solid #ccc;
		background-color: #f1f1f1;
	}

	/* Style the buttons inside the tab */
	.tab button {
		background-color: inherit;
		float: left;
		border: none;
		outline: none;
		cursor: pointer;
		padding: 14px 16px;
		transition: 0.3s;
		font-size: 17px;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
		background-color: #ddd;
	}

	/* Create an active/current tablink class */
	.tab button.active {
		background-color: #ccc;
	}

	/* Style the tab content */
	.tabcontent {
		display: none;
		padding: 6px 12px;
		border: 1px solid #ccc;
		border-top: none;
	}
</style>
@stop
@section('content')
<section class="content-header">
	<h1>
	About Us    </h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>About Us</li>
		<li class="active">
			@if(isset($data))
			Edit 
			@else
			Create
			@endif Section
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
						@endif Section
					</h3>
					<div class="pull-right">

						<a href="{{ route('admin.about-us') }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>

					</div> 
				</div>
				<div class="panel-body">
					<form method="post" id="productForm" class="ajaxformclass" action="@if(isset($data)){{route('admin.about-us.edit.store',['id'=>$data->id])}}@else{{route('admin.about-us.store')}}@endif" 
						enctype="multipart/form-data">

						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						
						<!-- <div class="form-group has-success">
							<label for="validate-text"> Title *</label>
							<div class="input-group">
								<input type="text" class="form-control" name="title" id="validate-text"
								value="@if(isset($data->title)){{$data->title}}@endif" placeholder="Enter Section  Title ">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div> -->

						<div class="form-group has-success">
							<label for="validate-text"> Display Order *</label>
							<div class="input-group">
								<input type="text" class="form-control" name="display_order" id="validate-text"
								value="@if(isset($data->display_order)){{$data->display_order}}@endif" placeholder="Enter Section  Display Order ">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>

						<div class="form-group has-success">
							<label for="validate-text">Image *</label>
							@if(isset($data->image))
							<img src='{{ URL::to(App\Helpers\Thumbnail::image("/about-us/$data->image","200","80","ff=ffffff")) }}'>
							@endif
							<div class="input-group">
								<input type="file" class="form-control" name="image">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
							<p class="mt-2">Recommended size : 642x500</p>
						</div>


						<div class="panel panel-success">
							<div class="panel-heading">
								<div class="text-muted bootstrap-admin-box-title editor-clr">
									<i class="livicon" data-name="thermo-down" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
									  Description *
								</div>
							</div>
							<div class="bootstrap-admin-panel-content">
								<textarea  style="width: 100%;height: 260px;" id="ckeditor_full" name="description" required>@if(isset($data->description)){{$data->description}}@else{{old('description')}}@endif</textarea>
							</div>
							<div class="has-error">
								{!! $errors->first('description', '<span class="help-block">:message</span>') !!}
							</div>
						</div>

						<div class="col-md-12 mar-10">
							<div class="col-xs-4 col-md-4"></div>
							<div class="col-xs-4 col-md-2">
								<input type="submit" class="btn btn-primary btn-block btn-md submit" value="Save">			
							</div>
							<div class="col-xs-4 col-md-2">
								<a class="btn btn-warning btn-block btn-md" href="{{ route('admin.about-us') }}">Cancel</a>
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
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap-dialog.min.js') }}"></script>


@stop

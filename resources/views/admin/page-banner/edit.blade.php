@extends('admin/layouts/default')
@section('title')
Banners::CRM
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

@stop
@section('content')
<section class="content-header">
	<h1>
	Banners    </h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>Banners</li>
		<li class="active">
			@if(isset($data))
			Edit 
			@else
			Create
			@endif Banner
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
						@endif Banner
					</h3>
					<div class="pull-right">

						<a href="{{ route('admin.page-banner') }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>

					</div> 
				</div>
				<div class="panel-body">
					<form method="post" id="productForm" class="ajaxformclass" action="@if(isset($data)){{route('admin.page-banner.edit.store',['id'=>$data->id])}}@else{{route('admin.page-banner.store')}}@endif" 
						enctype="multipart/form-data">

						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<!-- 
						<div class="form-group has-success">
							<label for="validate-text">Page Name *</label>
							<div class="input-group">
								<input type="text" class="form-control" name="page_name" id="validate-text"
								value="@if(isset($data->page_name)){{$data->page_name}}@endif" placeholder="Enter Page name ">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>
						!-->
						<div class="form-group has-success">
							<label for="validate-text">Banner Title *</label>
							<div class="input-group">
								<input type="text" class="form-control" name="title" id="validate-text"
								value="@if(isset($data->title)){{$data->title}}@endif" placeholder="Enter Banner  Title ">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>

						<div class="form-group has-success">
							<label for="validate-text">Image *</label>
							@if(isset($data->image))
							<img src="{{ URL::to(App\Helpers\Thumbnail::image("/page-banner/$data->image","200","80","ff=ffffff")) }}">
							@endif
							<div class="input-group">
								<input type="file" class="form-control" name="image">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>

						<div class="form-group has-success">
							<label for="validate-text">Description *</label>
							<div class="input-group">
								<textarea name="description" id="description" class="form-control" cols="30" rows="10">@if(isset($data->description)){{$data->description}}@endif</textarea>
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>

						<div class="col-md-12 mar-10">
							<div class="col-xs-4 col-md-4"></div>
							<div class="col-xs-4 col-md-2">
								<input type="submit" class="btn btn-primary btn-block btn-md submit" value="Save">			
							</div>
							<div class="col-xs-4 col-md-2">
								<a class="btn btn-warning btn-block btn-md" href="{{ route('admin.page-banner') }}">Cancel</a>
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

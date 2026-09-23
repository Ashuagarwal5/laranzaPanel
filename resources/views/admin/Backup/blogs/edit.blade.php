@extends('admin/layouts/default')
@section('title')
Blog Manager::CRM
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
	Blogs Manager    </h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>Blogs Manager</li>
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
						@endif Blog
					</h3>
					<div class="pull-right">
						<a href="{{ route('admin.blogs') }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>
					</div>
				</div>
				<div class="panel-body">

					<form method="post" id="page-form" enctype="multipart/form-data" class="ajaxformclass">

						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<div class="col-sm-12">
				           <div class="alert" style="margin-top:10px;display:none;">
				             <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
				           </div>
				        </div>	

						<div class="form-group has-success">
							<label for="validate-text">Blog Title </label>
							<div class="input-group">
								<input type="text" class="form-control" name="blog_title" value="@if(isset($data->blog_title)){{$data->blog_title}}@else{{old('blog_title')}}@endif" id="validate-text"
								placeholder="Enter Blog Title ">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>
							</div>
							<div class="has-error">
								{!! $errors->first('blog_title', '<span class="help-block">:message</span>') !!}
							</div>
						</div>

						<div class="form-group has-success">
							<label for="validate-text">Display Order </label>
							<div class="input-group">
								<input type="text" class="form-control" name="display_order" value="@if(isset($data->display_order)){{$data->display_order}}@else{{old('display_order')}}@endif" id="validate-text"
								placeholder="Display Order ">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>
							</div>
							<div class="has-error">
								{!! $errors->first('display_order', '<span class="help-block">:message</span>') !!}
							</div>
						</div>

						<div class="panel panel-success">
							<div class="panel-heading">
								<div class="text-muted bootstrap-admin-box-title editor-clr">
									<i class="livicon" data-name="thermo-down" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
									Blog Content
								</div>
							</div>
							<div class="bootstrap-admin-panel-content">
								<textarea  style="width: 100%;

								height: 260px;" id="ckeditor_full" name="blog_content" required>@if(isset($data->blog_content)){{$data->blog_content}}@else{{old('blog_content')}}@endif</textarea>
							</div>
							<div class="has-error">
								{!! $errors->first('blog_content', '<span class="help-block">:message</span>') !!}
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
								<a class="btn btn-warning btn-block btn-md submit" href="{{ route('admin.blogs') }}">Cancel</a>
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



@stop

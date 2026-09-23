@extends('admin/layouts/default')
@section('title')
Sub Admin Manager::CRM
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
	Sub Admin Manager    </h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>Sub Admin Manager</li>
		<li class="active">
			Change Password
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
						Change Password 
					</h3>
					<div class="pull-right">
						<a href="{{ route('admin.sub_admin') }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>
					</div>
				</div>
				<div class="panel-body">
					<form id="password_change_form" class="ajaxformclass" method="Post" action="{{route('admin.sub_admin.change_password_post',$id)}}">
						<div class="col-sm-12">
							<div class="alert" style="margin-top:10px;display:none;">
								<a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
							</div>
						</div> 
						{{csrf_field()}}
						<div class="form-group">
							<label for="exampleInputPassword1"> Password</label>
							<input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder=" Password">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Confirm Password</label>
							<input type="password" class="form-control" name="confirm_password" id="exampleInputPassword1" placeholder="Confirm Password">
						</div>

						<button type="submit" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
@stop
@section('footer_scripts')
<script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
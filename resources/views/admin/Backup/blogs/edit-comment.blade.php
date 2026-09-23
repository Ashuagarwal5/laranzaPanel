@extends('admin/layouts/default')
@section('title')
Blogs Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />

<style>

	.btn-default{

		border: 1px solid #ddd;

	}
</style>


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
			@endif  Blog Comment
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
						@endif Blog Comment
					</h3>
					<div class="pull-right">

						<a href="{{route('blogs/blogs-comments') }}" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left" aria-hidden="true"></i><span style="margin-left:8px">Back</span></a>
					</div>
				</div>
				<div class="panel-body">
					<form method="post" id="basic_info" class="ajaxformclass" 
					action="{{ route('blogs-comment.edit.store') }}"  enctype="multipart/form-data">
					<div class="col-sm-12">
						<div class="alert" style="margin-top:10px;display:none;">
							<a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
						</div>
					</div>
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<input type="hidden" name="blog_id", value="{{$data->id}}" />

					<div class="form-group col-sm-12">
						<label for="name"> Name *</label>
						<input  id="name"  class="form-control input-sm" name="name" placeholder="Enter Name" value="@if(isset($data->name)){!!$data->name!!} @endif">
					</div>

					<div class="form-group col-sm-12">
						<label for="email">Email *</label>
						<input  id="email"  class="form-control input-sm" name="email" placeholder="Enter Email" value="@if(isset($data->email)){!!$data->email!!} @endif">
					</div>
					<div class="form-group col-sm-12">
						<label for="status">Status *</label><br>
						<input type="radio" name="status" value="Publish" 
								 {{(isset($data->status) && $data->status=='Publish') ? 
								  'checked' : ''}}> Publish
								  &nbsp&nbsp&nbsp
						<input type="radio" name="status" 
						 {{(isset($data->status) && $data->status=='Unpublish') ? 
						  'checked' : ''}} value="Unpublish"> Unpublish
					</div>

					<div class="form-group col-sm-12">
						<label for="message">Blog Message *</label>
						<textarea    class="form-control input-sm" name="message" rows="4" 
						id="validate-text"placeholder="Enter Message" >@if(isset($data->message)){!!$data->message!!} @endif</textarea>
					</div>
					<p class="text-right">
						<button type="submit" class="submit btn btn-space btn-primary">Submit</button> 
						<a href="{{route('blogs/blogs-comments')}}"class="btn btn-default">Cancel</a>
					</p>
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

<script type="text/javascript">
	$(function () {
               // $('#datetimepicker4').datepicker();
           });
       </script>


       @stop

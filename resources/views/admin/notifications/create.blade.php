@extends('admin/layouts/default')
@section('title')
{{ $manager_name }} Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/drag-drop/dist/imageuploadify.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/admin/css/select2.min.css') }}" rel="stylesheet"/>
<style>
.customers .select2-container {
	width:100% !important;
}
</style>
@stop
@section('content')
<section class="content-header">
	<h1>
	{{ $manager_name }} Manager    </h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>{{ $manager_name }} Manager</li>
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
						@endif {{ $manager_name }}
					</h3>
					<div class="pull-right">
						<a href="{{ $manager_url }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>
					</div>
				</div>
				<div class="panel-body">
					<form method="post"  class="ajaxformclass" action="" enctype="multipart/form-data">
						<div class="col-sm-12">
							<div class="alert" style="margin-top:10px;display:none;">
								<a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
							</div>
						</div>
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<div class="form-group col-sm-12">
							<label for="validate-text">Title *</label>
							<input type="text"  class="form-control input-sm" name="title"
							value="@if(!empty(old('title')!='')){{old('title')}}@elseif(isset($data->title)){!!$data->title!!}@endif"  id="validate-text"placeholder="Enter Title" >
							@if(!empty($errors->first('title')))<div class="btn btn-sm btn-danger">{{ $errors->first('title') }}</div>@endif
						</div>
						<div class="form-group col-sm-12">
							<label for="validate-text">Description *</label>
							<textarea   class="form-control input-sm" name="description" id="validate-text"placeholder="Enter Description" >@if(!empty(old('description')!='')){{old('description')}}@elseif(isset($data->description)){!!$data->description!!}@endif</textarea>
							@if(!empty($errors->first('description')))<div class="btn btn-sm btn-danger">{{ $errors->first('description') }}</div>@endif
						</div>
						<div class="form-group col-sm-12">
							<label for="validate-text">Message Send Type *</label>
							<select class="form-control" name="send_type" id="send_type">
				<option value="individual" @if(isset($data->send_type) && $data->send_type == 'individual') selected="" @endif>Individual</option>				<option value="all" @if(isset($data->send_type) && $data->send_type == 'all') selected="" @endif>All</option>
							</select>
						</div>

						<div class="form-group col-sm-12">
							<label for="validate-text">User Type *</label>
							<select class="form-control" name="type">
								<option value="">--Select User Type--</option>
								@if(!empty($all_roles))
								@foreach($all_roles as $key => $value)
								@if($value->name == 'User')
								<option value="{{ $value->slug }}" @if(isset($data->type) && $data->type == $value->slug) selected="" @endif>{{$value->name}}</option>@endif
								@endforeach
								@endif
								
							</select>
						</div>
						
						
						
						<div class="form-group col-sm-12" id="users">
							<label for="validate-text">Customer *</label>
							<select class="form-control customers" name="user_id" id="mySelect2">
								<option value="">--Select Customer--</option>
								@if(!empty($users))
								@foreach($users as $key => $value)
								<!-- @if(!empty($value->full_name)) -->
								<option value="{{$value->id}}"@if(!empty($data->user_id)){{$data->user_id == $value->id ? 'selected':''}}@endif>{{$value->full_name}}</option>
								{{-- <option value="{{ $value->id }}" @if(!empty($data->user_id) && $data->user_id > 0 && $data->user_id==$value->id) selected="selected" @endif>{{ $value->full_name }}</option> --}}
								<!-- @endif -->
								@endforeach
								@endif
							</select>
						</div>

						

						<input type="hidden" name="get_user_id" id="get_user_id" class="get_user_id" value="@if(isset($data->user_id)){{$data->user_id}}@endif">
						<div class="form-group col-sm-12">
							<label for="validate-text">Upload Image</label>
							<input class="form-control" type="file" name="image">
							@if(isset($data->image))
							<img src="{{ URL::to(App\Helpers\Thumbnail::image("notifications/$data->image","300","200","cf"))}}" alt="image not found" />
							@endif
						</div>
						
						{{-- @if (isset($data->status))
						@if($data->status!= 'Sent') --}}
						<p class="text-right">
							<button type="submit" class="btn btn-space btn-primary">Submit</button>
							<a href="{{route('admin.notifications')}}"class="btn btn-default">Cancel</a>
						</p>
						{{-- @endif
						@endif --}}
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
<script src="{{asset('assets/admin/js/select2.min.js')}}" type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/drag-drop/dist/imageuploadify.min.js') }}"  type="text/javascript"></script>
<script type="text/javascript">
	$(function () {
               // $('#datetimepicker4').datepicker();
           });
       </script>
       <script type="text/javascript">
       	$(document).ready(function() {
       		$('input[type="file"]').imageuploadify();
       		
       		$('#mySelect2').select2({
       			selectOnClose: true
       		});
       	})
       	function getUser(val)
       	{

       		$.get('{{route("user.get-user")}}/?user_role='+val, function(data) {
       			var selected_record=[];
				var user_id = $('#get_user_id').val();
       			$.each(data, function(index, val2) {

       				if(user_id==val2.id)
       				{
       					selected_record.push(val2.id);
       				}
       				$('.customers').append('<option value="'+val2.id+'">'+val2.full_name+' ('+val2.mobileno+')</option>')
       			});
       			$('.customers').select2().val(selected_record).trigger('change');

       		});
       	}
       	
       	$(document).on('change', 'select[name="send_type"]', function(event) {
       			let send_type=$(this).val();
       			if(send_type == 'all')
       			{
       				$('#users').hide();
       				$('.customers').empty();
       			}
       			else
       			{
       				$('#users').show();
       			}	

       		});
       	
       	
       </script>
       @stop

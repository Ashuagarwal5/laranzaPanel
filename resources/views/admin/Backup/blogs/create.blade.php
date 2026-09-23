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
		      @endif  Blog
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

                  <a href="{{ $navi['back_url'] }}" class="btn btn-sm btn-danger"><i class="fa fa-arrow-left" aria-hidden="true"></i><span style="margin-left:8px">Back</span></a>
                </div>
              </div>
              <div class="panel-body">
					    <form method="post" id="basic_info" class="ajaxformclass" action="{{ $navi['route'] }}" 
                enctype="multipart/form-data">
        				<div class="col-sm-12">
        					<div class="alert" style="margin-top:10px;display:none;">
        					  <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
        					</div>
        				</div>
				        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            
                <div class="form-group col-sm-12">
      			      <label for="validate-text"> Category *</label>
      					  <select id="blog_category" name="blog_category" class="form-control">
          					<option value="">--Select Category--</option>
          					@foreach(App\BlogCategory::getAllList() as $key=>$value)
          					   <option @if(isset($data->blog_category) && $data->blog_category==$value->id) selected="" 
                         @endif value="{{$value->id}}">{{$value->blog_cate_title}}
                       </option>
          					@endforeach
      					  </select>
      			    </div>

          			<div class="form-group col-sm-12">
            			<label for="name">Blog Title *</label>
            			<input  id="name"  class="form-control input-sm" name="blog_title" placeholder="Enter Title" value="@if(isset($data->blog_title)){!!$data->blog_title!!} @endif">
          			</div>


                <div class="form-group col-sm-12">
                  <label for="validate-text">Blog Description *</label>
                  <textarea   id="ckeditor_full" class="form-control input-sm" name="blog_content"
                  id="validate-text"placeholder="Enter Description" >@if(isset($data->blog_content)){!!$data->blog_content!!} @endif</textarea>
                </div>

		            <div class="form-group fileinput fileinput-new col-sm-12" data-provides="fileinput">                
                  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                    <img src='@if(isset($data->image)){{url("storage/app/uploads/blogs/".$data->image) }} @endif'><br>
                  </div>
                  <div>
                    <span class="btn btn-default btn-file">
                      <span class="fileinput-new">Select Image</span>
                      <span class="fileinput-exists">Change</span>
                      <input accept="image/*"  type="file" name="image">
                    </span>
                  </div>
                </div>

                 <p class="text-right">
            	      <button type="submit" class="submit btn btn-space btn-primary">Submit</button> 
                		<a href="{{ $navi['back_url'] }}"class="btn btn-default">Cancel</a>
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

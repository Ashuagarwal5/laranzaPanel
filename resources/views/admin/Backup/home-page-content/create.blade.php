@extends('admin/layouts/default')
@section('title')
Home Page Content::CRM
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
  Home Page Content    </h1>
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('admin.dashboard') }}">
        <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
        Dashboard
      </a>
    </li>
    <li>Home Page Content</li>
    <li class="active">
      @if(isset($data))
      Edit 
      @else
      Add 
      @endif Home Page Content
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
            Add 
            @endif Home Page Content
          </h3>
          <div class="pull-right">

            <a href="{{ route('admin.home-page-content') }}" class="btn btn-sm btn-danger"><span class="btn-label">
             <i class="glyphicon glyphicon-chevron-left"></i>
           </span><span style="margin-left:8px">Back</span></a>


         </div>
       </div>
       <div class="panel-body">

        <form method="post" id="basic_info" class="ajaxformclass" action="{{$route}}" enctype="multipart/form-data">
          <div class="col-sm-12">
           <div class="alert" style="margin-top:10px;display:none;">
             <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
           </div>
         </div>	
         <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="form-group col-sm-12">
          <label for="validate-text">Product Name *</label>
          <select name="product_id" class="form-control">
           <option value="">select product</option>
           @if(!empty($products))
           @foreach($products as $product)\
             @if(isset($data->product_id) && $data->product_id == $product->id)
               @php($class='selected')
               @else
               @php($class='')
             @endif
             <option value="{{$product->id}}" {{$class}}>{{$product->product_title}}</option>
           @endforeach
           @else
             <option value="">-No Product Found -</option>
           @endif
          </select>

          @if(!empty($errors->first('title')))<div class="btn btn-sm btn-danger">{{ $errors->first('title') }}</div>@endif
        </div>		

        <div class="form-group col-sm-12">
          <label for="validate-text">Title *</label>
          <input id="validate-text" type="text" name="title" value="{{isset($data->title) ? $data->title : old('title')}}" class="form-control input-sm">
        </div>	

        <div class="form-group col-sm-12">
          <label for="validate-text">Display Order *</label>
          <input id="validate-text" type="text" name="display_order" value="{{isset($data->display_order) ? $data->display_order : old('display_order')}}" class="form-control input-sm">
        </div>  

        <div class="form-group col-sm-12 imageDiv">
         <label for="validate-text">Image</label>
         <input id="image" accept="iamge/*" type="file" name="image" value="" class="form-control input-sm" @if(isset($data->image)=="")  @endif   id="validate-text" >
         @if(isset($data->image))
          <img src="{{ URL::to(App\Helpers\Thumbnail::image("home-page-content/$data->image","300","200","cf"))}}" alt="image not found" />
         @endif
         @if(!empty($errors->first('image')))
          <div class="btn btn-sm btn-danger">{{ $errors->first('image') }}</div>
         @endif
        </div>
         <div class="col-sm-12" id="recoom_size"> Recommended Size : <span id="top">313 x 430</span>  &nbsp;For 
          <span id="middle"></span> Image
        </div>
        <div class="clearfix"></div>
<br />
<div class="col-sm-12">
        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="editor-clr">
              
              Description *
            </div>
          </div>
          <div class="panel-body">
            <textarea  style="width: 100%; height: 260px;" id="ckeditor_full" name="description" required>@if(isset($data->description)){{$data->description}}@else{{old('description')}}@endif</textarea>
          </div>
          <div class="has-error">
            {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
          </div>
        </div>	
        </div>	

        <p class="text-right">
         <button type="submit" class="btn btn-space btn-primary">Submit</button>
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



@stop

@extends('admin/layouts/default')
@section('title')
Product Images Manager::CRM
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
  Product Images Manager    </h1>
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('admin.dashboard') }}">
        <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
        Dashboard
      </a>
    </li>
    <li>Product Images Manager</li>
    <li class="active">
      @if(isset($detail))
      Edit 
      @else
      Add Image
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
            @if(isset($detail))
            Edit 
            @else
            Add 
            @endif Image
          </h3>
          <div class="pull-right">

            <a href="{{ route('products.images', $product_id) }}" class="btn btn-sm btn-danger"><span class="btn-label">
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
          <label for="validate-text">Product Name</label>
          <select name="product_id" class="form-control">
           @if(!empty($product))
             <option value="{{$product->id}}" selected>{{$product->product_title}}</option>
           @else
           <option value="">-No Product Found -</option>
           @endif
         </select>

         @if(!empty($errors->first('title')))<div class="btn btn-sm btn-danger">{{ $errors->first('title') }}</div>@endif
       </div> 

        <div class="form-group col-sm-12">
          <label for="display_order">Display Order</label>
          <input type="text" name="display_order" id="display_order" class="form-control" 
                  value="{{isset($detail->display_order) ?$detail->display_order : old('display_order')}}">

         @if(!empty($errors->first('display_order')))<div class="btn btn-sm btn-danger">{{ $errors->first('display_order') }}</div>@endif
        </div>     

       <div class="form-group col-sm-12 imageDiv">
         <label for="validate-text">Image</label>
         <input id="image" accept="iamge/*" type="file" name="image" value="" class="form-control input-sm" @if(isset($detail->image)=="")  @endif   id="validate-text" >
         @if(isset($detail->image))
         <img src="{{ URL::to(App\Helpers\Thumbnail::image("productimg/$detail->image","300","200","cf"))}}" alt="image not found" />
         @endif
         @if(!empty($errors->first('image')))
         <div class="btn btn-sm btn-danger">{{ $errors->first('image') }}</div>
         @endif
       </div>
       <div class="col-sm-12" id="recoom_size"> Recommended Size : <span id="top">240 x 165</span>  &nbsp;For <span id="middle"></span> Image</div>
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

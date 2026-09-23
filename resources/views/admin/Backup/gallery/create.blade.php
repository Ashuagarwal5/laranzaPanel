@extends('admin/layouts/default')
@section('title')
Product Gallery Manager::CRM
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
  Product Gallery Manager    </h1>
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('admin.dashboard') }}">
        <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
        Dashboard
      </a>
    </li>
    <li>Product Gallery Manager</li>
    <li class="active">
      @if(isset($gallery))
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
            @if(isset($gallery))
            Edit 
            @else
            Add 
            @endif Image
          </h3>
          <div class="pull-right">

            <a href="{{ route('admin.gallery') }}" class="btn btn-sm btn-danger"><span class="btn-label">
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
           <option value="">select product</option>
           @if(!empty($products))
           @foreach($products as $product)\
           @if(isset($gallery->product_id) && $gallery->product_id==$product->id)
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
         <label for="display_order">Display Order</label>
         <input type="text" name="display_order" class="form-control" id="display_order" value="{{isset($gallery->display_order) ? $gallery->display_order : old('display_order')}}">
         @if(!empty($errors->first('display_order')))<div class="btn btn-sm btn-danger">{{ $errors->first('display_order') }}</div>@endif
       </div>			





       <div class="form-group col-sm-12 imageDiv" style="display:@if(!empty($gallery) && $gallery->type=='Image') {{'block'}}@else {{'none'}} @endif">
         <label for="validate-text">Image</label>
         <input id="image" accept="iamge/*" type="file" name="image" value="" class="form-control input-sm" @if(isset($gallery->image)=="")  @endif   id="validate-text" >
         @if(isset($gallery->image))
         <img src="{{ URL::to(App\Helpers\Thumbnail::image("gallery/$gallery->image","300","200","cf"))}}" alt="image not found" />
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


<script>
  $(document).ready(function()
  {

   MyFun();

	/*
    $("#type").change(function()
    {
        var type=$(this).val();
				if(type=='Image'){
					//$("#vedio").prop('required',false);
					//$("#image").prop('required',true);
					$(".vedioDiv").hide();
			 		$(".imageDiv").show();
				}else if(type=='Vedio'){
					//$("#image").prop('required',false);
					//$("#vedio").prop('required',true);
					$(".imageDiv").hide();
					$(".vedioDiv").show();
				}else{
					$(".vedioDiv").hide();
					$(".imageDiv").hide();
				}       
    	});
    	
    	*/
    });



  function MyFun(){

   $(".vedioDiv").hide();
   $(".imageDiv").show();

 }


</script>


@stop

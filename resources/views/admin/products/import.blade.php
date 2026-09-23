@extends('admin/layouts/default')
@section('title')
Product CSV Manager::CRM
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
  Product CSV File Import    </h1>
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('admin.dashboard') }}">
        <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
        Dashboard
      </a>
    </li>
    <li>Product CSV Manager</li>
    <li class="active">
      @if(isset($detail))
      Edit 
      @else
      Upload Excel File
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
            @endif QR Code CSV File
          </h3>
          <div class="pull-right">

            <a href="{{ route('admin.products') }}" class="btn btn-sm btn-danger"><span class="btn-label">
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
         <label for="validate-text">Upload CSV File</label>
         <input id="product_csv" accept="csv/*" type="file" name="product_csv" value="" class="form-control input-sm" id="validate-text" >
        
         @if(!empty($errors->first('product_csv')))
         <div class="btn btn-sm btn-danger">{{ $errors->first('product_csv') }}</div>
         @endif
       </div>
       <div class="col-sm-12">
	    <u>Important Points</u> 
			<ul style="line-height:25px;">
				<li>Upload CSV or Excel file</li>
				<li>CSV/Excel File format must match the required format</li>	
				<li>Download Sample CSV File <a href=" {{ URL::to('assets/default/img/SampleExcelFile.xlsx') }}">Sample Excel File</a></li>
				<li>In CSV file if any QR Value already exists in the database then its Reward Value will be updated</li>	
				<li>If CSV file has new QR value then it will be inserted as new record</li>						
			</ul>
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

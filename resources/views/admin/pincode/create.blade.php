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
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
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
            <a href="{{ route('admin.pincode') }}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-chevron-left"></span>Back</a>

          </div>
        </div>
        <div class="panel-body">
         <form method="post" id="basic_info" class="ajaxformclass" action="{{ $route_url }}" enctype="multipart/form-data">
          <div class="col-sm-12">
           <div class="alert" style="margin-top:10px;display:none;">
             <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
           </div>
         </div>
         <input type="hidden" name="_token" value="{{ csrf_token() }}" />
         <div class="form-group col-sm-12">
           <label for="validate-text">State *</label>
           <select name="state" class="form-control input-sm">
             <option value="">Select State</option>
             @if(!empty($state))
             @foreach($state as $value)
             <option value="{{$value->name}}" @if(!empty(old('state')) && old('state')==$value->name) selected="selected" @elseif(!empty($data->state) && $data->state==$value->name) selected="selected" @endif>{{$value->name}}</option>
             @endforeach
             @endif
           </select>
           @if(!empty($errors->first('state')))<div class="btn btn-sm btn-danger">{{ $errors->first('state') }}</div>@endif
         </div>
         <div class="form-group col-sm-12">
           <label for="validate-text">District *</label>
           <input type="text"  class="form-control input-sm" name="district"
           value="@if(!empty(old('district')!='')){{old('district')}}@elseif(isset($data->district)){!!$data->district!!}@endif"  id="validate-text"placeholder="Enter District" >
           @if(!empty($errors->first('district')))<div class="btn btn-sm btn-danger">{{ $errors->first('district') }}</div>@endif
         </div>
         <div class="form-group col-sm-12">
           <label for="validate-text">City *</label>
           <input type="text"  class="form-control input-sm" name="city"
           value="@if(!empty(old('city')!='')){{old('city')}}@elseif(isset($data->city)){!!$data->city!!}@endif"  id="validate-text"placeholder="Enter City" >
           @if(!empty($errors->first('city')))<div class="btn btn-sm btn-danger">{{ $errors->first('city') }}</div>@endif
         </div>
         <div class="form-group col-sm-12">
          <label for="validate-text">Pincode *</label>
          <input type="text"  class="form-control input-sm" name="pincode"
          value="@if(!empty(old('pincode')!='')){{old('pincode')}}@elseif(isset($data->pincode)){!!$data->pincode!!}@endif"  id="validate-text"placeholder="Enter pincode" >
          @if(!empty($errors->first('pincode')))<div class="btn btn-sm btn-danger">{{ $errors->first('pincode') }}</div>@endif
        </div>
        <p class="text-right">
         <button type="submit" class="btn btn-space btn-primary">Submit</button>
         <a href="{{ route('admin.pincode') }}"class="btn btn-default">Cancel</a>
       </p>
     </form>
     @include('admin.notifications')
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

<script type="text/javascript">
  $(function () {

  });
</script>


@stop

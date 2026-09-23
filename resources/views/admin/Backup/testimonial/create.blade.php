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
                   <form method="post" id="basic_info" class="ajaxformclass" action="{{$route}}" enctype="multipart/form-data">
                    <div class="col-sm-12">
                       <div class="alert" style="margin-top:10px;display:none;">
                           <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
                       </div>
                   </div>
                   <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                   <div class="form-group col-sm-12">
                    <label for="validate-text">User Name*</label>
                    <input type="text"  class="form-control input-sm" name="client_name"
                    value="@if(!empty(old('client_name')!='')){{old('client_name')}}@elseif(isset($data->client_name)){!!$data->client_name!!}@endif"  id="validate-text"placeholder="Enter User Name" >
                    @if(!empty($errors->first('client_name')))<div class="btn btn-sm btn-danger">{{ $errors->first('client_name') }}</div>@endif
                </div>




                <div class="form-group col-sm-12">
                    <label for="validate-text">User Photo</label>
                    <input type="file" name="client_photo" value="@if(isset($data->client_photo)){{ $data->client_photo }}@endif" class="form-control input-sm"   id="validate-text" >
                    @if(isset($data->client_photo))
                    <img src='{{URL::to(App\Helpers\Thumbnail::image("feedback/$data->client_photo","200","150","rf"))}}' alt="image not found" />
                    @endif

                    @if(!empty($errors->first('client_photo')))<div class="btn btn-sm btn-danger">{{ $errors->first('client_photo') }}</div>@endif
                </div>


                <div class="form-group col-sm-12">
                    <label for="validate-text">User Location*</label>
                    <input type="text"  class="form-control input-sm" name="client_location"
                    value="@if(!empty(old('client_location')!='')){{old('client_location')}}@elseif(isset($data->client_location)){!!$data->client_location!!}@endif"  id="validate-text"placeholder="Enter User Location" >
                    @if(!empty($errors->first('client_location')))<div class="btn btn-sm btn-danger">{{ $errors->first('client_location') }}</div>@endif
                </div>

                <div class="form-group col-sm-12">
                    <label for="validate-text">Feebback*</label>
                    <textarea class="form-control input-sm" name="feedback"
                    id="validate-text"placeholder="Enter Feedback" >@if(!empty(old('feedback')!='')){{old('feedback')}}@elseif(isset($data->feedback)){!!$data->feedback!!}@endif</textarea>

                @if(!empty($errors->first('feedback')))<div class="btn btn-sm btn-danger">{{ $errors->first('feedback') }}</div>@endif
            </div>


            <p class="text-right">
             <button type="submit" class="btn btn-space btn-primary">Submit</button>
             <a href="{{ URL::to('admin/events') }}"class="btn btn-default">Cancel</a>
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
<script  src="{{ asset('assets/drag-drop/dist/imageuploadify.min.js') }}"  type="text/javascript"></script>

<script type="text/javascript">
    $(function () {
               // $('#datetimepicker4').datepicker();
           });
       </script>

       <script type="text/javascript">
        $(document).ready(function() {
            $('input[type="file"]').imageuploadify();
        })
    </script>



    @stop

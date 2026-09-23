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
      @if(isset($data))
      Edit 
      @else
      Add Sub Admin
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
            Add 
            @endif Sub Admin
          </h3>
          <div class="pull-right">
            <a href="{{ route('admin.sub_admin') }}" class="btn btn-sm btn-danger"><span class="btn-label">
             <i class="glyphicon glyphicon-chevron-left"></i>
           </span><span style="margin-left:8px">Back</span></a>
         </div>
       </div>
       <div class="panel-body">
        <form method="post" id="supportofficers_form" class="ajaxformclass" action="{{$route}}" enctype="multipart/form-data">
          <div class="col-sm-12">
           <div class="alert" style="margin-top:10px;display:none;">
             <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
           </div>
         </div>	
         {{csrf_field()}}
         <div class="form-group col-sm-12">
          <label for="validate-text">Full Name *</label>
          <input type="text"  class="form-control input-sm" name="full_name" value="@if(!empty(old('full_name')!='')){{old('full_name')}}@elseif(isset($data->name)){!!$data->name!!}@endif"  id="validate-text"placeholder="Enter Sub Admin Name" >
          @if(!empty($errors->first('full_name')))<div class="btn btn-sm btn-danger">{{ $errors->first('full_name') }}</div>@endif
        </div>					
        
        <div class="form-group col-sm-12">
          <label for="validate-text">Email Address *</label>
          <input type="text"  class="form-control input-sm" name="email" value="@if(!empty(old('email')!='')){{old('email')}}@elseif(isset($data->emailadd)){!!$data->emailadd!!}@endif"  id="validate-text"placeholder="Enter Sub Admin Email" >
          @if(!empty($errors->first('email')))<div class="btn btn-sm btn-danger">{{ $errors->first('email') }}</div>@endif
        </div>

        <div class="form-group col-sm-12">
          <label for="validate-text">Mobile Number  *</label>
          <input type="text"  class="form-control input-sm" name="mobileno" value="@if(!empty(old('mobileno')!='')){{old('mobileno')}}@elseif(isset($data->contactno)){!!$data->contactno!!}@endif"  id="validate-text"placeholder="Enter Sub Admin Mobile Number" >
          @if(!empty($errors->first('mobileno')))<div class="btn btn-sm btn-danger">{{ $errors->first('mobileno') }}</div>@endif
        </div>
        @if(!isset($data))
        <div class="form-group col-sm-12">
          <label for="validate-text">Password *</label>
          <input type="password"  class="form-control input-sm" name="password" value="@if(!empty(old('password')!='')){{old('password')}}@elseif(isset($data->pswd)){!!$data->pswd!!}@endif"  id="validate-text"placeholder="Enter Sub Admin Password" >
          @if(!empty($errors->first('password')))<div class="btn btn-sm btn-danger">{{ $errors->first('password') }}</div>@endif
        </div>
        @endif


        <div class="form-group col-sm-12 imageDiv" style="display:@if(!empty($data) && $data->type=='Image') {{'block'}}@else {{'none'}} @endif">
          <label for="validate-text">Profile Image*</label>
          <input id="image"type="file" name="profile_photo" value="" class="form-control input-sm"    id="validate-text" >
          @if(isset($data->pic))
          <img src="{{ URL::to(App\Helpers\Thumbnail::image("user/$data->pic","300","200","cf"))}}" style="height: 250px;width: 300px;" alt="image not found" />
          @endif
          @if(!empty($errors->first('profile_photo')))<div class="btn btn-sm btn-danger">{{ $errors->first('profile_photo') }}</div>@endif
        </div>
        <div class="col-sm-12 col-md-12">
        <div class="form-group">
          <label for="inputEmail3" name="password" class="col-sm-4 control-label">Assign Privileges : </label>
          <br><br>
          <div class="col-sm-6 col-md-6" style="min-height: 160px;">
            <li style="font-size:15px; margin-bottom:10px;" >
            @php
            $privileges_arr=json_decode($record->privileges,true);
            @endphp
            <input type="checkbox" id='' class="check_0" name="support_officer[0][]" value="0" style="margin-right:10px;" {{($privileges_arr[0]!=null)?'checked':''}}/>
              <strong>Dashboard</strong>
            </li>
          </div>
          @foreach(App\Manager::manager() as $keym => $value)
          <div class="col-sm-6 col-md-6" style="min-height: 160px;">
            <li style="font-size:15px; margin-bottom:10px;" >
              <strong>{{$value->manager_name}} &rArr;</strong>
              <ul>
                
                @foreach(App\Manager::submanager($value->mng_id) as $keysu =>$subvalue)
                <ul style="list-style:none;">
                  <li style="line-height:20px;" >
                    <input type="checkbox" id='' class="check_{{$value->mng_id}}" name="support_officer[{{$value->mng_id}}][]" value="{{$subvalue->mng_id}}" style="margin-right:10px;" 
                    @if(isset($record) && ($record->privileges != 'null'))
                    @foreach(json_decode($record->privileges) as $val)
                    @foreach($val as $childkey=>$childvalue)
                    @if($subvalue->mng_id == $childvalue)
                    checked="checked"
                    @else
                    'checked="false"' 
                    @endif
                    @endforeach
                    @endforeach
                    @endif
                    /> 
                    <label for="" style="cursor:pointer;"> {{$subvalue->manager_name}}</label>
                  </li> 
                </ul>
                @endforeach
              </ul>
            </li>           
          </div>
          @if($keym != 0)
          @if($keym % 2 == 0)
          <div class="clr"></div>
          @endif                
          @endif                
          @endforeach   
          <div class="clearfix"></div>
        </div>
      </div>
      <p class="text-right">
       <button type="submit" class="btn btn-space btn-primary submit">Submit</button>
     </p>
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

@extends('admin/layouts/default')
@section('title')
Footer Menus Manager::CRM
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
    Footer Menus Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Footer Menus Manager</li>
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
                        @endif Footer Menus
                    </h3>
                    <div class="pull-right">

                        <a href="{{ route('admin.footer') }}" class="btn btn-sm btn-danger"><span class="btn-label">
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
                    <label for="validate-text">Footer Menu Title *</label>
                    <input type="text"  class="form-control input-sm" name="link_showing_name" value="@if(!empty(old('link_showing_name')!='')){{old('link_showing_name')}}@elseif(isset($data->link_showing_name)){!!$data->link_showing_name!!}@endif"  id="validate-text"placeholder="Enter Link Showing Name" >
                    @if(!empty($errors->first('link_showing_name')))<div class="btn btn-sm btn-danger">{{ $errors->first('link_showing_name') }}</div>@endif
                </div>
                
                
                <div class="form-group col-sm-12">
                  <label for="validate-select">Link Type *</label>
                  <select class="form-control input-sm selecttype" onchange='hinttext(this.value);' name="link_type" id="divshow">
                    <option value="">Please Select</option>
                    <option value="Internal" id=""  @if(!empty($data->link_type))@if($data->link_type =='Internal')  selected="selected"   @endif @endif>Internal</option>
                    <option value="External" id="" @if(!empty($data->link_type))@if($data->link_type =='External')  selected="selected"   @endif @endif>External</option>
                </select>
                @if(!empty($errors->first('link_type')))<div class="btn btn-sm btn-danger">{{ $errors->first('link_type') }}</div>@endif
                <div id="hint_text" style="display:none"> Hint : <span id="div1"></span>  &nbsp;For <span id="div2"></span></div>

            </div>









            <div class="form-group col-sm-12">
                <label for="validate-text">Footer Menu Link *</label>
                <input type="text"  class="form-control input-sm" name="link_address" value="@if(!empty(old('link_address')!='')){{old('link_address')}}@elseif(isset($data->link_address)){!!$data->link_address!!}@endif"  id="validate-text"placeholder="Enter Link Address" >
                @if(!empty($errors->first('link_address')))<div class="btn btn-sm btn-danger">{{ $errors->first('link_address') }}</div>@endif
                
                
                <span id="hinttext"> </span>
                
                
            </div>
            
            
            
            
            
            <div class="form-group col-sm-12">
                <label for="validate-text">Footer Menu Order *</label>
                <input type="text"  class="form-control input-sm" name="shownig_order" value="@if(!empty(old('shownig_order')!='')){{old('shownig_order')}}@elseif(isset($data->shownig_order)){!!$data->shownig_order!!}@endif"  id="validate-text"placeholder="Enter Menu Order" >
                @if(!empty($errors->first('shownig_order')))<div class="btn btn-sm btn-danger">{{ $errors->first('shownig_order') }}</div>@endif
            </div>
            <div class="form-group col-sm-12">
              <label for="validate-select">Footer Menu Loaction *</label>
              <select class="form-control input-sm selecttype"   name="link_showing_in_column" id="divshow">
                <option value="">Please Select</option>
                <option value="Column 1" id=""  @if(!empty($data->link_showing_in_column))@if($data->link_showing_in_column =='Column 1')  selected="selected"   @endif @endif>Column 1</option>
                <option value="Column 2" id="" @if(!empty($data->link_showing_in_column))@if($data->link_showing_in_column =='Column 2')  selected="selected"   @endif @endif>Column 2</option>
            </select>
            @if(!empty($errors->first('link_showing_in_column')))<div class="btn btn-sm btn-danger">{{ $errors->first('link_showing_in_column') }}</div>@endif
        </div>
        <div class="form-group col-sm-12">
            <label for="validate-text">Open In New Tab *</label>
            <div class="input-group ">
               <input type="radio"   name="blank_target" value="Yes" @if(!empty(old('blank_target')!='')){{old('blank_target')}} @elseif(isset($data->blank_target)){{ $data->blank_target == "Yes" ? 'checked="checked"' : '' }}@endif  id="validate-text" >Yes , Open In New Tab
               <input type="radio" name="blank_target" value="No" @if(!empty(old('blank_target')!='')){{old('blank_target')}} @elseif(isset($data->blank_target)){{ $data->blank_target == "No" ? 'checked="checked"' : '' }}@endif id="validate-text" >No, Just Open In Parent Page 
           </div>

           <p class="text-right">
             <button type="submit" class="btn btn-space btn-primary">Submit</button>
         </p>
     </form>
     
     
     
     
     
     
     
 </div></div></div></div>


 
 
 
 
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
    var site_url =  "{{ URL::to('/')}}";
    function hinttext(value)
    {
      if(value=='External')
          $('#hinttext').html('Hint: http://www.sitename.com');
      else if(value=='Internal')
          $('#hinttext').html('Hint: '+ site_url+'/'+'<b>Page name</b>');
      else
          $('#hinttext').html('');
  }
  
</script>

@stop

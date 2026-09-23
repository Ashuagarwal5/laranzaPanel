@extends('admin/layouts/default')
@section('title')
   Dealer Manager::CRM
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
Dealer Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Dashboard
            </a>
        </li>
        <li>Dealer Manager</li>
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
							Add
							  @endif Dealer
                        </h3>
                               <div class="pull-right">

								<a href="{{ route('admin.dealer') }}" class="btn btn-sm btn-danger"><span class="btn-label">
                                     <i class="glyphicon glyphicon-chevron-left"></i>
                                </span><span style="margin-left:8px">Back</span></a>


                  </div>
                    </div>
                    <div class="panel-body">
							
                        <form method="post" id="page-form"  class="ajaxformclass">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<div class="col-sm-12">
					<div class="alert" style="margin-top:10px;display:none;">
					<a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
					</div>
				  </div>


                            <div class="form-group has-success">

                                <label for="validate-text">Dealer Name </label>

                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" value="@if(isset($data->name)){{$data->name}}@else{{old('name')}}@endif" id="validate-text"
                                           placeholder="Enter Dealer Name ">
                                            <span class="input-group-addon success">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                            </span>
                                             
                                </div>
                            
											<div class="has-error">
                                              {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                                </div>
                            </div>
                            
                            
                            
                <div class="form-group has-success">
                        <label for="validate-text">Login Email Address *</label>
                        <input type="text"  class="form-control input-sm" name="email" value="@if(!empty(old('email')!='')){{old('email')}}@elseif(isset($data->email)){!!$data->email!!}@endif"  id="validate-text"placeholder="Enter Primary Email" >
                        @if(!empty($errors->first('email')))<div class="btn btn-sm btn-danger">{{ $errors->first('email') }}</div>@endif
                </div>

                <div class="form-group has-success">
                        <label for="validate-text">Login Mobile Number  *</label>
                        <input type="text"  class="form-control input-sm" name="mobileno" value="@if(!empty(old('mobileno')!='')){{old('mobileno')}}@elseif(isset($data->mobileno)){!!$data->mobileno!!}@endif"  id="validate-text"placeholder="Enter Primary Mobile Number" >
                        @if(!empty($errors->first('mobileno')))<div class="btn btn-sm btn-danger">{{ $errors->first('mobileno') }}</div>@endif
                </div>
               
               
               <!--
                @if(!isset($data))
                <div class="form-group has-success">
                        <label for="validate-text">Password *</label>
                        <input type="password"  class="form-control input-sm" name="password" value="@if(!empty(old('password')!='')){{old('password')}}@elseif(isset($data->pswd)){!!$data->pswd!!}@endif"  id="validate-text"placeholder="Enter Password" >
                        @if(!empty($errors->first('password')))<div class="btn btn-sm btn-danger">{{ $errors->first('password') }}</div>@endif
                </div>
                @endif
-->


      <div class="form-group has-success">
        <div class="form-group">
          <label>Location <span class="required">*</span></label>
          <select name="destination_id" class="form-control" >
            @foreach($locations as $key=>$value)
            <option value="{{$value->id}}" @if(isset($data->destination_id) && $data->destination_id=="$value->id") selected=""  @endif>{{$value->destination_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="has-error">
                                              {!! $errors->first('destination_id', '<span class="help-block">:message</span>') !!}
                                                </div>
                                                
      </div>
						

						 <div class="form-group has-success">
							
							<label for="validate-text">Dealer Full Address </label>
							<div class="bootstrap-admin-panel-content">
								<textarea rows="3" class="form-control" placeholder="Enter Address" id="address" name="address">@if(isset($data->address)){{$data->address}}@else{{old('address')}}@endif</textarea>
								
								
							</div>
							<div class="has-error">
								{!! $errors->first('address', '<span class="help-block">:message</span>') !!}
							</div>
						   </div>
							
							 <div class="form-group has-success">

                                <label for="validate-text">Dealer Contact Number </label>

                                <div class="input-group">
                                    <input type="text" class="form-control" name="contact_no" value="@if(isset($data->contact_no)){{$data->contact_no}}@else{{old('contact_no')}}@endif" id="validate-text"
                                           placeholder="Enter Dealer Contact Number ">
                                            <span class="input-group-addon success">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                            </span>
                                             
                                </div>
                            
											<div class="has-error">
                                              {!! $errors->first('contact_no', '<span class="help-block">:message</span>') !!}
                                                </div>
                            </div>
							
							
						
						<div class="col-md-12 mar-10">
							<div class="col-xs-4 col-md-4"></div>
						<div class="col-xs-4 col-md-2">

									<button type="submit"  class="btn btn-primary btn-block btn-md submit">
										Save
									</button>

									</div>
						<div class="col-xs-4 col-md-2">
							<a class="btn btn-warning btn-block btn-md submit" href="{{ route('admin.dealer') }}">Cancel</a>
						</div>

								</div>


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

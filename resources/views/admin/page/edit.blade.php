@extends('admin/layouts/default')
@section('title')
    Static Page Manager::CRM
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
Static Page Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Dashboard
            </a>
        </li>
        <li>Static Page Manager</li>
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
							  @endif Static Page
                        </h3>
                               <div class="pull-right">

								<a href="{{ route('admin.page') }}" class="btn btn-sm btn-danger"><span class="btn-label">
                                     <i class="glyphicon glyphicon-chevron-left"></i>
                                </span><span style="margin-left:8px">Back</span></a>


                  </div>
                    </div>
                    <div class="panel-body">
							
                        <form method="post" id="page-form" >

							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

                            <div class="form-group has-success">
                                <label for="validate-text">Page Title </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="page_title" value="@if(isset($data->page_title)){{$data->page_title}}@else{{old('page_title')}}@endif" id="validate-text"
                                           placeholder="Enter Page Title ">
                                            <span class="input-group-addon success">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                            </span>
                                </div>
                             <div class="has-error">
                                              {!! $errors->first('page_title', '<span class="help-block">:message</span>') !!}
                                                </div>
                            </div>
                            
                            <div class="form-group has-success" style="display:none">
                                <label for="validate-text">Page Sub Title </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="page_sub_title" value="@if(isset($data->page_sub_title)){{$data->page_sub_title}}@else{{old('page_sub_title')}}@endif" id="validate-text"
                                           placeholder="Enter Page Title ">
                                            <span class="input-group-addon success">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                            </span>
                                </div>
                             <div class="has-error">
                                              {!! $errors->first('page_sub_title', '<span class="help-block">:message</span>') !!}
                                                </div>
                            </div>
							
							
						<!--	<div class="form-group has-success">
                                <label for="validate-text">HTML Title </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="html_title" value="@if(isset($data->html_title)){{$data->html_title}}@else{{old('html_title')}}@endif" id="validate-text"
                                           placeholder="Enter HTML Title ">
                                            <span class="input-group-addon success">
                                                    <span class="glyphicon glyphicon-ok"></span>
                                            </span>
                                </div>
                             <div class="has-error">
                                              {!! $errors->first('html_title', '<span class="help-block">:message</span>') !!}
                                                </div>
                            </div>
                            
                            <div class="form-group has-success">
                                <label for="validate-text">Meta Description </label>
                                <div class="input-group">
									<textarea name="meta_description" placeholder="Enter Meta Description" class="form-control" id="validate-text">@if(isset($data->meta_description)){{$data->meta_description}}@else{{old('meta_description')}}@endif</textarea>                                  
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
                             <div class="has-error">
                                              {!! $errors->first('meta_description', '<span class="help-block">:message</span>') !!}
                                                </div>
                            </div>
                           !-->
						

						  <div class="panel panel-success">
							<div class="panel-heading">
								<div class="text-muted bootstrap-admin-box-title editor-clr">
									<i class="livicon" data-name="thermo-down" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
									Page Content
								</div>
							</div>
							<div class="bootstrap-admin-panel-content">
								<textarea id="ckeditor_full" name="page_description" required>@if(isset($data->page_description)){{$data->page_description}}@else{{old('page_description')}}@endif</textarea>
								
								
							</div>
							<div class="has-error">
								{!! $errors->first('page_description', '<span class="help-block">:message</span>') !!}
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
							<a class="btn btn-warning btn-block btn-md submit" href="{{ route('admin.page') }}">Cancel</a>
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

@extends('admin/layouts/default')
@section('title')
    Email Templates Manager::CRM
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
Email Templates Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Dashboard
            </a>
        </li>
        <li>Email Templates Manager</li>
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
                           @if(isset($detail))
						  Edit 
						  @else
							Create
							  @endif Email Template
                        </h3>
                               <div class="pull-right">

								<a href="{{ route('admin/emailtemplate') }}" class="btn btn-sm btn-danger"><span class="btn-label">
                                     <i class="glyphicon glyphicon-chevron-left"></i>
                                </span><span style="margin-left:8px">Back</span></a>


                  </div>
                    </div>
                    <div class="panel-body">
							
                        <form method="post" id="example-form">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group col-sm-12">
                                <label for="validate-text">Email Title </label>
                                    <input type="text" parsley-trigger="change"  required="" class="form-control parsley-success"  name="title" value="@if(isset($detail->title)){!! old('title', $detail->title) !!}@endif" id="validate-text"
                                           placeholder="Enter Email Title " required><ul class="parsley-errors-list" id="parsley-id-5"></ul>
                            </div>

                         <div class="form-group col-sm-12">
                                <label for="validate-phone">Subject</label>
                                    <input type="text" parsley-trigger="change"  required="" class="form-control parsley-success"  name="subject" value="@if(isset($detail->subject)){!! old('subject',$detail->subject) !!}@endif" id="validate-text"
                                           placeholder="Enter Email Subject " required><ul class="parsley-errors-list" id="parsley-id-5"></ul>
              </div>

              <div class="panel panel-defalult col-sm-12">
                <div class="panel-heading">
                        <label><b>Message Content</b></label>
                    </div>
                <div class="bootstrap-admin-panel-content">
                    <textarea id="ckeditor_full" name="message" required>@if(isset($detail->message)){{{ $detail->message }}}@endif</textarea>
                    
                    
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
									<a class="btn btn-default btn-block btn-md submit" href="{{ route('admin/emailtemplate') }}">Cancel</a>
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

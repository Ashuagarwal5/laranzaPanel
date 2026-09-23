@extends('admin/layouts/default')
@section('title')
    Enquiries Manager::CRM
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
Enquiry Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Dashboard
            </a>
        </li>
        <li>Enquiries Manager</li>
        <li class="active">
          @if(isset($data))
      	   Reply
      	  @else
      		  Reply
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
                                  Enquiry Reply Form
                        </h3>
				   <div class="pull-right">
					   
					    <a href="{{ URL::to('admin/enquiry') }}" class="btn btn-sm btn-danger"><span class="btn-label">
                       <i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>
                  </div>
             </div>
                    <div class="panel-body">
						  <form method="post" id="example-form" >
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group col-sm-12">
                                <label for="validate-text">Reply To </label>
									<input type="text" class="form-control" name="email" value="{{ $enquiry->email }}" id="validate-text"
									placeholder="Enter product name" required readonly>
                            </div>
							 <div class="form-group col-sm-12">
								  <label for="validate-text"> Message</label>
                  		<div class="bootstrap-admin-panel-content">
									<textarea  name="message" class="form-control" id="ckeditor" required></textarea>
								</div>
							</div>
                             <div class="col-md-12 mar-10">
								    <div class="col-xs-4 col-md-4"></div>
                                <div class="col-xs-4 col-md-2">
                                   <button type="submit"  class="btn btn-primary btn-block btn-md btn-responsive">
										Send
										</button>
                                </div>
                            </div>
                        </form>
                    
                    
           
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
</script> 
    @stop

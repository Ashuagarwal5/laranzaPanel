@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
   Admin Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
       
 <link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>

<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/> 
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
 Admin Manager   </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Admin Manager</li>
        <li class="active">
          @if(isset($managerDetail))
          Edit
          @else
           Create
           @endif
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="livicon" data-name="wrench" data-size="20" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                                @if(isset($managerDetail))
								  Edit Admin Manager
								  @else
									Add Admin Manager
								   @endif
                               
                          
                        </h4>
						<div class="pull-right">
							<a href="{{ route('admin_manager') }}" class="btn btn-sm btn-danger" ><span class="btn-label">
											<i class="glyphicon glyphicon-chevron-left"></i>
							</span><span style="margin-left:8px">Back</span></a>
						</div>

                                
                    </div>
                    <div class="panel-body">
                       
                       
                          <form method="post" id="example-form" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group">
                               
                                <label for="validate-text">Manager Name</label>

								<div class="input-group">
									<input type="text" class="form-control" name="manager_name" value="@if(isset($managerDetail->manager_name)){{$managerDetail->manager_name}}@endif" id="validate-text"
									placeholder="Enter Manager Name" required>
									<span class="input-group-addon @if(isset($managerDetail))success @else danger @endif">
										<span class="glyphicon @if(isset($managerDetail))glyphicon-ok @else glyphicon-remove @endif"></span>
									</span>
								</div>
                            </div>
                            
                           
                           
                             <div class="form-group">   
								  <label for="validate-text">Parent Id</label>   
							<div class="input-group">
									<input type="text" class="form-control" name="parent_id" value="@if(isset($managerDetail->parent_id)){{$managerDetail->parent_id}}@endif" id="validate-text"
									placeholder="Enter Parent Id" required>
									<span class="input-group-addon @if(isset($managerDetail))success @else danger @endif">
										<span class="glyphicon @if(isset($managerDetail))glyphicon-ok @else glyphicon-remove @endif"></span>
									</span>
								</div>
                            </div>
                            
                             <div class="form-group">   
								  <label for="validate-text">Page Set</label>   
							<div class="input-group">
									<input type="text" class="form-control" name="page_set" value="@if(isset($managerDetail->page_set)){{$managerDetail->page_set}}@endif" id="validate-text"
									placeholder="Enter Page Set" required>
									<span class="input-group-addon @if(isset($managerDetail))success @else danger @endif">
										<span class="glyphicon @if(isset($managerDetail))glyphicon-ok @else glyphicon-remove @endif"></span>
									</span>
								</div>
                            </div>
                            
                    
                            
                             <div class="form-group">   
								  <label for="validate-text">Page Link</label>   
							<div class="input-group">
									<input type="text" class="form-control" name="page_link" value="@if(isset($managerDetail->page_link)){{$managerDetail->page_link}}@endif" id="validate-text"
									placeholder="Enter Page Link" required>
									<span class="input-group-addon @if(isset($managerDetail))success @else danger @endif">
										<span class="glyphicon @if(isset($managerDetail))glyphicon-ok @else glyphicon-remove @endif"></span>
									</span>
								</div>
                            </div>
                            
                               <div class="form-group">   
								  <label for="validate-text">Display Order</label>   
							<div class="input-group">
									<input type="text" class="form-control" name="display_order" value="@if(isset($managerDetail->display_order)){{$managerDetail->display_order}}@endif" id="validate-text"
									placeholder="Enter Display Order" required>
									<span class="input-group-addon @if(isset($managerDetail))success @else danger @endif">
										<span class="glyphicon @if(isset($managerDetail))glyphicon-ok @else glyphicon-remove @endif"></span>
									</span>
								</div>
                            </div>
                            
                             <div class="form-group">   
								  <label for="validate-text">Class Name</label>   
							<div class="input-group">
									<input type="text" class="form-control" name="class_name" value="@if(isset($managerDetail->class_name)){{$managerDetail->class_name}}@endif" id="validate-text"
									placeholder="Enter Class Name" required>
									<span class="input-group-addon @if(isset($managerDetail))success @else danger @endif">
										<span class="glyphicon @if(isset($managerDetail))glyphicon-ok @else glyphicon-remove @endif"></span>
									</span>
								</div>
                            </div>
                            
                            
                          
							
							
                         
                       
                             <div class="col-md-12 mar-10">
								    <div class="col-xs-4 col-md-4"></div>
                                <div class="col-xs-4 col-md-2">
                               
                                   <button type="submit"  class="btn btn-primary btn-block btn-md btn-responsive">
										Save
										</button>        
                                     
                                </div>
                                <div class="col-xs-4 col-md-2">
                                 <a class="btn btn-success btn-block btn-md btn-responsive" href="{{ route('admin_manager') }}">Cancel</a>   
                                         
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


	<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/intl-tel-input/js/intlTelInput.min.js') }}"type="text/javascript"></script>
	<script src="{{ asset('assets/js/pages/validation.js') }}" type="text/javascript"></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
	
@stop

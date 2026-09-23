@extends('admin/layouts/default')
@section('title')
    Branch Reviews Manager::CRM
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
Branch Reviews Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Dashboard
            </a>
        </li>
        <li>Branch Reviews Manager</li>
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
							  @endif Branch Reviews
                        </h3>
				   <div class="pull-right">
						<a href="{{ route('admin.branch_reviews') }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>
                  </div>
             </div>
                    <div class="panel-body">
							
                        <form method="post" id="page-form" enctype="multipart/form-data">

							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

                          <div class="form-group has-success">
                                <label for="validate-text">User Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="user_name" value="@if(isset($data->user_name)){{$data->user_name}}@else{{old('user_name')}}@endif" id="validate-text" placeholder="Enter User Name">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								<div class="has-error">
									  {!! $errors->first('user_name', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>                      							                   											
                     							
                          <div class="form-group has-success">
                                <label for="validate-text">Review</label>
                                <div class="input-group">
									<textarea placeholder="Enter Review" class="form-control" name="review">@if(isset($data->review)){{$data->review}}@else{{old('review')}}@endif</textarea>
<!--
                                    <input type="text" class="form-control" name="review" value="@if(isset($data->review)){{$data->review}}@else{{old('review')}}@endif" id="validate-text" placeholder="Enter Review">
-->
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								<div class="has-error">
									  {!! $errors->first('review', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>                      							                   											
                     							
                          <div class="form-group has-success">
                                <label for="validate-text">Rate</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="rate" value="@if(isset($data->rate)){{$data->rate}}@else{{old('rate')}}@endif" id="validate-text" placeholder="Enter Rate">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								<div class="has-error">
									  {!! $errors->first('rate', '<span class="help-block">:message</span>') !!}
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
									<a class="btn btn-warning btn-block btn-md" href="{{ route('admin.branch_reviews') }}">Cancel</a>
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
</script>
   
    @stop

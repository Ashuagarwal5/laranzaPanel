@extends('admin/layouts/default')
@section('title')
    Comming Soon::CRM
@stop
@section('header_styles')
<link href="{{ asset('assets/admin/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/admin/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/admin/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/> 
<link href="{{ asset('assets/admin/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet">
@stop
@section('content')
<section class="content-header">
    <h1>
 Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li> Manager</li>
        <li class="active">
          List
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
                               Comming Soon...
                          
                        </h4>   
                    </div>
                    <div class="panel-body">
                          		
										<div class="tagrecord">

									</div>

									</div>     
                                           
                       
                    </div>
                </div>
        </div>
    </div>
    <!-- row-->
</section>
@stop
@section('footer_scripts')

<script src="{{ asset('assets/admin/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"type="text/javascript"></script>
<script src="{{ asset('assets/admin/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"type="text/javascript"></script>
<script src="{{ asset('assets/admin/vendors/intl-tel-input/js/intlTelInput.min.js') }}"type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/pages/validation.js') }}" type="text/javascript"></script>
<script  src="{{ asset('assets/admin/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/admin/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
<script  src="{{ asset('assets/admin/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/admin/js/pages/editor.js') }}"  type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/admin/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/jquery.form.js') }}"type="text/javascript"></script>
@stop

 @extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Banip Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<style>
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }

</style>
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>

 <link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>

<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
Banip Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Banip Manager</li>
        <li class="active">
           Create
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                          Create Banip
                        </h3>
                     <div class="pull-right" style="margin-top: -42px">

               <a href="{{ route('banip') }}" class="btn btn-sm btn-danger"  style="margin-bottom:-82px;"><span class="btn-label">
                                                <i class="glyphicon glyphicon-chevron-left"></i>
                                            </span><span style="font-size:13px;margin-left:8px">Back</span></a>


                  </div>            
                                
                    </div>
                    <div class="panel-body">
                        <form method="post" id="example-form" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group">

                                <label for="validate-text">Ban Ip</label>

                                <div class="input-group">
                                    <input type="text" class="form-control" name="ban_ip" value="" id="validate-text"
                                           placeholder="Enter Ban IP" required>
                                            <span class="input-group-addon danger">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                </div>
                                 {!! $errors->first('ban_ip', '<span class="help-block"style="color:red;">:message</span>') !!}
                            </div>
                               
                             <div class="col-md-12 mar-10">
								    <div class="col-xs-4 col-md-4"></div>
                                <div class="col-xs-4 col-md-2">

                                   <button type="submit"  class="btn btn-primary btn-block btn-md btn-responsive">
										Save
										</button>

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

<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/intl-tel-input/js/intlTelInput.min.js') }}"
            type="text/javascript"></script>


    <script src="{{ asset('assets/js/pages/validation.js') }}" type="text/javascript"></script>
    <script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
    <script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
    <script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
    <script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>

    @stop

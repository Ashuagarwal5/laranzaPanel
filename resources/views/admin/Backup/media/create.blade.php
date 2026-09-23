 @extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Media Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>

 <link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>

<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/bmggift/plugins/datepicker/css/bootstrap-datepicker3.min.css') }}" />

@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
Media Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>Media Manager</li>
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
                          Create Media
                        </h3>
            <div class="pull-right" style="margin-top: -10px">

               <a href="{{ route('media') }}" class="btn btn-sm btn-danger"><span class="btn-label">
                                                <i class="glyphicon glyphicon-chevron-left"></i>
                                            </span><span style="font-size:13px;margin-left:8px">Back</span></a>
                  </div>

                             </div>
                    <div class="panel-body">
                        <form method="post" id="example-form"  enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                          
                           
                            <div class="form-group">

                                <label for="validate-text">Folder Name</label>

                                <div class="input-group">
                                    <input type="text" class="form-control" name="folder_path" value="@if(!empty(old('folder_path')!='')){{old('folder_path')}}@endif" id="validate-text"placeholder="Enter Folder Name">
                                            <span class="input-group-addon danger">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                </div>
                                {!! $errors->first('folder_path', '<span class="help-block" style="color:red">:message</span>') !!}
                            </div>
                            
          
                           
                            <div class="form-group fileinput fileinput-new" data-provides="fileinput">
                          <label for="validate-text">Images</label>
                          <br>
								<span class="btn btn-default btn-file">
									<span class="fileinput-new">Upload Image</span>
									<span class="fileinput-exists">Change</span>
									<input type="file" name="image[]" multiple id="fileupload" >
								</span>	
								<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
								
									<p>Hint : You Can Upload Multiple Images at a time</p>

									   {!! $errors->first('image[]', '<span class="help-block" style="color:red">The image field is required.</span>') !!}
						 </div> 
						 
						 
						  <hr />
<b>Live Preview</b>
<br />
<br />
<div id="dvPreview">
	 </div>




                              <div class="col-md-12 mar-10">
								    <div class="col-xs-4 col-md-4"></div>
                                <div class="col-xs-4 col-md-2">

                                   <button type="submit"  class="btn btn-primary btn-block btn-md btn-responsive">
										save
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


<script language="javascript" type="text/javascript">
window.onload = function () {
    var fileUpload = document.getElementById("fileupload");
    fileUpload.onchange = function () {
        if (typeof (FileReader) != "undefined") {
            var dvPreview = document.getElementById("dvPreview");
            dvPreview.innerHTML = "";
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp |.pdf |.doc |.xlsx |.docx |.xls |)$/;
            for (var i = 0; i < fileUpload.files.length; i++) {
                var file = fileUpload.files[i];
            //  alert(file.name.toLowerCase);
                if (regex.test(file.name.toLowerCase()))
                 {
//alert('dd');
                    var reader = new FileReader();
                    reader.onload = function (e)
                     {
						
                        var img = document.createElement("IMG");
                        img.height = "100";
                        img.width = "100";
                        img.src = e.target.result;
                        dvPreview.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                } else {
                    alert(file.name + " is not a valid file.");
                    dvPreview.innerHTML = "";
                    return false;
                }
        
            }
        } else {
            alert("This browser does not support HTML5 FileReader.");
        }
    }
};
</script>




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
  
 
 
 <script type="text/javascript" src="{{ asset('assets/default/bmggift/plugins/datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    @stop

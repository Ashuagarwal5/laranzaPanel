 @extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Media Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')


<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />

<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>



<!--
<link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet"/>
-->
<!--
<link href="{{ asset('assets/css/jquery-ui-1.8.css') }}" rel="stylesheet"/>
-->
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" rel="stylesheet"/>


<link href="{{ asset('assets/imageuploader/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css') }}" rel="stylesheet"/>



<!--
-->



<!--
<link href="{{ asset('assets/drag-drop/dist/imageuploadify.min.css') }}" rel="stylesheet"/>
-->

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


                  </div>

                             </div>
                    <div class="panel-body">
                        <form method="post" id="ajax_formmedia" class="ajaxformclass" action="{{ route('admin.media.post') }}" enctype="multipart/form-data">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                          
					<div id="uploader" >
					<p></p>
					</div>  



                     <input type="hidden" name="dd" value="s">  


                              <div class="col-md-12 mar-10">
								    <div class="col-xs-4 col-md-4"></div>
                                <div class="col-xs-4 col-md-2">

                                   <button type="submit"  id="button_frm" class="submit btn btn-primary btn-block btn-md btn-responsive" disabled>

										save
										</button>

                                </div>

                            </div>


                        </form>
                   
                   
                   
                   
                   
                   <br>
                   
                   <hr>
                   
                    <table class="table table-bordered " id="tablem">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Image</th>                            
                            <th>Image Path</th>                            
                            <th>Date</th>                            
                            <th>Action</th>                            
                        </tr>
                        </thead>
                        <tbody>
							
                        </tbody>
                    </table>
               
               
                   
                   
                   
                   
                   
                   
                   
                    </div>
                </div>


        </div>
    </div>
    <!-- row-->
</section>
@stop
@section('footer_scripts')




<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    
    
<script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('assets/imageuploader/plupload/plupload.full.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/imageuploader/plupload/jquery.ui.plupload/jquery.ui.plupload.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/imageuploader/jquery.pulsate.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/imageuploader/managephoto.js') }}" ></script>




<!--
    <script  src="{{ asset('assets/drag-drop/dist/imageuploadify.min.js') }}"  type="text/javascript"></script>
-->

     <script type="text/javascript">
		 
		 var siteurl ="http://dolovery.com/";
		 var templateassets ="http://dolovery.com/assets/imageuploader";
		 var time='1564637630';
		 
            $(function () {
               // $('#datetimepicker4').datepicker();
            });
        </script>
        
         <script type="text/javascript">
            $(document).ready(function() {
               /// $('input[type="file"]').imageuploadify();
            })
            
            $(document).on("submit", ".ajax_formmedia", function(event) {
				
				event.prevent();
				
				alert('ajax_formmedia');
    
  });
  
  
   $(function() {
            var table = $('#tablem').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.media.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'image', name: 'image', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'image', name: 'image', render:function(data,type,row,meta){ return imagepath(data,type,row,meta)} },
                    { data: 'add_date', name: 'add_date' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                   // $(this).updateLivicon();
                });
            } );
        });

function displayimage(data,type,row,meta)
        {
			 if(data){
			  var storeitemimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/media-upload/","200","40","ff=ffffff")) }}/'+data;
			  var str='<img src="'+storeitemimageurl+'" />';
			 }
			 
			  return str;
        }
        
        function imagepath(data,type,row,meta)
        {
			var href= siteurl+'uploads/media-upload'+'/'+data;
			//var href= siteurl+'{{ ("storage/app/uploads/media-upload") }}'+'/'+data;
			 //~ if(data){
			  //~ var storeitemimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/media-upload/","200","40","ff=ffffff")) }}/'+data;
			  //~ var str='<img src="'+storeitemimageurl+'" />';
			 //~ }
			 var str = '<a target="_blank" href="'+href+'">'+href+'</a>';
			  return str;
        }
        function deleteMedia(id){
			
			
		var txt;
  var r = confirm("Press a button!\nEither OK or Cancel.\nThe button you pressed will be deleted this media.");
  if (r == true) {
	  
	  
	  $.get(siteurl+'admin/media/deleted-data'+'/'+id, function(data, status){
		  
		  
		  toastr.success("Media Deleted");
		  
		  
		  location.reload();
    ///alert("Data: " + data + "\nStatus: " + status);
    });
  
  
    txt = "You pressed OK!";
  } else {
    txt = "You pressed Cancel!";
  }


			//alert(id);
		//	alert(url);
			
			
		}
  
        </script>

    @stop

@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Media Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
 <link href="{{ asset('assets/css/toastr.css') }}" rel="stylesheet" type="text/css"/>


<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>

@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Media Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Media Manager</li>
            <li class="active">Media</li>
        </ol>
    </section>
    <div id="ajaxResponse"></div>
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Media List
                    </h4>
                    <div class="pull-right">
                    <a href="{{ route('create/media') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> create</a>
                    </div>
                </div>

                      
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
						<tr class="filters">
							<th>ID</th>
							<th>Folder Path</th>
							<th>Image</th>
							<th>Folder Name</th>
							<th>Created Date</th>
							<th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    <!-- row-->
    </section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/default/js/clipboard.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

    <script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                                aaSorting : [[0, 'desc']],

                ajax: '{!! route('admin.media.data') !!}',
                columns: [
					{ data: 'id', name: 'id'},
					{ data: 'folder_path', name: 'folder_path',render:function(data,type,row,meta){ return copybutton(data,type,row,meta)} },
					{ data: 'image', name: 'image',render:function(data,type,row,meta){ return displaybimage(data,type,row,meta)} },
					{ data: 'folder_name', name: 'folder_name' },
					{ data: 'add_date', name: 'created_at' },

					{ data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });
        
          function displaybimage(data,type,row,meta)
         {
			if(data)
				{
			if(row['mime_type'] == 'jpg' || row['mime_type'] == 'jpeg' || row['mime_type'] == 'gif' || row['mime_type'] == 'png' || row['mime_type'] == 'bmp' )
			{
				
				   var banner='{{ URL::to(App\Helpers\Thumbnail::image("/media/","200","95","ff=ffffff")) }}/'+row['folder_path']+'/'+data;

					var str='<img src="'+banner+'" />';

			}
			if(row['mime_type'] == 'pdf')
			{
				
				   var banner='{{ URL::to(App\Helpers\Thumbnail::image("/media/","200","95","ff=ffffff")) }}/fileextensions/pdf.jpeg';

					var str='<img src="'+banner+'" />';
				
			}
			if(row['mime_type'] == 'xlsx')
			{
		
				   var banner='{{ URL::to(App\Helpers\Thumbnail::image("/media/","200","95","ff=ffffff")) }}/fileextensions/xlsx.jpeg';

					var str='<img src="'+banner+'" />';
				
			}
			if(row['mime_type'] == 'doc')
			{
		
				   var banner='{{ URL::to(App\Helpers\Thumbnail::image("/media/","200","95","ff=ffffff")) }}/fileextensions/doc.jpeg';

					var str='<img src="'+banner+'" />';
				
			}
			if(row['mime_type'] == 'docx')
			{
		
				   var banner='{{ URL::to(App\Helpers\Thumbnail::image("/media/","200","95","ff=ffffff")) }}/fileextensions/docx.jpeg';

					var str='<img src="'+banner+'" />';
				
			}
			if(row['mime_type'] == 'xls')
			{
		
				   var banner='{{ URL::to(App\Helpers\Thumbnail::image("/media/","200","95","ff=ffffff")) }}/fileextensions/xls.jpeg';

					var str='<img src="'+banner+'" />';
				
			}
		}
				else
				var str='No image found';
          return str;
		}

     function copybutton(data,type,row,meta)
         {
			if(data)
			{
				var destinationPath='{{ URL::to('/uploads/media')  }}/'+data+'/'+row['image'];
				
				if(row['mime_type'] == 'jpg' || row['mime_type'] == 'jpeg' || row['mime_type'] == 'gif' || row['mime_type'] == 'png' || row['mime_type'] == 'bmp' )
				{
					var str =	'<p id="content_'+row['id']+'">'+destinationPath+'</p><button class="btn btn-success copy-text" data-clipboard-target="#content_'+row['id']+'" >Copy Url</button><a href="'+destinationPath+'" class="btn btn-success" target="_blank" >Open</a>'
				}
				else
				{
					var viewpath='{{ URL::to('admin/media/view-files')  }}/'+row['id'];
					var str =	'<p id="content_'+row['id']+'">'+destinationPath+'</p><button class="btn btn-success copy-text" data-clipboard-target="#content_'+row['id']+'" >Copy Url</button><a href="'+viewpath+'" class="btn btn-success" target="_blank" >Open</a>'
				}
			}
				else
				var str='No Path found';
          return str;
		}
		
			
$(function(){
  new Clipboard('.copy-text');
});
	  </script>
  <script>
  $(document).ready(function(){
	   
		   
	});	   
 		   
		   
  
 </script>  
@stop

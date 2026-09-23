@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
   Product Gallery Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

   <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">

<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Product Gallery Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Product Gallery Manager</li>
            <li class="active">Gallery List</li>
        </ol>
    </section>
    <div id="ajaxResponse"></div>
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
             <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Product Gallery List
                    </h4>
                    <div class="pull-right">
						  <a href="{{ route('create.gallery') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Add Image</a>
                    </div>
                </div>
                <div class="panel-body">
                @if (Session::has('success'))
                   <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>{{ Session::get('success') }}</strong>
                  </div>
                  
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                             <th style="width:0%;">Id</th>
                        <th style="width:30%;">Product Title</th>
                        <th style="width:25%;">Image</th>
                        <th style="width:15%;">Create Date</th>
                        <th style="width:15%;">Display Order</th>
                        <th style="width:15%;">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>    <!-- row-->
    </section>



@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
    <script>
       $(function() {
		    var routes= "{{ route('admin.gallery.data') }}";
            var table_blog = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: routes,
                columns: [
                    { data: 'id', name: 'id',visible:false },
					{ data: 'poduct_title', name: 'poduct_title' },
					{ data: 'image', name: 'image', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'add_date', name: 'created_at' },
                    { data: 'display_order', name: 'display_order' },
					{ data: 'actions', name: 'actions', orderable: false, searchable: true }
                ],
                 columnDefs: [{
                  "render": function ( data, type, row ) {
        						if(row["type"]=='Vedio')
        						return row["vedio"];
        						else
        						return data;
        					},
        					"targets": 1
        				}
						
              ]
            });
            table_blog.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
	 
		
	   function displayimage(data,type,row,meta)
        {
      
			var imageurl='{{ URL::to(App\Helpers\Thumbnail::image("/gallery/","150","65","cf")) }}/'+data;
			var str='<img src="'+imageurl+'" />'; 
	
		 return str;         
        
        } 
		function wordTrim(data,type,row,meta) {
			 var str=data;
    var res = str.substr(0,42);
              if(str.length>42)
                res=res+"..";
              return res;
			}
		
	 });
    
    

		function changedateformate(data,type,row,meta)
		{
			return data;

		}
    </script>
<script>
$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>

@stop

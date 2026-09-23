@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Branch Stores Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Branch Stores Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Branch Stores Manager</li>
            <li class="active">Branch Stores List</li>
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
                        Branch Stores List
                    </h4>
                    <div class="pull-right">
						  <a href="{{ URL::to('admin/branch_stores/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Create</a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Create Date </th>
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
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
     <script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.branch_stores.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'branch_name', name: 'branch_name', render:function(data,type,row,meta){ return title(data,type,row,meta)}},
                    { data: 'branch_image', name: 'branch_image', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'add_date', name: 'add_date' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

		function title(data,type,row,meta)
		{
			if(data)
			return data;
			else
			return 'Not Available';
		}
		
		function displayimage(data,type,row,meta)
        {
			 if(data){
			  var branchimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/branch/","200","65","ff=ffffff")) }}/'+data;
			  var str='<img src="'+branchimageurl+'" />';
			 }
			 else{
				   var str='No image found';
				 }

			  return str;
        }
    </script>
<script>
$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>

@stop

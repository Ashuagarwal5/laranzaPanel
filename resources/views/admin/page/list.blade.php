@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Static Page Manager::CRM
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
        <h1>Static Page Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Static Page Manager</li>
            <li class="active">Static Page List</li>
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
                        Static Page List
                    </h4>
                    <div class="pull-right">
						  <a href="{{ URL::to('cpmin/static_pages/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Create</a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>Sr. No.</th>
                            <th>Title</th>
                            <th>Slug</th>
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
                ajax: '{!! route('admin.page.data') !!}',
                columns: [
                    { data: 'page_id', name: 'page_id', searchable: false, render: function (data, type, row, meta) { return meta.settings._iDisplayStart + meta.row + 1; } },
                    { data: 'page_title', name: 'page_title' },
                    { data: 'slug', name: 'slug' },
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

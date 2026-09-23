@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Admin Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Admin Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Admin Manager</li>
            <li class="active">Admin Manager</li>
        </ol>
    </section>

    <!-- Main content -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <section class="content paddingleft_right15">
        <div class="row">
			
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Admin Manager List
                    </h4>
                    <div class="pull-right">
						  <a href="{{ URL::to('cpmin/admin_manager/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Create</a>
                    </div>
                </div>

                  
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>Sr. No.</th>
                            <th>Manager Name</th>
                             <th>Parent Manager</th>
                             <th><center>Page Link</center> </th>
                               <th>Display Order</th>
                              <th>Class Name</th>
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
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script>
		
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin_manager.data') !!}',
                columns: [
					{ data: 'mng_id', name: 'mng_id', searchable: false, render: function (data, type, row, meta) { return meta.settings._iDisplayStart + meta.row + 1; } },
					{ data: 'manager_name', name: 'manager_name' },

					{ data: 'parent_id', name: 'parent_id' },

					
					{ data: 'page_link', name: 'page_link' },
					{ data: 'display_order', name: 'display_order' },
					{ data: 'class_name', name: 'class_name' },
					{ data: 'add_date', name: 'add_date' },
					{ data: 'actions', name: 'actions', orderable: true, searchable: true }
                ],
                
				
              
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });
        
        
    </script>
   
   
@stop

@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
   Footer Menus Manager::CRM
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
        <h1>Footer Menus Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Footer Menus Manager</li>
            <li class="active">Footer Menus List</li>
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
                        Footer Menus List
                    </h4>
                    <div class="pull-right">
						  <a href="{{ URL::to('admin/footer/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Create</a>
                    </div>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                         <tr align="right">
                        <th style="width:5%;">Id</th>
                        <th style="width:15%;">Title</th>
                        <th style="width:20%;">Link</th>
						<th style="width:15%;">Order</th>
						<th style="width:15%;">Location</th>
                        <th style="width:20%;">Create Date</th>
                        <th style="width:20%;">Action</th>
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
		    var routes= "{{ route('admin.footer.data') }}";
            var table_blog = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: routes,
                columns: [
                    { data: 'ft_id', name: 'ft_id',visible:false },
                    { data: 'link_showing_name', name: 'link_showing_name' },
                    { data: 'link_address', name: 'link_address' },
                    { data: 'shownig_order', name: 'shownig_order' },
                    { data: 'link_showing_in_column', name: 'link_showing_in_column' },
                    { data: 'add_date', name: 'created_at' },
					{ data: 'actions', name: 'actions', orderable: false, searchable: true }
                ],
            });
            table_news.on( 'draw', function () {
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

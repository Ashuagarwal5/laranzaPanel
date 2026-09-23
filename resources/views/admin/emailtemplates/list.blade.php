@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Email Templates Manager::CRM
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
        <h1>Email Templates Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Email Templates Manager</li>
            <li class="active">Email Templates List</li>
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
                        Email Templates List
                    </h4>
                    
                </div>
                <div class="panel-body">
					<div class="col-sm-12">
					@include('admin.notifications')
					</div>
                    <table class="table table-bordered " id="table1">
                        <thead>
						  <tr align="right">
							<th style="width:25%;">Id</th>
							<th style="width:25%;">Title</th>
							<th style="width:25%;">Subject</th>
							<th style="width:25%;">Actions</th>
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
<script>
var routes = "{{ route('admin.emailtemplate.data') }}"; 
</script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
	<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
   <script type="text/javascript">
		var table_emailtemp = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: routes,
                columns: [
                    { data: 'em_tm_id', name: 'em_tm_id' },
                    { data: 'title', name: 'title' },
                    { data: 'subject', name: 'subject' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
            table_emailtemp.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            });	
      $(document).ready(function(){
      	//initialize the javascript
      	App.init();
      	App.formElements();
      	App.dataTables();
      });
    </script>
<script>
$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>


@stop

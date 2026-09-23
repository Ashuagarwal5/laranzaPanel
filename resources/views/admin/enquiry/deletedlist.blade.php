@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')Enquiries Manager @parent @stop
{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
	<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop
@section('content')
      <div class="be-content">
        <div class="page-head">
          <h2 class="page-head-title">Enquiry Manager</h2>
          <ol class="breadcrumb page-head-nav">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li><a href="{{route('admin.enquiry')}}">Enquiry Manager</a></li>
            <li class="active">Deleted Enquiries List</li>
          </ol>
        </div>
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-table">
                <div class="panel-heading">Deleted Enquiry Manager
                </div>
                <div class="panel-border-color panel-border-color-primary "></div>
                <div class="panel-body">
					@include('admin.notifications')
				<div class="col-sm-12">
				</div>
               <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                             <th>ID</th>
                            <th>Fulll name</th>
                            <th>Email</th>
                            <th>Subject</th>
                           <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div id="ajaxResponse"></div>
@stop
@section('footer_scripts')
<script>
var routes = "{{ route('admin.enquiry.deleteddata') }}";
</script>
    <script src="{{ asset('assets/admin/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app-tables-datatables.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      	//initialize the javascript
      	App.init();
      	App.formElements();
      	App.dataTables();
      });
    </script>
    <script>
        $(function() {

        });
		function changedateformate(data,type,row,meta)
		{
			return data;
		}
    </script>
<script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                 aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.enquiry.deleteddata') !!}',
                columns: [
                    { data: 'enq_id', name: 'enq_id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'subject', name: 'subject' },
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
    </script>
  <script>
	function restorerecord(pageid){
		$('.restorepage').click(function(){
			$.ajax(
					{
						url: '{{ URL::to('admin/faq/restorepage') }}/' +pageid,
						type: 'GET',
						dataType: "text",
						data: {
						 '_token': $('input[name=_token]').val(),
						},
						success:function(response){

							location.reload();
						}
					});
		});
	}
 </script>
@stop

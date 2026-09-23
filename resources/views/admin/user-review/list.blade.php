@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
   Users Review Manager::CRM
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

<style>

.btn-default{
	
	border: 1px solid #ddd;
	
	}
</style>


@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Users Review Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Users Review Manager</li>
            <li class="active">Users Review List</li>
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
                        Users Review List
                    </h4>
                  </div>
                  <div class="panel-body">
					        @include('admin.notifications')
					        
                  <div class="table-responsive">
                    <table class="table table-bordered " id="table_news">
                      <thead>
                        <tr class="filters">
                          <th>Id</th>
  						            <th > User Name</th>
                          <th > Product</th>
                          <th > Rating</th>
                          <th > Status</th>
                          <th>Create Date</th>
                          
                          <th >Actions</th>
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
     <script type="text/javascript">
	 $(function() {
		    var routes= "{{route('admin.review-data')}}";
            var table_news = $('#table_news').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: routes,
                columns: [
                    { data: 'id', name: 'id',visible:true },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'product_title', name: 'product_title' },
                    { data: 'rating', name: 'rating' },
                    { data: 'status', name: 'status' },
                    { data: 'add_date', name: 'created_at' },
					{ data: 'actions', name: 'actions', orderable: false, searchable: true }
                ],
            });
            table_news.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
                
                // enable_tooltip();
            } );	
              
	 });
	
    </script>
<script>

</script>

@stop

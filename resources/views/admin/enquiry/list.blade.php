@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
   {{ ucwords(str_replace('-',' ',$request_url))}} Manager::CRM
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
        <h1>{{ ucwords(str_replace('-',' ',$request_url))}} Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>{{ ucwords(str_replace('-',' ',$request_url))}} Manager</li>
            <li class="active">{{ ucwords(str_replace('-',' ',$request_url))}} List</li>
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
                        {{ ucwords(str_replace('-',' ',$request_url))}} List
                    </h4>
                    <div class="pull-right">
                    </div>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table-enquiry">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                        <th>Full name</th>
                        <th>Email</th>
						<!-- <th>Subject</th> -->
						<!-- <th>Reply Status</th> -->
                        <th>Create Date</th>
                        <th>Action</th>
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
		
		        var request_url= "{{ $request_url }}";
				 var routes= "{{ route('admin.enquiry.data') }}"+'?request_url='+request_url;
				 
				var table_enquiry = $('#table-enquiry').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: routes,
				
                    columns: [
                    { data: 'enq_id', name: 'enq_id',visible: false  },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    // { data: 'subject', name: 'subject' },
                    // { data: 'totalreply', name: 'totalreply',render:function(data,type,row,meta){ 
                    //return displaytext(data,type,row,meta)} , orderable: false, searchable: false },
                    { data: 'add_date', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ],
            });
            table_enquiry.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
	function displaytext(data,type,row,meta)
        {
         if(row.totalreply > 0){
           // alert(row.event_id);
             var str='Replied('+row.totalreply+' times)';
         }
         else{
               var str='No Replied';
             }
          return str;
        }
    </script>
  
  
  


<script>
$(document).ready(function(){
	//toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>

@stop

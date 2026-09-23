@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
{{ $manager_name }} Manager::CRM
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
    <h1>{{ $manager_name }} Manager</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>{{ $manager_name }} Manager</li>
        <li class="active">Pending Redemption Request List</li>
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
                All {{ $manager_name }} List
            </h4>
           <!--  <a href="" class="btn btn-danger pull-right export_button">Export Data</a> -->
        </div>
        <div class="panel-body">
            <div class="table-responsive">




                <table class="table table-bordered " id="table_news">
                    <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>Reward Points</th>
                            <th>Add Date </th>
                            <th>Actions</th>
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
      var route= "{{ route('admin.redeem.data') }}";	
      var route_for_export="{{route('export.redeem.data')}}";
      $('.export_button').attr('href',route_for_export);
      
      var table = $('#table_news').DataTable({
       	buttons: [ 'copy', 'csv', 'excel' ],
        processing: true,
        destroy:true,
        serverSide: true,
        aaSorting : [[0, 'desc']],
        ajax: route,
        columns: [
        { data: 'id', name: 'id' },
        { data: 'user_name', name: 'user_name', render:function(data,type,row,meta){ return userinfo(data,type,row,meta)} },
        { data: 'user_mobile', name: 'user_mobile' },        
        { data: 'point', name: 'point' },
        { data: 'add_date', name: 'add_date' },
        { data: 'actions', name: 'actions', orderable: true, searchable: true }
        ]
    });
    
   table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    } );




function userinfo(data,type,row,meta)
{
	   if(data)
	   {
	        var str='<a href="{{ route("admin.user") }}/show/'+ row.user_id +'" data-toggle="modal" data-target="#modal-email"> '+ data +' </a>';
	   }
	   else
	   {
		   var str='<a href=""{{ route("admin.user") }}/show/'+ row.user_id +'" data-toggle="modal" data-target="#modal-email"> Name Not Specified </a>';
		   
	   }
       return str;
 }




  });

</script>


@stop

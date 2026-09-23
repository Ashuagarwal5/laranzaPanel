@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
Point Transaction Manager::CRM
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<style>
#table1 tbody td:nth-child(1){
    text-transform: capitalize;
}
</style>
@stop
{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Bonus Points Setting</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Bonus Points Setting</li>
        <li class="active">Bonus Points List</li>
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
            Bonus Points List
            </h4>
           <!-- <a href="{{ route('export.wallet-transaction') }}" class="btn btn-danger pull-right export_btn" title="">Export Data</a> -->
        </div>
        <div class="panel-body">
       
            <div class="table-responsive">
                
    <table class="table table-bordered " id="table1" style="margin-top: 10px;">
        <thead>
            <tr class="filters">
               <th>Sr. No.</th>
               <th>Total Points Earned</th>
               <th>Points From</th>
               <th>Points To</th>
               <th>Bonus Reward Points </th>
               <th>Create Date</th>
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
<script src="{{ asset('assets/admin/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script>

$(function set_table() {
    var routes = "{{ route('admin.bonus-points-setting.data') }}";
    var table_blog = $('#table1').DataTable({
        processing: true,
        destroy: true,
        serverSide: true,
        aaSorting: [[0, 'asc']],
        ajax: routes,
        
        columns: [
            { data: 'id', name: 'id', searchable: false, render: function (data, type, row, meta) { return meta.settings._iDisplayStart + meta.row + 1; } },
            { data: 'qualification_points', name: 'qualification_points' },
            { data: 'points_from', name: 'points_from', visible: true },
            { data: 'points_to', name: 'points_to', visible: true },
            { data: 'bonus_ponits', name: 'bonus_ponits', visible: true },
            { data: 'add_date', name: 'add_date', visible: true }
            
            
        ]
        
    });

    table_blog.on('draw', function () {
        $('.livicon').each(function () {
            $(this).updateLivicon();
        });
    });
});


function changedateformate(data,type,row,meta)
{
 return data;
}
</script>
<script>
    $(document).ready(function(){
       // toastr[response.msgType](response.msg, response.msgHead);
   });
</script>
@stop

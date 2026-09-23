@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
Products Manager::CRM
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop
{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>History Bulk Manager</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>History Bulk QR Codes Manager</li>
        <li class="active">History Bulk QR List</li>
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
                All History Bulk QR Codes List
            </h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
               
        <table class="table table-bordered " id="table_news">
            <thead>
                <tr class="filters">
                    <th>Sr. No.</th>
                    <th>QR Code Count</th>
                    <th>Date </th>
                    <th>IP </th>
                    <th>By </th>
                    <th>Remark</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$item->bulk_qr_code}}</td>
                    <td>{{$item->add_date}}</td>
                    <td>{{$item->ip}}</td>
                    <td>{{$item->by}}</td>
                    <td>{{$item->remark}}</td>
                    @if ($item->status == "Completed")
                    <td>{{$item->status}}</td>
                    @else
                    <td>Processing.....</td>
                    @endif
                    <td>
                    @if ($item->status == "Completed")
                    <a class="delval btn btn-xs btn-info" title="Download QR Code" href="{{route('products.history_bulk_download',['id' => $item->id])}}"><i class="fa fa-download"></i></a>
                    <a class="delval btn btn-xs btn-success" title="Download Excel File" href="{{route('admin.products.exportExcel',['id' => $item->id])}}"><i class="fa fa-file-excel-o"></i></a>
                    {{-- @if ($item->check_type_value == "No") --}}

                    <a class="delval btn btn-xs btn-primary" title="Edit Bulk QR Code" href="{{route('products.bulk_create',['id' => $item->id])}}"><i class="fa fa-edit"></i></a>
                    <a data-toggle="modal" data-target="#modal-regular" href="{{route('confirm-delete/bulk',['id' => $item->id])}}" class="delval btn btn-xs btn-danger"  title="Delete QR Code"><i class="fa fa-trash"></i></a>
                    <a href="{{route('print-qrcode/bulk',['id' => $item->id])}}" target="_blank" class="delval btn btn-xs btn-warning"  title="Print All QR Code"><i class="fa fa-print"></i></a>
                    {{-- @endif --}}
                    @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</section>
@stop
<!-- {{-- page level scripts --}} -->
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script type="text/javascript"> 
   $(document).ready( function () {
    $('#table_news').DataTable({
        order: [],
    });
} );
  
  function measurements(data,type,row,meta)
  {
   if(row.density==null)
       row.density = 'NA';
   if(row.thickness==null)
       row.thickness = 'NA';
   if(row.length==null)
       row.length = 'NA';
   if(row.width==null)
       row.width = 'NA';
   if(row.varient==null)
       row.varient = 'NA';
   var str='Density: '+row.density+'<br/>'+'Length: '+row.length+'&nbsp;&nbsp;'+'Width: '+row.width+'<br/>'+'Thickness: '+row.thickness+'&nbsp;&nbsp;'+'Varient: '+row.varient+'<br/>';
   return str;
}
</script>

@stop

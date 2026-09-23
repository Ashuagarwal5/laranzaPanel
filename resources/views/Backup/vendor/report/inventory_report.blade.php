@extends('vendor/header')

{{-- Page title --}}
@section('title')
Inventory Report
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/vendors/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">	

@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	<div class="container-fluid">
      <div class="row">

         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">
			  Inventory Report</h1>

<div class="panel">
<div class="panel-heading">



          <div class="clr"></div>
</div>
<div class="panel-body">
<div class="account clearfix" style="margin-top:10px;">


 <form class="form-inline form-center" action="" method="get">
 
 
 <div class="input-daterange input-group" id="datepicker">
	  <span class="input-group-addon">From</span>
        <input required type="text" class="input-sm form-control" name="start" value="@if(isset($start)){{ $start}} @endif"/>
        <span class="input-group-addon">to</span>
        <input required type="text" class="input-sm form-control" name="end" value="@if(isset($end)){{ $end}} @endif" />
    </div>
    
    

  <button type="submit" class="btn btn-danger btn-ctmb">Submit</button>
</form> 
</div>
<div class="clr"></div>

<hr>
<br>
@if(isset($end) && isset($start))
<div class="account clearfix" style="margin-top:10px;">

 <div class="table-responsive">
	  
	  
	   <table class="table table-bordered table-striped" id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Sale Price</th>
                            <th>Add Date </th>
                            <th>Action </th>
                        </tr>
                        </thead>
                        <tbody>
							
							@foreach($products as $key  =>  $value)
							<tr>
							<td>{{ $value->id }}</td>
                            <td>{{ $value->product_title }}</td>
                            <td><img src='{{ URL::to(App\Helpers\Thumbnail::image("/products/$value->product_image","200","80","ff=ffffff")) }}' ></th>
                            <td>{{ $value->sale_price }} </td>
                            <td>{!! $value->product_description !!}</td>
                            <td>{{ $value->add_date }}  </td>
                            <td><a href="{{ route('seller.product.edit', $value->id ) }}" class="btn btn-default btn-xs purple popular" title="Edit">
			<i class="fa fa-edit "></i>
			</a>  </td>
							
							</tr>
							
							
							@endforeach
                        </tbody>
                    </table>
                
       





   <div class="clr"></div>
        </div>







</div>

@endif





</div>
        </div>
      </div>
    </div>



    <!-- //Container End -->
@stop

{{-- footer scripts --}}
@section('footer_scripts')

	<script  src="{{ asset('assets/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"  type="text/javascript"></script>

<script>
$('.input-daterange').datepicker({
    format: 'yyyy-mm-dd',
});
</script>
    <!-- page level js starts-->

	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

	
@stop

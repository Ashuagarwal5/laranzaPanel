@extends('vendor/header')

{{-- Page title --}}
@section('title')

@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	<div class="container-fluid">
      <div class="row">

         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Product List</h1>

<div class="panel">
<div class="panel-heading">


	<div class="pull-right">

          <a class="btn btn-success btn-sm" href="{{ route('seller.product.create') }}"><i aria-hidden="true" class="fa fa-upload"></i>
 Add Product</a>
          </div>
          <div class="clr"></div>
</div>
<div class="panel-body">
<div class="account clearfix" style="margin-top:10px;">


 <div class="table-responsive">
	 <table id="table" class="table table-bordered  table-striped">
		  <thead>
			  <tr>
				  <th>Id</th>
				  <th> Name</th>
				  <th>Price</th>
				  <th>Sale Price</th>
				  <th>Created Date</th>
				  <th>Auction</th>
				    </tr>
			</thead>

 <tbody>
	 @foreach($products as $key => $value)
	 <tr>
		  <th scope="row">{{ $value->pro_id}}</th>
		  <td>{{ $value->title}}</td>
		  <td>{{ $value->price}}</td>
		  <td>{{ $value->sale_price}}</td>
		   <td>{{date("d M Y",strtotime($value->pro_date))}}</td>
		    <td align="center"><a class="btn" href="#"><i aria-hidden="true" class="fa fa-pencil-square-o"></i>
</a><a class="btn" href="#"><i aria-hidden="true" class="fa fa-trash"></i>

</a></td>

</tr>
  @endforeach
   </tbody> </table>
   </div>

   <div class="clr"></div>
        </div>
</div>
</div>
        </div>
      </div>
    </div>



    <!-- //Container End -->
@stop

{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->

 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

<script>
$(document).ready(function() {
	$('#table').DataTable();
});
</script>
    <!--page level js ends-->

@stop

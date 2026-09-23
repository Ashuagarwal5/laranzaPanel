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
          <h1 class="page-header">Product List </h1>

<div class="panel">
<div class="panel-heading">


				<div class="col-sm-8"><h3 style="margin-top:5px;">Total Records: {{count($products)}}</h3></div>
				<div class=" col-sm-4"> <a class="btn btn-success btn-sm pull-right" href="{{ route('seller.product.create') }}"><i aria-hidden="true" class="fa fa-upload"></i>
				Add Product</a></div>

		
          <div class="clr"></div>
</div>
<div class="panel-body">
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
                            <td><img src='{{ URL::to(App\Helpers\Thumbnail::image("/products/$value->product_image","200","80","ff=ffffff")) }}' ></td>
                            <td> {{ App\WebsiteSetting::getCurrencyCode() }} {{ $value->sale_price }} </td>
                            <td>{{ $value->add_date }}  </td>
                            <td>
								<a href="{{ route('seller.product.edit', $value->id ) }}" class="btn btn-default btn-xs purple popular" title="Edit">
			<i class="fa fa-edit "></i>
			</a>  
			
			<a href="{{ route('seller.confirm-delete/product', $value->id ) }}" data-toggle="modal" data-target="#modal-regular" class="btn btn-danger btn-xs purple popular" title="Edit">
			<i class="fa fa-trash "></i>
			</a>  
			
			
			</td>
							
							</tr>
							
							
							@endforeach
							
							
							@if(count($products) <= 0)
							<tr style="height: 93px;">
								<td colspan="7">
								<center style="margin-top: 28px; font-size: 17px;">You haven’t added any product yet</center>
								</td>
							</tr>
							@endif
							
							
							
							
							
							
                        </tbody>
                    </table>
                
                
   
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


    <!--page level js ends-->

@stop

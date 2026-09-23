@extends('vendor/header')

{{-- Page title --}}
@section('title')
Sales Report
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
			  Sales Report</h1>

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
    
    
 <div class="form-group">
		<div class="input-group">
    <span for="exampleFormControlSelect1" class="input-group-addon">Order Status</span>
    <select class="form-control" id="exampleFormControlSelect1" name="order_type">
      <option value="all" @if(isset($order_type) && $order_type== 'all') selected="" @endif >All</option>
      <option value="pending-orders" @if(isset($order_type) && $order_type== 'pending-orders') selected="" @endif>Pending</option>
      <option value="completed-orders" @if(isset($order_type) && $order_type== 'completed-orders') selected="" @endif>Completed</option>
      <option value="failed-orders" @if(isset($order_type) && $order_type== 'failed-orders') selected="" @endif>Failed</option>
    </select>
    </div>
  </div>
    
    






  <button type="submit" class="btn btn-danger btn-ctmb">Submit</button>
</form> 
</div>
</div>
<div class="clr"></div>

<hr>
<br>
@if(isset($end) && isset($start))
<div class="account clearfix" style="margin-top:10px;">

 <div class="table-responsive">
	  <table class="table table-bordered " id="table1">
	
		  <thead>
			  <tr>
				<th>Sr. No.</th>
				<th>Order Id</th>
				<th>Buyer Info.</th>
				<th>Total Ordered Items</th>
				<th>Order Amount</th>
				<th>Order Date</th>
				<th>Shipment Status</th>
				<th>Payment Method</th>
				<th>Date</th>
				<th>Actions</th>
				</tr>
				    
			</thead>
			
			  <tbody>
				
							
                        </tbody>
</table>

   </div>

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

<?php  if(isset($start))
	   $start = $start;
	   else
	   $start = '';
	
	
	   if(isset($end))
	   $end = $end;
	   else
	   $end = '';
	
	
	 ?>
	
    <!-- page level js starts-->

	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>
	var url = "{!! route('seller.orders.data',$order_type) !!}"
	
	
	var start = "{{ $start }}";
	var end = "{{ $end }}";
	

	
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: "{!! route('seller.orders.data',$order_type) !!}"+'?start='+start+'&end='+end,
                columns: [
                    { data: 'order_id', name: 'order_id' },
                    { data: 'increment_id', name: 'increment_id' },
                    { data: 'full_name', name: 'full_name',render:function(data,type,row,meta){ return buyerinfo(data,type,row,meta)} },
                    { data: 'my_item_count', name: 'my_item_count',render:function(data,type,row,meta){ return iteminfo(data,type,row,meta)} },
                    { data: 'grand_total', name: 'grand_total' },
                    { data: 'order_date', name: 'order_date' }, 
                    { data: 'order_status', name: 'order_status',render:function(data,type,row,meta){ return uppercase(data,type,row,meta)} },
                  	{ data: 'payment_method', name: 'payment_method',render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
					{ data: 'add_date', name: 'add_date' }, 
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

		function iteminfo(data,type,row,meta)
		{
			if(data)
			{
				var userdataroute='{{ URL::to('/sellerpanel/orders/item_info')  }}/'+row['order_id'];

				var str= '<a href="'+userdataroute+'" data-toggle="modal" data-target="#modal-regular" style="text-decoration: underline !important;" target="_blank" >'+data +'</a>' ;
				return str;
			}
			else
			{
				return '-N/A-';
			}
		}

		function buyerinfo(data,type,row,meta)
		{
			if(data)
			{
				var userdataroute='{{ URL::to('/sellerpanel/orders/users/show')  }}/'+row['member_id'];

				var str= '<a href="'+userdataroute+'" data-toggle="modal" data-target="#modal-large" style="text-decoration: underline !important;" target="_blank" >'+data+'</a>' ;
				return str;
			}
			else
			{
				return '-N/A-';
			}
		}
		function sellerinfo(data,type,row,meta)
		{
			if(data)
			{
				var dataroute='{{ URL::to('/sellerpanel/orders/sellerinfo')  }}/'+row['seller_id'];

				var str= '<a href="'+dataroute+'" data-toggle="modal" data-target="#modal-large" style="text-decoration: underline !important;" target="_blank" >'+data+'</a>' ;
				return str;
				
			
			}
			else
			{
				return '-N/A-';
			}
		}
		function uppercase(data,type,row,meta)
		{
			if(data)
			{
				   return (data + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
					return $1.toUpperCase();
				});
				
				
			}
			else
			{
				return '-N/A-';
			}
		}
		
		   function displayimage(data,type,row,meta)
         {
			 
			if(data == 'Bank Transfer (Wire Transfer/Cheque/DD)')
			{
				var destinationPath='{{ URL::to('/uploads/bank_receipt')  }}/'+row['bank_receipt'];
				
			
					var str =	data+'<br><a href="'+destinationPath+'" style="text-decoration: underline !important;" target="_blank" >Open Receipt</a>'
			}
			else
			if(data == 'Pay using Paytm')
			{
				
			
					var str =	data+'<br><a href="javascript:" style="text-decoration: underline !important;cursor:text !important"  >'+row['paytm_transaction_id']+'</a>'
				
			}
			else
			var str= data;
				
          return str;
			 
			 
			 
		
		}
		
    </script>

	
@stop

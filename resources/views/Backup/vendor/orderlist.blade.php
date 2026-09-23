@extends('vendor/header')

{{-- Page title --}}
@section('title')
Orders
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<style>

.modal { overflow: auto !important; }
</style>

@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	<div class="container-fluid">
      <div class="row">

         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">
			  
			  @if($order_type == 'awaiting-shipmment-order')
			  {{ str_replace('-',' ', 'awaiting-delivery-order') }} List
			  @else
			  {{ str_replace('-',' ', $order_type) }} List
			  @endif
			  
			  
			  </h1>

		@include('notifications')



<div class="panel">
<div class="panel-heading">



          <div class="clr"></div>
</div>
<div class="panel-body">
<div class="account clearfix" style="margin-top:10px;">


 <div class="table-responsive">
	  <table class="table table-bordered " id="table1">
	
		  <thead>
			  <tr>
				<th>Serial No.</th>
				<th>Order Id</th>
				<th>Buyer Info.</th>
				<th>Total Ordered Items</th>
				<th>Delivery Status</th>
				<th>Order Amount</th>
				<th>Payment Method</th>
				<th>Date</th>
				<th>Actions</th>
			</tr>
				    
			</thead>
			
			  <tbody>
				</tbody>


   </div>

   <div class="clr"></div>
        </div>
</div>
</div>
        </div>
      </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="fail_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body">
       Are you sure to Fail/Decline this order ? 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       <a href="{"> <button type="button" class="btn btn-primary">Fail</button></a>
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
	var url = "{!! route('seller.orders.data',$order_type) !!}"
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: "{!! route('seller.orders.data',$order_type) !!}",
                columns: [
                    { data: 'order_id', name: 'order_id', render: function (data, type, row, meta) { return serialNo(data,type,row,meta)} },
                    { data: 'increment_id', name: 'increment_id' },
                    { data: 'full_name', name: 'full_name',render:function(data,type,row,meta){ return buyerinfo(data,type,row,meta)} },
                    { data: 'my_item_count', name: 'my_item_count',render:function(data,type,row,meta){ return iteminfo(data,type,row,meta)} },
                    { data: 'status', name: 'status',render:function(data,type,row,meta){ return uppercase(data,type,row,meta)} },
                    { data: 'seller_row_total', name: 'seller_row_total' },
                  	{ data: 'payment_method', name: 'payment_method',render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
					{ data: 'add_date', name: 'add_date' }, 
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            //~ table.on( 'draw', function () {
                //~ $('.livicon').each(function(){
                    //~ $(this).updateLivicon();
                //~ });
            //~ } );
        });
        
        function serialNo(data,type,row,meta)
		{
			return meta.row + meta.settings._iDisplayStart + 1;
			
		}

		function iteminfo(data,type,row,meta)
		{
			if(data)
			{
				var userdataroute='{{ URL::to('/sellerpanel/orders/item_info')  }}/'+row['order_id'];

				var str= '<a href="'+userdataroute+'" data-toggle="modal" data-target="#modal-large" style="text-decoration: underline !important;" target="_blank" >'+data +'</a>' ;
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
			if(data == 'completed')
			data = 'Order Delivered';
			
			if(data == 'order shipped')
			data = 'On the way';
			
			if(data == 'failed')
			data = 'Not applicable';
			
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
		
		function openModel(url, position) {
   $.get(url, function(data) {
     $('#' + position).find('.modal-content').html(data);
     return false;
   });
 }
 
 
 
		function openModelOther(url, position) {
			
			
	$('.modal').modal('hide');
	$('#modal-large').modal('show');
   $.get(url, function(data) {
     $('#' + position).find('.modal-content').html(data);
     return false;
   });
 }
		
    </script>

	
@stop

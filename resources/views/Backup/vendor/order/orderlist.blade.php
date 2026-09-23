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

@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	<div class="container-fluid">
      <div class="row">

         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Order List</h1>

<div class="panel">
<div class="panel-heading">


          <div class="clr"></div>
</div>
<div class="panel-body">
<div class="account clearfix" style="margin-top:10px;">


 <div class="table-responsive">
	 <table id="table1" class="table table-bordered  table-striped">
		  <thead>
			  <tr>
				    <th>OEDER ID</th>
                             <th>Name</th>
                            <th>Total</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Purchased On</th>
                            
				    </tr>
			</thead>

			<tbody>

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


 <script>
	    
        $(function() {
	        
	      
	        var slug='{{$slug}}';
	        
	       if(slug!=""){
			  var url='{!! route('seller.orders.statedata') !!}/'+ slug;	   
			
			}
			else{
				  var url='{!! route('seller.orders.data') !!}';
				
				}
	       
            var table = $('#table1').DataTable({
                 processing: true,
                serverSide: true,
               
                searchDelay:600,
                ajax: url,
                columns: [
					{ data: 'order_id', name: 'order.order_id',render:function(data,type,row,meta){ return orderdetail(data,type,row,meta)}},
					{ data: 'shipping_name', name: 'order_address.first_name' },
					{ data: 'item_total', name: 'item_total',render:function(data){return "₹ "+parseFloat(data).toFixed(2)} },
					{ data: 'method', name: 'order_payment.payment_method'},
					{ data: 'status', name: 'order.status'},
					{ data:"order_date",name: 'order.created_at',render:function (data){ return data}},
                   
                    
                ],
            });
            
            table.on( 'draw', function () {
                $('.livicon').each(function($data){
	               
                    $(this).updateLivicon();
                });
            } );
            
            
        });
        
        
         function orderdetail(data,type,row,meta)
      {
	      var orderdetailurl='{!! route('seller.orders.show') !!}/'+data;
	      var str='<a href="'+orderdetailurl+'">'+data+'</a>';
	      if(row.invoice_id)
	      {
		     str=str+'<br>'+row.invoice_id;
	      }


	      return str;
      }
        
    </script>
</script>
    <!--page level js ends-->

@stop

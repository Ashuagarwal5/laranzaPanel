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

    <section class="content-header">
        <h1>Orders Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Order  Manager</li>
            
            
            <li class="active">{{$submenu}} </li>
           
        </ol>
    </section>
      
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                      {{$submenu}} Orders List
                    </h4>
                    
                </div> 
                               
                      
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
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
            </div>
        </div>    <!-- row-->
    </section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    
    
    

    <script>
	    
        $(function() {
	        
	      
	        var slug='{{$slug}}';
	        
	       if(slug!=""){
			  var url='{!! route('admin.orders.statedata') !!}/'+ slug;	   
			
			}
			else{
				  var url='{!! route('admin.orders.data') !!}';
				
				}
	       
            var table = $('#table1').DataTable({
                 processing: true,
                serverSide: true,
               
                searchDelay:600,
                ajax: url,
                columns: [
                    { data: 'order_id', name: 'order.order_id',render:function(data,type,row,meta){ return orderdetail(data,type,row,meta)}},
                     { data: 'shipping_name', name: 'order_address.first_name' },
                    { data: 'grand_total', name: 'order.grand_total',render:function(data){return "₹ "+parseFloat(data).toFixed(2)} },
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
	      var orderdetailurl='{!! route('admin.orders.show') !!}/'+data;
	      var str='<a href="'+orderdetailurl+'">'+data+'</a>';
	      if(row.invoice_id)
	      {
		     str=str+'<br>'+row.invoice_id;
	      }


	      return str;
      }
        
    </script>
@stop

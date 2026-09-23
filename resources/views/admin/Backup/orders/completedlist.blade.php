@extends('admin.layouts.default')
@section('title')
    Orders
    @parent
@stop
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
    
    
@stop
@section('content')

    <section class="content-header">
        <h1>Completed Orders</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Orders</li>
            <li class="active">Completed Orders List</li>
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
                        Completed Orders List
                    </h4>
                    <div class="pull-right">
                    </div>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th> ID</th>
                            <th> Order ID</th>
                            <th> Buyer Info</th>
                            <th> Order Amount</th>
                            <th> Order Date</th>
                            <th>Payment Status </th>
                            <th>Payment Method </th>
                            <th>Status</th>
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
     
  
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.completed-order.data') !!}',
                 columns: [
                    { data: 'order_id', name: 'order_id', render: function (data, type, row, meta) { return serialNo(data,type,row,meta)} },
                    { data: 'order_id', name: 'order_id'},
                    { data: 'first_name', name: 'first_name',render:function(data,type,row,meta){ return buyerinfo(data,type,row,meta)} },
                    { data: 'grand_total', name: 'grand_total' },
                    { data: 'add_date', name: 'add_date' },
                    { data: 'payment_status', name: 'payment_status',render:function(data,type,row,meta){ return uppercase(data,type,row,meta)} },
                  	{ data: 'payment_method', name: 'payment_method' },
                  	{ data: 'order_status', name: 'order_status' },
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });
        
           function serialNo(data,type,row,meta)
		{
			return meta.row + meta.settings._iDisplayStart + 1;
			
		}
		

	function iteminfo(data,type,row,meta)
		{
			if(data)
			{
				var userdataroute='{{ URL::to('/admin/orders/item-info')  }}/'+row['order_id'];

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
				var userdataroute='{{ URL::to('/admin/users/show')  }}/'+row['member_id'];

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
				var dataroute='{{ URL::to('/admin/orders/sellerinfo')  }}/'+row['seller_id'];

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
			 if(data){
			  var storeitemimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/products/","200","80","ff=ffffff")) }}/'+data;
			  var str= row.product_name+'</br><img src="'+storeitemimageurl+'"  /></br>'+'Price : LBP '+row.price;
			 }
			 
			  return str;
        }
        
		
    </script>


@stop

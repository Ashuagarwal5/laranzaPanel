@extends('vendor/header')

{{-- Page title --}}
@section('title')
Inqury
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
          <h1 class="page-header">Inqury List</h1>

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
										<th>Inquiry ID</th>
										<th>Name</th>
										<th>Product Name</th>
										<th>Quantity</th>
										<th>Place</th>
										<th>Phone No</th>
										<th>Created At</th>
										<th>Action</th>
                            
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
	    var url="{{route('seller.orders-inquiry.data')}}";
    
        	$(function() {
			var table = $('#table1').DataTable({
				processing: true,
				serverSide: true,
				ajax: url,

					   columns: [
							{ data: 'pro_inq_id', name: 'pro_inq_id.order_id'},
							{ data: 'name', name: 'name' },
							{ data: 'title', name: 'title' },
							{ data: 'quantity', name: 'quantity' },
							{ data: 'place', name: 'place' },
							{ data: 'mobile_no', name: 'mobile_no'},
							{ data:"add_date",name: 'add_date'},
							{ data: 'actions', name: 'actions', orderable: true, searchable: true }
						],

				
			});
			table.on( 'draw', function () {
				$('.livicon').each(function(){
					$(this).updateLivicon();
				});
			} );
	});
    </script>
</script>
    <!--page level js ends-->

@stop

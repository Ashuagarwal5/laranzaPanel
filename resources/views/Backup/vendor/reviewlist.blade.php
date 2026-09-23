@extends('vendor/header')

{{-- Page title --}}
@section('title')
Rating & Review
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
          <h1 class="page-header">
			  Review List</h1>

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
				<th>Id</th>
				<th>User Name</th>
				<th> Product Name</th>
				<th>Rate</th>
				<th>Review</th>
				</tr>
				    
			</thead>
			
			  <tbody>
					<tr>
					<td colspan="7"><center>No Record Found</center></td>
					</tr>		
							
                        </tbody>


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
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: "{!! route('seller.reviewdata') !!}",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'product_title', name: 'product_title' },
                    { data: 'rate', name: 'rate' },
                    { data: 'review', name: 'review' },

                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

	
	
		  
    </script>


@stop

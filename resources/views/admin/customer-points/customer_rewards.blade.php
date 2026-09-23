@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
   
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Customer Rewards </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Customer Rewards </li>
            <li class="active">Customer Rewards  List</li>
        </ol>
    </section>
   
   
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
             <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Customer Rewards  List
                    </h4>
                    
                    <a href="{{route('admin.customer.rewards.add')}}" class="pull-right btn btn-default"><i class="fa fa-plus"></i> Add Reward</a>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Reward(Gift) </th>
                            <th>Image</th>
                            
                            <th>Qualification Points</th>
                            
                            <th>Total Redemptions </th>
                            <th>Action </th>
                            
                        </tr>
                        </thead>
                        <tbody>
							@foreach($data as $key  =>  $value)
							<tr>
							<td >{{ $key+1 }}</td>
							<td >{{ $value->reward }}</td>
							<td ><img src="{{URL::to(App\Helpers\Thumbnail::image("customer-reward/$value->image","200","100","ff=ffffff")) }}"></td>
							
							<td >{{ $value->qualification_points }}</td>
							
							<td >{{ $value->total_red }}</td>
							<td >
								
				<a href="{{ route('admin.customer.rewards.edit',$value->id) }}" class="delval btn btn-xs btn-warning"  title="Edit Record">
				<i class="fa fa-pencil"></i>
				Edit
				</a>
				
				
				<a data-toggle="modal" data-target="#modal-regular" href="{{ route('confirm-delete/reward_manager',$value->id) }}" class="delval btn btn-xs btn-danger"  title="Delete Record">
				<i class="fa fa-trash"></i>
				Delete
				</a>
				
				
				</td>
							

							
							</tr>
							
							@endforeach
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
             
        });

	
    </script>
<script>
	$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>

@stop

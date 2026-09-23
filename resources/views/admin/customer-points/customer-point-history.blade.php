@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/select2.min.css') }}">
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
    <h1>Customer Transactions History</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Customer Point History </li>
        <li class="active">Customer Point History List</li>
    </ol>
</section>


<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
     <div class="panel panel-primary ">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
           Customer Transactions History List
       </h4>
       <!-- <a href="{{ route('admin.customer.point.export')}}" class="btn btn-danger pull-right">Export Data</a> -->
   </div>
   <div class="panel-body">
   

                  <div class="row">
                    <div class="col-xs-12 col-sm-12">
                      <div class="form-group">
                        <label for="user">Select Customer :</label>
                        <div class="input-group">
                            <select class="form-control" name="user" id="user" style="width: 100%;">
                                <option value="">All Records</option>
                                @if(!empty($users))
                                @foreach($users as $key=>$value)
                                @if(!empty($value->full_name))
                                <option value="{{$value->id}}" @if(isset($userid) && $userid == $value->id) selected="selected" @endif>{{$value->full_name}} - {{$value->mobileno}}</option>
                                @endif
                                @endforeach
                                @endif
                            </select>
                            <span class="input-group-addon">
                                <span class=""></span>
                            </span>
                            </div>
                        </div>
                    </div>

                  </div>
                  <div class="row">
		                <div class="col-xs-12 col-sm-12" style="">
			                	<center>
				                	<a href="{{ route('admin.customer.point.history') }}">Reset Search</a>
			                	</center>
						</div>
                  </div>
             

   <hr/>    
    
    <div class="table-responsive">
        <table class="table table-bordered " id="table1">
            <thead>
                <tr>
                    <th>User Id</th>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Total Earned Points</th>
                    <th>Total Redeemed Points</th>
                    <th>Balance Points </th>
                </tr>
            </thead>
            <tbody>
            
            @if(sizeof($history) > 0 && isset($history))
            @foreach($history as $key => $value)
				<tr role="row" class="odd">
				   <td class="sorting_1">{{ $value->id }}</td>
				   <td><a href="{{ route('user.show',$value->id) }}" data-toggle="modal" data-target="#modal-email"> {{ $value->full_name }}</a></td>
				   
				   </td>
				   <td>{{ $value->mobileno }}</td>
				   <td>{{ $value->city }}</td>
				   <td>{{ $value->state }}</td>
				   <td><a href="{{ route('user.rewards', [$value->id,'Earn'])}}" target="_blank">{{ $value->total_earned }}</a></td>
				   <td><a href="{{ route('user.rewards', [$value->id,'Redeem'])}}" target="_blank">{{ (float) $value->total_redeemed }}</a></td>
				   <td><a href="{{ route('user.rewards', [$value->id,'All'])}}" target="_blank">{{ $value->total_earned-$value->total_redeemed }}</a></td>
				</tr>
			@endforeach	
			@else
			<tr>
				<td colspan="8">
					<div class="col-sm-12 text-center">
					  No Record Found
					 </div>
				</td>
			</tr>
			@endif
			
			@if(sizeof($history) > 9 )
			<tr>
				<td colspan="8">
					<div class="col-sm-12 text-center">
					  <nav aria-label="Page navigation example ">
					   {!!  $history->render() !!}
					  </nav>
					 </div>
				</td>
			</tr>
			@endif
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
<script src="{{ asset('assets/admin/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>


<script>
//Call JQuery funciton work on change of select box
    $(function()
    {
        $('#user').select2();

	});
function redirectfilter()
{

	window.location.href = '';
	
}

$('#user').on('change', function()
{
	var userid = this.value;
    window.location.href = "{{ URL::to('cpmin/customer-point-history') }}/"+userid;
});


</script>



@stop

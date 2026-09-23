<div class="modal-header modal-header-lk">
	<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
	<img src="https://user1.codespur.us/khtravels/uploads/200/50/f/logo/CiIOLEcfIS.png" class="side-logo">
	<h4 class="modal-title modal-title-2">{{ $cabProvider->name }} - Assigning Detail</h4>
	<div class="clearfix"></div>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<div class="tab-content">
				<div id="home" class="tab-pane fade in active">
					<table class="table table-striped table-bordered table-hover"  boid="sample_1">
						<thead>
							<tr>
								<th>SN.</th>
								<th>Tour Info</th>
								<th>Cab Date</th>
								<th>Cabs & Driver Info</th>
								<th>Cabs Tour Plan</th>
								<th>Expense Amount</th>
							</tr>
							
							




							@if(isset($assignedTours))
							@if(count($assignedTours)>0)
							@foreach($assignedTours as $key=>$value)
							<tr>
								<td width="5%">{!!$key+1!!}</td>
								<td width="25%">{{ $value->tour_title }} <br/> {{ $value->tour_type }} Tour, Tour Id: {{ $value->tour_id }} <br/>Starting Date: {{ date('d/m/Y',strtotime($value->tour_start_date)) }} 
								@if(isset($value->tour_booking_id))Booking Id: {{ $value->tour_booking_id }}@endif
								
								</td>
								<td width="20%">From: {{ date('d/m/Y',strtotime($value->cab_start_date)) }} <br/> To: {{ date('d/m/Y',strtotime($value->cab_end_date)) }}</td>
								<td width="20%">
								Total Cabs: {{ $value->total_cabs }} <br/>
								{{ $value->cabs_info }}</td>
								<td width="25%"> {{ $value->cabs_plan }}
								<td width="10%">Rs. {{ $value->cab_paid_amount }}</td>
							</tr>
							@endforeach
							@else  
						</thead>
						<tbody>
							<tr>
								<tbody>
									<tr>
										<td colspan="6">
											<h3 style="margin-top:0px" class="text-center">No Records Found !</h3>
										</td>
									</tr>
								</tbody>
								@endif
								@endif
							</table>
						</div>

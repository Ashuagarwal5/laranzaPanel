<style>
	.panel-heading-nav {
  border-bottom: 0;
  padding: 10px 0 0;
}

.panel-heading-nav .nav {
  padding-left: 10px;
  padding-right: 10px;
}
</style>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">{{ $approved_customers }} Detail</h4>
</div>
<div class="modal-body">
		<div class="panel panel-default">
		  <div class="panel-heading panel-heading-nav">
			<ul class="nav nav-tabs">
			  <li role="presentation" class="active">
				<a href="#one" aria-controls="one" role="tab" data-toggle="tab">Basic Details</a>
			  </li>
			  <li role="presentation">
				<a href="#two" aria-controls="two" role="tab" data-toggle="tab">Documents Details</a>
			  </li>
			  <li role="presentation">
				<a href="#three" aria-controls="three" role="tab" data-toggle="tab">Bank Details</a>
			  </li>
			</ul>
		  </div>
		  <div class="panel-body">
			<div class="tab-content">
			  <div role="tabpanel" class="tab-pane fade in active" id="one">
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
								<tr>
									<th>Field Name</th>
									<th>Data</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Id</td>
									<td> @if(isset($user->id)) {{ $user->id }} @else -NA- @endif </td>
								</tr>
								<tr>
									<td>Full Name</td>
									<td> @if(isset($user->full_name)) {{ $user->full_name }} @else -NA- @endif </td>
								</tr>
								{{-- <tr>
									<td>User Type</td>
									<td> @if(isset($user->user_type) && ($user->user_type)!='') @if($user->user_type=='User') User @else {{ $user->user_type}} @endif @else -Not Specified- @endif</td>
								</tr> --}}
								@if(isset($user->dealer_id) && ($user->dealer_id)!='')
								<tr>
									<td>Dealer</td>
									<td> {{ $user->dealer_name }}</td>
								</tr>
								@endif
								<!-- <tr>
									<td>First Name</td>
									<td> @if(isset($user->dealer_name)) {{ $user->dealer_name }} @else -NA- @endif </td>
								</tr> -->
								<!-- <tr>
									<td>Last Name</td>
									<td> @if(isset($user->last_name)) {{ $user->last_name }} @else -NA- @endif </td>
								</tr> -->
								<tr>
									<td> Email</td>
									<td> @if(isset($user->email)) {{ $user->email }} @else -NA- @endif </td>
								</tr>
								<tr>
									<td> Mobile Number</td>
									<td> @if(isset($user->mobileno)) {{ $user->mobileno }} @else -NA- @endif </td>
								</tr>
								<tr>
									<td> DOB</td>
									<td> @if($user->dob != null) {{ date('d-M-Y', strtotime($user->dob)) }} @else -NA- @endif </td>
								</tr>
								<tr>
									<td> Gender</td>
									<td> @if($user->gender != null) {{ $user->gender }} @else -NA- @endif </td>
								</tr>
								<tr>
									<td>State</td>
									<td> @if(isset($user->state)) {{ $user->state }} @else -NA- @endif</td>
								</tr>
								<tr>
									<td>District/City</td>
									<td> @if(isset($user->city)) {{ $user->city }} @else -NA- @endif</td>
								</tr>			
								<tr>
									<td> Pincode</td>
									<td> @if(isset($user->pincode)) {{ $user->pincode }} @else -NA- @endif </td>
								</tr>
								<tr>
									<td>Referral Code</td>
									<td> @if(isset($user->referral_code)) {{ $user->referral_code }} @else -NA- @endif </td>
								</tr>
								<tr>
									<td> Address</td>
									<td> @if(isset($user->address)) {{ $user->address }} @else -NA- @endif </td>
								</tr>
								<tr>
									<tr>
										<td>Profile Image</td>
										<td> 
											@if(isset($user->profile_photo))
												<img src="{{ URL::to(App\Helpers\Thumbnail::image("user/$user->profile_photo","200","200","cf"))}}" />
											@else
												-Not Provided-
											@endif
										</td>
									</tr>
									<td>Add Date</td>
									<td>@if(isset($user->add_date)) {{ $user->add_date }} @else -NA- @endif </td>
								</tr>
								{{-- <tr>
									<td>IP</td>
									<td> {{ $user->ip }} </td>
								</tr> --}}
							</tbody>
						</table>
					</div>
				</div>
			  </div>
			  <div role="tabpanel" class="tab-pane fade" id="two">
				@include('admin.user.document_table')
			  </div>
				<div role="tabpanel" class="tab-pane fade" id="three">
					@include('admin.user.bank_details_table')
				</div>
			  </div>
			</div>
		  </div>



	
</div>
<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>
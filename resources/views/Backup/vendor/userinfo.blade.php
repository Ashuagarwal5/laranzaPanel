<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Detail of {{$detail->full_name}}</h4>
</div>
		<div class="modal-body">
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
								<td> {{ $detail->id }} </td>
								</tr>
								
								<tr>
								<td>Profile Photo</td>
								<td><img src="{{ asset('assets/admin/default_user.png') }}" height="100"/></td>
								</tr>

								<tr>
								<td>Name</td>
								<td> {{$detail->full_name}}</td>
								</tr>
								<tr>
								<td>Email</td>
								<td> @if(!empty($detail->email)){{$detail->email}}@else Not Provided @endif </td>
								</tr>
								<tr>
								<td>Mobile No</td>
								<td> {{$detail->mobileno}}  </td>
								</tr>
								<tr>
								<td>Address</td>
								<td> 
									@if(isset($detail->address))
									{{$detail->address}} 
									@else
									Not Available
									@endif
								</td>
								</tr>
								<tr>
								<td>Created Date</td>
								<td> {{ $detail->add_date }} </td>
								</tr>
								

							</tbody>
					</table>
				</div>
			</div>
		</div>

<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

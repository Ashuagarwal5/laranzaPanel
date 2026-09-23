<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">{{ $manager_name }} Detail</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-bordered table-hover"  boid="sample_1">
				<thead>
					<tr>
						<th>Field Name</th>
						<th>Data</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Id</td>
						<td> {!! $detail->id !!} </td>
					</tr>

					<tr>
						<td> User Name</td>
						<td> {!! $detail->client_name !!} </td>
					</tr>
					<tr>
						<td>User Photo</td>
						<td>   <img src='{{URL::to(App\Helpers\Thumbnail::image("feedback/$detail->client_photo","250","200","rf"))}}' alt="image not found" />
						</td>
					</tr>
					<tr>
						<td> User Location</td>
						<td> {!! $detail->client_location !!} </td>
					</tr>
					<tr>
						<td> Status</td>
						<td> {!! $detail->status !!} </td>
					</tr>

					<tr>
						<td> Feedback</td>
						<td> {!! nl2br($detail->feedback) !!} </td>
					</tr>
					<tr>
						<td>Created At</td>
						<td> {!! $detail->add_date !!} </td>
					</tr>
					<tr>
						<td>Updated At</td>
						<td> {!! $detail->update_date !!} </td>
					</tr>
					<tr>
						<td>Ip</td>
						<td> {!! $detail->ip !!} </td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

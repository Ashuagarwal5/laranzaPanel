<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Blog Comment Detail</h4>
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
						<td>Name</td>
						<td> {{ $detail->name }} </td>
					</tr>
					<tr>
						<td>Email </td>
						<td> {{ $detail->email }} </td>
					</tr>
					
					<tr>
						<td>Message</td>
						<td> {!! $detail->message !!} </td>
					</tr>
					
					<tr>
						<td>Created Date</td>
						<td> {{ $detail->add_date }} </td>
					</tr>
							<!-- <tr>
							<td>Updated Date</td>
							<td> {{ $detail->update_date }} </td>
						</tr> -->
						<tr>
							<td>IP</td>
							<td> {{ $detail->ip }} </td>
						</tr>

					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="modal-footer">
		<button type="button" class="btn default" data-dismiss="modal">Close</button>
	</div>

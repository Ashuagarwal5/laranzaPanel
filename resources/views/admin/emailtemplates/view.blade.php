<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Email Templates Detail</h4>
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
			<td> {{ $detail->em_tm_id }} </td>
			</tr>
			<tr>
			<td>Title</td>
			<td> {{ $detail->title }} </td>
			</tr>
			<tr>
			<td>Subject</td>
			<td> {{ $detail->subject }} </td>
			</tr>
			<tr>
			<td>Message</td>
			<td> {!! $detail->message !!} </td>
			</tr>
            

							</tbody>
					</table>
				</div>
			</div>
		</div>

<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

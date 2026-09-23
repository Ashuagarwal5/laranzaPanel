<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Branch Reviews Detail</h4>
</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
                <div class="table-responsive">
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
			<td>User Name</td>
			<td> @if(!empty($detail->user_name)) {{ $detail->user_name }} @else Not Provided @endif </td>
			</tr>
					
			<tr>
			<td>Reviews</td>
			<td> @if(!empty($detail->review)) {{ $detail->review }} @else Not Provided @endif </td>
			</tr>
			
			<tr>
			<td>Branch Name</td>
			<td> @if(!empty($detail->branch_name)) {{ $detail->branch_name }} @else Not Provided @endif </td>
			</tr>
			
			<tr>
			<td>Rate</td>
			<td> @if(!empty($detail->rate)) {{ $detail->rate }} @else Not Provided @endif </td>
			</tr>

			<tr>
			<td>Updated Date</td>
			<td> {{ $detail->update_date }} </td>
			</tr>
			<tr>
			<td>IP</td>
			<td> {{ $detail->ip }} </td>
			</tr>



							</tbody>
					</table>
                    </div>
				</div>
			</div>
		</div>

<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Branch Stores Detail</h4>
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
			<td>Branch Stores Title</td>
			<td> @if(!empty($detail->branch_name)) {{ $detail->branch_name }} @else Not Provided @endif </td>
			</tr>
			
			
			<tr>
			<td>Image</td>
			<td>
				<img src="{{URL::to(App\Helpers\Thumbnail::image("branch/$detail->branch_image","200","160","ff=ffffff")) }}">
			</td>
			</tr>
			
			<tr>
			<td>Branch Stores Title</td>
			<td> @if(!empty($detail->branch_name)) {{ $detail->branch_name }} @else Not Provided @endif </td>
			</tr>
			
			<tr>
			<td>Branch Stores City</td>
			<td> @if(!empty($detail->city)) {{ $detail->city }} @else Not Provided @endif </td>
			</tr>
			
			<tr>
			<td>Branch Stores State</td>
			<td> @if(!empty($detail->state)) {{ $detail->state }} @else Not Provided @endif </td>
			</tr>
			
			<tr>
			<td>Branch Stores Country</td>
			<td> @if(!empty($detail->country)) {{ $detail->country }} @else Not Provided @endif </td>
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

<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

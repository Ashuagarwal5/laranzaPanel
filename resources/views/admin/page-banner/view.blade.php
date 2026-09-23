<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Banner Detail</h4>
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
							<td>Banner Title</td>
							<td> {{ $detail->title }} </td>
						</tr>

						<tr>
							<td>image</td>
							<td> <img src='{{URL::to(App\Helpers\Thumbnail::image("/socially-conscious/".$detail->image,"300","250","ff=ffffff"))}}'> </td>
						</tr>

						
						<tr>
							<td>Created Date</td>
							<td> {{ $detail->created_at }} </td>
						</tr>
						<tr>
							<td>Updated Date</td>
							<td> {{ $detail->updated_at }} </td>
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

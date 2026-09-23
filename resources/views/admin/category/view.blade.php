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
			<td> {{ $detail->banner_id }} </td>
			</tr>
			<tr>
			<td>Banner Title</td>
			<td> @if(!empty($detail->banner_title)) {{ $detail->banner_title }} @else Not Provided @endif </td>
			</tr>
			
			
			<tr>
			<td>Image</td>
			<td>
				<img src="{{URL::to(App\Helpers\Thumbnail::image("banner/$detail->banner_image","200","160","ff=ffffff")) }}">
			</td>
			</tr>
			
			<tr>
			<td>Sort Description</td>
			<td> @if(!empty($detail->sort_description)) {{ $detail->sort_description }} @else Not Provided @endif</td>
			</tr>
			
			<tr>
			<td>Button Title</td>
			<td>@if(!empty($detail->button_title)) {{ $detail->button_title }} @else Not Provided @endif</td>
			</tr>
			
			<tr>
			<td>Banner URL</td>
			<td>@if(!empty($detail->banner_url)) {{ $detail->banner_url }} @else Not Provided @endif</td>
			</tr>
			<tr>
			<td>Created Date</td>
			<td> {{ $detail->add_date }} </td>
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

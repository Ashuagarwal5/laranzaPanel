<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Section  Detail</h4>
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

						<!-- <tr>
							<td>Section Title</td>
							<td> {{ isset($detail->title) ? $detail->title : 'Not Found !' }} </td>
						</tr> -->

						<tr>
							<td>Display Order</td>
							<td> {{ $detail->display_order }} </td>
						</tr>

						<tr>
							<td>image</td>
							<td> <img src='{{URL::to(App\Helpers\Thumbnail::image("/about-us/".$detail->image,"200","180","ff=ffffff"))}}'> </td>
						</tr>

						<tr>
							<td> Description</td>
							<td> {!! $detail->description !!} </td>
						</tr>

						

						
						<tr>
							<td>Created Date</td>
							<td> {{ $detail->create_date }} </td>
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

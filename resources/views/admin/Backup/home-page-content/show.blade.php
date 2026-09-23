<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Home Content Detail</h4>
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
							<td>Display Order</td>
							<td> {{ $detail->display_order }} </td>
						</tr>

						<tr>
						<td>Image</td>
						<td>  
						 <img src="{{ URL::to(App\Helpers\Thumbnail::image("home-page-content/$detail->image","150","100","cf"))}}" 
						      alt="-N/A-" />
						 </td>
						</tr>
						<tr>
						<td>Title</td>
						<td> {!! $detail->title !!} </td>
						</tr>
							<td>Description</td>
							<td> {!! $detail->description !!} </td>
						</tr>
						<tr>
						<td>Created At</td>
						<td> {{ date('d M Y', strtotime($detail->created_at)) }} </td>
						</tr>
						<tr>
						<td>Updated At</td>
						<td> {{ date('d M Y', strtotime($detail->updated_at)) }} </td>
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

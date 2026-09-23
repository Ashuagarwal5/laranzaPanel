<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Gallery Detail</h4>
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
						 @if($detail->image!= '')
						<td>Image</td>
						<td>  
						 <img src="{{ URL::to(App\Helpers\Thumbnail::image("gallery/$detail->image","150","100","cf"))}}" alt="-N/A-" />
						 </td>
						</tr>
						@else
						<tr>
						<td>Video</td>
						<td > <iframe height="180px" src="{{$detail->vedio}}" frameborder="0" allowfullscreen></iframe>
						</td>
						</tr>
						@endif
						<tr>
						<td>Title</td>
						<td> {!! $detail->title !!} </td>
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

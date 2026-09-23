<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Support Officer @if(isset($detail->name)) {!! ucfirst($detail->name) !!}'s @else N/A @endif Detail</h4>
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
						<td> @if(isset($detail->userid)) {!! $detail->userid !!}  @else N/A @endif</td>
					</tr>
					<tr>
						<td>Name</td>
						<td>@if(isset($detail->name))  {!! ucfirst($detail->name) !!}  @else N/A @endif</td>
						</tr>

					<td>Image</td>
					<td>
					@if(isset($detail->pic))
						<img src="{{ URL::to(App\Helpers\Thumbnail::image("user/$detail->pic","200","100","cf"))}}" />
						@else
						--Not Uploaded--
						@endif
					</td>
				</tr>
				<tr>
					<td>Email</td>
					<td> @if(isset($detail->emailadd)) {!! $detail->emailadd !!}  @else N/A @endif</td>
				</tr>
				<tr>
					<td>Mobile No</td>
					<td> @if(isset($detail->contactno))  {!! $detail->contactno !!}  @else N/A @endif</td>
				</tr>
				<tr>
					<td>Created At</td>
					<td> @if(isset($detail->add_date))  {!! $detail->add_date !!}  @else N/A @endif</td>
				</tr>
				<tr>
					<td>Updated At</td>
					<td> @if(isset($detail->update_date))  {!! $detail->update_date !!} @else N/A @endif </td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Details of Shipment</h4>
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
								<td>Shipment Id</td>
								<td> {{ $detail->ord_shp_id }} </td>
								</tr>

								<tr>
								<td>Tracking Url</td>
								<td> {{$detail->tracking_url}}</td>
								</tr>
								<tr>
								<td>Tracking Code</td>
								<td> {{$detail->tracking_code}} </td>
								</tr>
								<tr>
								<td>Courier Company</td>
								<td> {{$detail->courier_company}}  </td>
								</tr>
								<tr>
								<td>Shipment Date</td>
								<td> 
									{{$detail->shipment_date}}
								</td>
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

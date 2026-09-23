
<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Product Price Detail</h4>
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
								<td>Product Title</td>
								<td> {!!$detail->product_title !!} </td>
								</tr>
								
 								<tr>
 									<td>Product Price</td><td>{{$detail->price}}</td>
 								</tr>

 								<tr>
 									<td>Country</td><td>{{$detail->cntry_name}}</td>
 								</tr>

 								<tr>
 									<td>Country Currency</td><td>{{$detail->currency}}</td>
 								</tr>

 								<tr>
 									<td>Currency Symbol</td><td>{{$detail->symbol}}</td>
 								</tr>

 								<tr>
 									<td>Country Alpha Code</td><td>{{$detail->alphacode}}</td>
 								</tr>

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

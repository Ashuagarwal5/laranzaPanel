<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Promocode Detail</h4>
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
			<td> {{ $detail->promo_code_id }} </td>
			</tr>
			<tr>
			<td>Title</td>
			<td> {{ $detail->title }} </td>
			</tr>
			<tr>
			<td>Coupon Code</td>
			<td> {!! $detail->coupon_code !!} </td>
			</tr>
			<tr>
			<td>Uses Per Coupon</td>
			<td> {{ $detail->uses_per_coupon }} </td>
			</tr>
			<tr>
			<td>Uses Per Customer</td>
			<td> {{ $detail->uses_per_customer }} </td>
			</tr>
			<tr>
			<td>From Date</td>
			<td> {{(date('d M , Y',$detail->from_date)) }} </td>
			</tr>
			<tr>
			<td>To Date</td>
			<td> {{(date('d M , Y',$detail->to_date)) }} </td>
			</tr>
			<tr>
			<td>Apply As</td>
			<td> {{ $detail->apply_as }} </td>
			</tr>
			<tr>
			<td>Discount</td>
			<td> {{ $detail->discount }} </td>
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

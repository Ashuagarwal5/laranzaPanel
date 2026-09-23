<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Dealer Detail</h4>
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
								<td style="width: 30%;">Dealer Name</td>
								<td style="width: 70%;"> 
								@if($detail->name!='') {{$detail->name}} @else -NA- @endif
								 </td>
								</tr>
								<tr>
								<td>Dealer Email</td>
								<td> 
								@if($detail->email!='') {{$detail->email}} @else -NA- @endif
								 </td>
								</tr>
								<tr>
								<td>Dealer Mobile Number</td>
								<td> 
								@if($detail->mobileno!='') {{$detail->mobileno}} @else -NA- @endif
								 </td>
								</tr>
								<tr>
								<td>Dealer Location</td>
								<td> 
								@if($detail->destination_name!='') {{$detail->destination_name}} @else -NA- @endif
								 </td>
								</tr>
								
								
								<tr>
								<td>Dealer Address</td>
								<td> {!! $detail->address !!} </td>
								</tr>
								
								
								
								<tr>
								<td>Dealer Contact Number</td>
								<td>
								
								
								{{ $detail->contact_no!='' ? $detail->contact_no : '-NA-' }}
								
								  </td>
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

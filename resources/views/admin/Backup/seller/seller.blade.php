<div id="tab2" class="tab-pane fade" >
	<div class="row">
		<div class="col-md-12 pd-top">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-bordered table-striped" id="users">
						<tr>
							<td>Seller Name</td>
							<td>
								{{ $seller->first_name }} &nbsp;   {{ $seller->last_name }}
							</td>
						</tr>
						
						<tr>
							<td>Store Name</td>
							<td>
								{{ $seller->shop_name }}
							</td>
						</tr>
						
						
						<tr>
							<td>Store Location</td>
							<td>
								{{ $seller->location_on_map }}
							</td>
						</tr>
						
						
						<tr>
							<td>E-mail</td>
							<td>
								{{ $seller->email }}
							</td>
						</tr>
						<tr>
							<td>Mobile no.</td>
							<td>
								{{ $seller->mobileno }}
							</td>
						</tr>
						<tr>
							<td>Position</td>
							<td>
								{{ $seller->designation }}
							</td>
						</tr>
						<tr>
							<td>Created at</td>
							<td>
								{{ $seller->created_at }}
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Inquiry Detail</h4>
</div>
		<div class="modal-body">



                                                <div class="table-responsive">
                                                  <table class="table table-bordered table-striped" id="users">

                                                     
			<tr>
			<td>Id</td>
			<td> {{ $detail->pro_inq_id }} </td>
			</tr>




			<tr>
			<td>Name</td>
			<td> {{ $detail->name}} </td>
			</tr>
			
			<tr>
			<td>Email</td>
			<td> {{ $detail->email}} </td>
			</tr>


			<tr>
			<td>Mobile No</td>
			<td> {{ $detail->mobile_no }} </td>
			</tr>
			
			<tr>
			<td>Product Name</td>
			<td> {{ $detail->title}} </td>
			</tr>
			
			<tr>
			<td>Quantity</td>
			<td> {{ $detail->quantity}} </td>
			</tr>
			
				<tr>
			<td>Place</td>
			<td> {{ $detail->place}} </td>
			</tr>


			<tr>
			<td>Query</td>
			<td> {!! nl2br(e($detail->query)) !!} </td>
			</tr>
           <tr>
		
			<tr>
			<td>Created Date </td>
			<td> {{ $detail->add_date }} </td>
			</tr>

                                                    </table>
                                                </div>




		</div>
<div class="clr"></div>
<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

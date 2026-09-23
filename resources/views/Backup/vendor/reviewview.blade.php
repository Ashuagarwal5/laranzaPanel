
<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Review Detail</h4>
</div>
		<div class="modal-body">



                                                <div class="table-responsive">
                                                  <table class="table table-bordered table-striped" id="users">

                                                     
			<tr>
			<td>Id</td>
			<td> {{ $detail->rw_id }} </td>
			</tr>




			<tr>
			<td>Name</td>
			<td> {{ $detail->first_name}} </td>
			</tr>


			<tr>
			<td>Review</td>
			<td> {{ $detail->review }} </td>
			</tr>

			<tr>
			<td>Rate</td>
			<td> {{ $detail->rate }} </td>
			</tr>
           <tr>
			<td>Product</td>
			<td> {{ $detail->title }} </td>
			</tr>


			<tr>
			<td>Created Date </td>
			<td> {{ $detail->created_at }} </td>
			</tr>

                                                    </table>
                                                </div>




		</div>
<div class="clr"></div>
<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

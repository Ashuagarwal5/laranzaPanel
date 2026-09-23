<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Blog Detail</h4>
</div>

</style>


<style>
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 14px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<div id="Basic" class="tabcontent" @if(!isset($tab)) style="display:block;" @endif>
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
						<td>Blog Title</td>
						<td> {{ $detail->blog_title }} </td>
						</tr>
				
					
						<tr>
						<td>Blog Category Title</td>
						<td> {{ $detail->category }} </td>
						</tr>
		               <tr>
						<td>Blog Image</td>
						<td> <img src="{{ asset('/storage/app/uploads/blogs/'.$detail->image)}}"  alt="--N/A-"
							   style="min-width:150px;max-width: 150px;max-height: 130px; min-height: 130px;" alt="-N/A-">
					    </td>
						</tr>
					
						
				
						<tr>
						<td>Blog Description</td>
						<td>{!! $detail->blog_content !!} </td>
						</tr>
						
						
						<td>Add Date</td>
						<td> {{ $detail->add_date }} </td>
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

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">Detail of {{$detail->first_name. ' '. $detail->last_name}}</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12  tab-content">

			<ul class="nav  nav-tabs" style="background:#fff;">
				<li class="active">
					<a href="#tab1"  data-toggle="tab">
						<i class="fa fa-building-o" data-name="user" data-size="16" data-c="#000" data-hc="#000" data-loop="true"></i>
					User Data</a>
				</li>
				
			</ul>
			<div id="tab1" class="tab-pane fade active in">

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
								<td>Profile Photo</td>
								<td>
									
									@if($detail->profile_photo)
									<img src="{{ URL::to(App\Helpers\Thumbnail::image("/user/$detail->profile_photo","200","200","ff=ffffff"))}}" height="100"/>
									@else

									<img src="{{ asset("assets/admin/default_user.png") }}" height="100"/>
									@endif														
								</td>
							</tr>

							<tr>
								<td>Name</td>
								<td> {{$detail->first_name. ' '. $detail->last_name}}</td>
							</tr>
							<tr>
								<td>Email</td>
								<td> @if(!empty($detail->email)){{$detail->email}}@else Not Provided @endif </td>
							</tr>
							<tr>
								<td>Mobile No</td>
								<td> {{$detail->mobileno}}  </td>
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

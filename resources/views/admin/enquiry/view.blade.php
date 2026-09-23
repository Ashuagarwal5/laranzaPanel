<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">
		@if($detail->enq_type ==  'enquiry-product' )
		Product Enquiry Detail
		@endif

		@if($detail->enq_type ==  'enquiry' )
		Contact Us Enquiry Detail
		@endif

		@if($detail->enq_type ==  'enquiry-design' )
		Design Enquiry Detail
		@endif

	</h4>
</div>
<div class="modal-body">
	<link href="{{ asset('assets/css/toastr.css') }}" rel="stylesheet" type="text/css"/>
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
						<td> {{ $detail->enq_id }} </td>
					</tr>

					<tr>
						<td>Enquiry Type</td>
						<td> {{ ucwords(str_replace('-',' ',$detail->enq_type))}} </td>
					</tr>


					<tr>
						<td>Fulll name</td>
						<td> {{ $detail->name }} </td>
					</tr>
					<tr>
						<td>E-mail</td>
						<td> {{ $detail->email }} </td>
					</tr>
					<tr>
						<td>Mobile No</td>
						<td> {{ isset($detail->phone) ? $detail->phone : 'Not Found !' }} </td>
					</tr>
					<tr>

						@if($detail->cntry_name)
						<tr>
							<td>Country </td>
							<td> {{ $detail->cntry_name }} </td>
						</tr>
						<tr>
							@endif	


							@if($detail->product_title)
							<tr>
								<td>Product </td>
								<td> {{ $detail->product_title }} </td>
							</tr>
							<tr>
								@endif	





								@if($detail->image)
								<tr>
									<td>Image </td>
									<td> 
										<a target="_blank" href="{{ url('storage/app/uploads/enquiry/'.$detail->image) }}" >
											<img src="{{ URL::to(App\Helpers\Thumbnail::image("enquiry/$detail->image","150","100","cf"))}}" alt="-N/A-" />
										</a> </td>
									</tr>
									<tr>
										@endif	

										<td>Message</td>
										<td> {!! nl2br($detail->message) !!} </td>
									</tr>
									<tr>
										<td>Created Date </td>
										<td> {{ $detail->created_at }} </td>
									</tr>
								</tbody>
							</table>
							<hr>
							<!-- hide reply <div class="replyrecord">
							</div> -->
						</div>
					</div>
					<script  src="{{ asset('assets/js/toastr.min.js') }}"  type="text/javascript"></script>
					<script>
						var routes = "{{ URL::to('admin/enquiry/allreply/'.$detail->enq_id)}}";
					</script>
					<script>
						function displayreply()  {
							$.ajax({
								url: routes ,
								type: "get",
								dataType: 'html',
								success: function(s){
									$('.replyrecord').html(s);
								}
							});
						}

						displayreply();
						$(document).ready(function(){


							displayreply();
							$("#replyshow").click(function(){
								$("#rform").show();
							});
							$('#enquiryreply').submit(function( event ) {
								event.preventDefault();
								$('.btnsbmtr').html('Processing');
								$('.btnsbmtr').attr('disabled','disabled')
								$.ajax({
									url  : $(this).attr('action'),
									type: "POST",
									data: $(this).serialize(),
									dataType: 'json',
									beforeSend:function(){
										$('.formmessage').remove();
									}
									,
									success: function(response) {
										$('.btnsbmtr').html('Send');
										$('.btnsbmtr').removeAttr('disabled');
										toastr[response.status]("Reply send successfully", "Notifications");
										$('#enquiryreply')[0].reset();
										$("#rform").fadeIn('slow');
										displayreply();
									},
									error: function(response){
										$('.btnsbmtr').html('Send');
										$('.btnsbmtr').removeAttr('disabled');
										var data = response.responseJSON;
										$.each(data, function( key, value ) {
											console.log(key + " => " + value);
											var msg = '<label class="error formmessage" for="'+key+'"  style="color:red">'+value+'</label>';
											$('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);
										});
									}
								});
							});
						});
					</script>
					<div class="modal-footer">
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>

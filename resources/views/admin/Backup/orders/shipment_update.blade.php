<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title" id="user_delete_confirm_title">{{$model}}</h4>
</div>
<div class="modal-body">

	@if($error)
	<div>{!! $error !!}</div>
	@endif

	<form id="shipment_form" action="{{route('admin.order.shipment.post',$id)}}" method="POST" class="form-horizontal shipment_form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />




		<div class="form-group">
			<label for="goes_from_email" class="col-sm-2 control-label"> Shipment Status</label>
			<div class="col-sm-10">
				<select class="shipment_status form-control" name="shipment_status">
					<option value="">Select Status</option>


					@if($order->status == 'awaiting shipment')

					<option value="order shipped">Order Shipped</option>
					@endif


					@if($order->status == 'order shipped')
					<option value="completed">Order Delivered</option>
					@endif



				</select>
			</div>
		</div>

		<div class="pager wizard">
			<button type="submit" class="btn btn-primary submit" >Submit</button>
		</div>


	</form>






	<script>

		$(document).on("submit", ".shipment_form", function(event) {
			var posturl = $(this).attr('action');

			var formid = '#' + $(this).attr('id');

			$(this).ajaxSubmit({
				url: posturl,
				dataType: 'json',
				type: "POST",
				beforeSend: function() {
					$(".submit").attr("disabled", 'disabled');
					$('.formmessage').hide();
					$('#wait-div').show();
				},
				success: function(response) {
					$(".submit").removeAttr("disabled", 'disabled');
					$(formid).find('.form-group').removeClass('has-error');
					toastr[response.msgType](response.msg, response.msgHead); 

					$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
					if (response.status == "success") {
						$(formid).find('.alert').fadeIn();
						$(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
					} else {
						$(formid).find('.alert').fadeIn();
						$(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
						$.each(response.errorArray, function( key, value ) {
							console.log(key + " => " + value);
							var msg = '<label class="error formmessage" for="'+key+'"  style="color:ef6f6c">'+value+'</label>';

							$(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').closest('.form-group').addClass('has-error');

							$(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);
						});



					}
					if (response.slideToTop){
                  //$('html, body').animate({scrollTop: 0 }, 'slow');
                  $('html, body').animate({
                  	scrollTop: $(formid).offset().top-290
                  },800);
              }
              if (response.url){

              	setTimeout(function(){ 
              		window.location.href = response.url; 


              	}, 3000);




              }




              if (response.selfReload)
              	window.location.reload();
              if (response.status == 'success') {
                  //$(formid)[0].reset();
              }
              if (response.redirect == 'yes') {
              	window.location.href = response.redirectUrl;
              }
          },
          error: function(response) {
          	var data = response.responseJSON;
          	$(".submit").removeAttr("disabled", 'disabled');

          }
      });
			return false;
		});


	</script>
</div>


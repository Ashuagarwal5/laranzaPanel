<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title">Update Reward Points of {{ $user->full_name }} </h4>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="panel-body">
        <form method="post" id="reward_update" class="ajaxformclass" action="{{ $saveURL }}" style="border: 1px solid #c1c1c1;background-color: #f2f2f2; padding:8px;">
          <div class="col-sm-12">
            <div class="alert" style="margin-top:10px;display:none;"> <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong> </div>
          </div>
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            
            
            <div class="row">

			   <div class="form-group col-sm-12">
			      <label for="validate-text"> Transaction Type *</label>
			      <select name="transaction_type" class="form-control" id="transaction_type">
			         <option value="">Select Transaction Type </option>
			         <option value="Earn" @if(isset($data->transaction_type) && $data->transaction_type == 'Earn') selected="selected" @endif >Earn</option>
			         <option value="Redeem" @if(isset($data->transaction_type) && $data->transaction_type == 'Redeem') selected="selected" @endif >Redeem</option>
			      </select>
			      @if(!empty($errors->first('transaction_type')))
			      <div class="btn btn-sm btn-danger">{{ $errors->first('transaction_type') }}</div>
			      @endif 
			   </div>

			   <div class="form-group col-sm-12" id="qr_value_div" style="display:none">
			      <label for="validate-text"> QR Code Value *</label>
			      <input type="text"  class="form-control" name="qr_value" id="qr_val" value="@if(!empty(old('qr_value')!='')){{old('qr_value')}}@elseif(isset($data->qr_value)){!!$data->qr_value!!}@endif"  id="validate-text" placeholder="Enter Qr Value" minlength="6">
			      @if(!empty($errors->first('qr_value')))
			      <div class="btn btn-sm btn-danger">{{ $errors->first('qr_value') }}</div>
			      @endif 
			</div>
			</div>
			<div class="row">
			   <div class="form-group col-sm-12" id="reward_points_div" style="display: none">
			      <label for="validate-text"> Reward Point Value *</label>
			      <input type="number"  class="form-control" name="point" id="point" value="@if(!empty(old('point')!='')){{old('point')}}@elseif(isset($data->point)){!!$data->point!!}@endif"  id="validate-text" placeholder="Enter Reward Point Value" maxlength="6">
			      @if(!empty($errors->first('point')))
			      <div class="btn btn-sm btn-danger">{{ $errors->first('point') }}</div>
			      @endif 
			   </div>
			   
			</div>
          <div class="clr"></div>
          <p class="text-right">
          	<input type="hidden" name="redirect" value="All" />
            <button type="submit" class="submit btn btn-space btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

<script>
$(document).ready(function(){
    $("#transaction_type").change(function(){
    
      	var transtype = $("#transaction_type").val();
      	
      	if(transtype == 'Earn')
      	{
			$("#qr_value_div").css("display", "block");
      		$("#reward_points_div").css("display", "block");
	    }
	    else if(transtype == 'Redeem')
	    {
			$("#qr_value_div").css("display", "none");
      		$("#reward_points_div").css("display", "block");
			$('#point').prop('disabled', false)
			$('#point').val('')
			$("#qr_val").val(''); 
      		$("#qr_value_div").hide();
 	    }  
		else{
			$("#qr_value_div").css("display", "none");
      		$("#reward_points_div").css("display", "none");
		}  
    });
});
</script>
<script>

$("#qr_val").blur(function(){
      var conpon_no = this.value;
      var csrf_token = "{{ csrf_token() }}";
            jQuery.ajax({
                url: '{{ route("admin.user.coupon.check") }}',
                type: 'POST',
                data: {
	              'coupon_no': conpon_no,
	              '_token': csrf_token
                },
                success: function(data) 
                {
					if(data.key_value == '1')
					{
	                	$("#qr_val").val(data.qr_value); 
	                	$("#point").val(data.reward_points);  
                	}
                	else if(data.key_value == '0') { 
						$('#point').val('')
						alert('Invalid QR Code Value'); 
					}
					else{
						$('#point').val('')
						alert('This QR Code is Already Used');
					}
                }
            });
});

</script>

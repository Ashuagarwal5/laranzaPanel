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
            <label for="validate-text"> Reward Point Value *</label>
            <input type="number"  class="form-control" name="point" value="@if(!empty(old('point')!='')){{old('point')}}@elseif(isset($data->point)){!!$data->point!!}@endif"  id="validate-text" placeholder="Enter Reward Point Value" maxlength="6">
            @if(!empty($errors->first('point')))
            <div class="btn btn-sm btn-danger">{{ $errors->first('point') }}</div>
            @endif </div>
				<div class="form-group col-sm-12">
            <label for="validate-text"> Transaction Type *</label>
            <select name="transaction_type" class="form-control" id="transaction_type">
				<option value="">Select Transaction Type </option>
				<option value="Earn" @if($data->transaction_type == 'Earn') selected="selected" @endif >Earn</option>
				<option value="Redeem" @if($data->transaction_type == 'Redeem') selected="selected" @endif >Redeem</option>
			</select>
            @if(!empty($errors->first('transaction_type')))
            <div class="btn btn-sm btn-danger">{{ $errors->first('transaction_type') }}</div>
            @endif </div>
			</div>
            
            
            
            
            <div class="row" id="qr_info">
	            <div class="form-group col-sm-6">
		            <label for="validate-text"> Coupon Number *</label>
		            <input type="text"  class="form-control" name="coupon_no" value="@if(!empty(old('coupon_no')!='')){{old('coupon_no')}}@elseif(isset($data->coupon_no)){!!$data->coupon_no!!}@endif"  id="validate-text" placeholder="Enter Coupon no" minlength="7">
					@if(!empty($errors->first('coupon_no')))
					<div class="btn btn-sm btn-danger">{{ $errors->first('coupon_no') }}</div>
					@endif 
			   </div>
	            
	            
	            
				<div class="form-group col-sm-6">
		            <label for="validate-text"> QR Value *</label>
		            <input type="text"  class="form-control" name="qr_value" value="@if(!empty(old('qr_value')!='')){{old('qr_value')}}@elseif(isset($data->qr_value)){!!$data->qr_value!!}@endif"  id="validate-text" placeholder="Enter Qr Value" minlength="6">
					@if(!empty($errors->first('qr_value')))
					<div class="btn btn-sm btn-danger">{{ $errors->first('qr_value') }}</div>
					@endif 
			   </div>
			</div>
			
			<!-- 
			<div class="row" id="product_info">
	            <div class="form-group col-sm-12">
		            <label for="validate-text"> Product Info *</label>
		               <select name="transaction_type" class="form-control" id="transaction_type">
						<option value="">Select Product Info </option>
						@foreach($prdData as $product)
<option value="{{ $product->id }}" @if($data->product_id == $product->id) selected="selected" @endif > ID:{{ $product->id }} Product Group Code {{ $product->product_group_code }}</option>
						@endforeach
					</select>

			   </div>
	            
	            
	            
				
			</div>
			-->
			
			
          <div class="clr"></div>
          <p class="text-right">
          	<input type="hidden" name="redirect" value="{{ $redirect }}" />
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


    $("#transaction_type").click(function(){
    
      	var transtype = $("#transaction_type").val();
      	
      	if(transtype == 'Earn')
      	{
      		$("#qr_info").show('slow');
	        $("#product_info").show('slow'); 

	    }
	    else
	    {
	       $("#qr_info").hide('slow');
	       $("#product_info").hide('slow');   
	    }    
    });
});
</script>

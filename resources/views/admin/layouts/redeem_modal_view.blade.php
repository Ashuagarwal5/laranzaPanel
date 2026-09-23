<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="user_delete_confirm_title">{{$model}}</h4>
  </div>
  <div class="modal-body">
     
          <label for="payment">Payment Details</label>
          <textarea name="payment_details" readonly id="payment_details" class="form-control" cols="30" rows="5">{{$data->payment_data}}</textarea>
        <br>
          <img src="{{url('uploads/200/200/fff/PaymentScreenshot/'.$data->payment_screenshot)}}">
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  </div>
  

<div class="modal-header">
 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
 <h4 class="modal-title">Shipment Details <span class="pull-right">
 </span></h4>
</div>
<div class="modal-body">
	
 <div class="panel-body">
  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Sr No.</th>
          <th>Tracking Url</th>
          <th>Courier Company </th>
          <th>Shipment Date </th>
          <th>Create Date </th>
        </tr>
      </thead>
      <tbody>
        @if(count($shipped_data) > 0)  
          @foreach($shipped_data as $key => $data)
            <tr>
              <td>{{$key+1}}</td>
              <td>{{$data->tracking_url}}</td>
              <td>{{$data->courier_company}}</td>
              <td>{{date('d M Y', strtotime($data->shipment_date))}}</td>
              <td>{{date('d M Y h:i a', strtotime($data->created_at))}}</td>
            </tr>
          @endforeach
        @else
           <tr>
            <td colspan='5' class="text-center">No any shipping data  found !</td>
           </tr>
        @endif
      </tbody>
    </table>
  </div>
  <form method="post" class="ajaxformclass" id="payment_detail" 
    action="{{route('shipment-detail-add',$id)}}" >
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

    <div class="form-group">
     <div class="alert" style="margin-top:10px;display:none;">
       <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
     </div>
    </div> 
   
    <div class="form-group">
      <label for="validate-text">Tracking Url </label>
      <div class="input-group" style="width: 100%;">
       <input id="validate-text" name="tracking_url" type="text"
       placeholder="Tracking Url" class="form-control"
       value=""/>
     </div>
    </div>

    {{--  <div class="form-group">
      <label for="validate-text">Tracking Code </label>
      <div class="input-group" style="width: 100%;">
       <input id="tracking_code" name="tracking_code" type="text"
       placeholder="Tracking Code" class="form-control"
       value=""/>
     </div>
    </div> --}}

    <div class="form-group">
      <label for="validate-text">Courier Company </label>
      <div class="input-group" style="width: 100%;">
       <input iid="validate-text" name="courier_company" type="text"
       placeholder="Courier Company" class="form-control"
       value=""/>
     </div>
    </div>

    <div class="form-group">
      <label for="validate-text">Shipment Date </label>
      <div class="input-group" style="width: 100%;">
       <input id="validate-text" name="shipment_date" type="text"
       placeholder="Shipment Date" data-provide="datepicker" class="form-control datepicker"
       value=""/>
     </div>
    </div>
    <div class="col-md-12 mar-10">
     <div class="col-xs-4 col-md-4"></div>
      <div class="col-xs-4 col-md-2">
        <button type="submit"  class="btn btn-primary btn-block btn-md submit">
          Save
         </button>
      </div>
    </div>
  </form>
 </div>

</div>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/jquery.form.js') }}"type="text/javascript"></script>

<script src="{{ asset('assets/vendors/daterangepicker/js/daterangepicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/pages/datepicker.js') }}" type="text/javascript"></script>

<script>
	$(document).on("submit", ".ajax_form_submit", function(event) {
    var posturl = $(this).attr('action');
    var callbackFunction = $(this).attr('data-callback_function');
    if (callbackFunction) {
      if (callbackForm() == false) {
        return false;
      }
    }
    var formid = '#'+$(this).attr('id');
    $(".submit").attr("disabled", 'disabled');
    $(this).ajaxSubmit({
      url: posturl,
      dataType: 'json',
      type: "POST",
      beforeSend: function() 
      {
        $('.formmessage').hide();
        $('#wait-div').show();
      },
      success: function(response) {
        $(".submit").removeAttr("disabled", 'disabled');
        $(formid).find('.form-group').removeClass('has-error');
        toastr[response.msgHead](response.msg, response.msgHead);  
        
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
                if (response.url)
                  window.location.href = response.url;
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



	$(".datepicker").datepicker({});

	
</script>



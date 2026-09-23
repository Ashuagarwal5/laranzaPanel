<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="user_delete_confirm_title">{{$model}}</h4>
  </div>
  <div class="modal-body">
     
          @if($error)
          <div>{!! $error !!}</div>
      @endif
      <form id="form-id" action="{{ $confirm_route }}" method="post" enctype="multipart/form-data" class="ajaxformclass">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="alert p-1" style="margin-top:10px;display:none;">
          <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a>&nbsp;<strong class="ajax_message"></strong>
        </div>
            <label for="form">Select Step</label>
            <input type="hidden" name="id" value="{{$id}}">
            <select name="step" id="step" class="form-control">
                <option value="2">Step-2</option>
                <option value="3">Step-3</option>
            </select>
  </div>
</form>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" id="conform_submit" class="btn btn-danger">Confirm</button>
  </div>
  <script>
    $(document).on('click','#conform_submit',function(e){
      $("#form-id").submit()
    })
</script>
  
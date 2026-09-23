<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title" id="user_delete_confirm_title">{{$model}}</h4>
</div>
<div class="modal-body">
   
        @if($error)
        <div>{!! $error !!}</div>
    @else
       {{$message}}?
    @endif
    
     <div class="loginformseller">
		<form class="form-horizontal ajaxform" method="post" action="{{ $confirm_route }}" >
	   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
	   <div id="login_message" ></div>


 <div class="" style="padding:30px 0;">

   <label for="emmail_login">Choose Reason : </label>
   
    <input type="radio" name="reason" value="Out of stock" required> Out of stock 
    <input type="radio" name="reason" value="Temporarily out of service"> Temporarily out of service
   
</div>





        <div class="" style="padding:30px 0;">
                       

                        <label for="emmail_login">Comment</label>
                        <textarea name="comment" class="form-control"></textarea>



                    </div>
                    
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
 
  @if(!$error)
    <button  type="submit" href="{{ $confirm_route }}" class="btn btn-danger">Confirm</button>
  @endif


       </form>

       </div>



 
</div>
<div class="modal-footer">

   
 
</div>

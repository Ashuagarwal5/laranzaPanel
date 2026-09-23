<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Chat With  {{$userdetail->first_name}} {{$userdetail->last_name}}</h4>
</div>
<style>
.received_withd_msg p {
    background: #ebebeb none repeat scroll 0 0;
    border-radius: 3px;
    color: #646464;
    font-size: 14px;
    margin: 0;
    padding: 5px 10px 5px 12px;
    width: 100%;
    border-radius: 0 15px 15px 15px;
}
.mesgs {
    float: left;
    padding: 30px 15px 0 25px;
    width: 100%;
}
.outgoing_msg {
    overflow: hidden;
    margin: 0px 0 26px;
}
.sent_msg_img {
    display: inline-block;
    width: 6%;
    float: right;
    margin-left: 12px;
    margin-right: 30px;
}
.sent_msg p {
	border-radius: 15px 0px 15px 15px;
}
.sent_msg span {
    float: right;
}
.model-footer-d {
    border-top: 0px !important;
    padding-top: 0px !important;
}
</style>
		<div class="modal-body">
			<div class="row">
				
				  <div class="mesgs">
          	  
            
 
       
       <div  id="chatwindow">   
          @if(isset($messagelist) && count($messagelist)>0)
          <div class="msg_history">
          @foreach($messagelist as $k=>$val)
          
          	@if($val->send_by == 'User')
		<div class="incoming_msg">
		  <div class="incoming_msg_img"> 
			  
			  
<!--
			  <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> 
-->
			  
			  @if($val->profile_photo)
									<img src="{{ URL::to(App\Helpers\Thumbnail::image("/user/$val->profile_photo","200","200","ff=ffffff"))}}" height="60"/>
									 @else
									 
									 <img src="{{ asset("assets/admin/default_user.png") }}" height="30"/>
									 @endif
			  
			  
			  </div>
		  <div class="received_msg">
			<div class="received_withd_msg">
			  <p>{{$val->message}}</p>
			  <span class="time_date"> {{date('h:i A',strtotime($val->created_at))}}    | {{date('d F',strtotime($val->created_at))}}</span></div>
		  </div>
		</div>
        @endif
			@if($val->send_by == 'Admin')
        <div class="outgoing_msg">
        <div class="sent_msg_img"> <img src="http://dolovery.sakhtlaunde.in/assets/admin/img/avatar-man.jpg" alt="lakhan"> </div>
		  <div class="sent_msg">
			<p>{{$val->message}}</p>
			<span class="time_date"> {{date('h:i A',strtotime($val->created_at))}}    | {{date('d F',strtotime($val->created_at))}}</span> </div>
		</div>
	  		
	  		
	  		@endif
            
        @endforeach
        </div>
        @else
        <center>Chating With Your Favourite Friend....</center>
        @endif
        
        </div>
      
        
  
      
    </div>
    </div>

				
			</div>
		</div>
		
<div class="modal-footer model-footer-d">
<div class="type_msg">
            <div class="input_msg_write">
		
        
        <form method="post" class="form-horizontal chat_form" id="reply" action="{{route('reply.store')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="user_id" value="{{$id}}" />
		<input type="hidden" name="send_by" value="Admin" />
        <input name="message" type="text" class="write_msg message_input" placeholder="Type a message" required>
		  <button class="msg_send_btn submit" type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
          </form>
            </div>
          </div>
</div>
<script>

$(document).ready(function(){
	
	$(window).load(function() {
  $("html, body").animate({ scrollTop: $(document).height() }, 1000);
});
</script>

		
		<form method="post" id="active_inactive" class="" action="{!! $navi['route'] !!}" >
				<div class="col-sm-12">
					<div class="alert" style="margin-top:10px;display:none;">
					<a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
					</div>
				  </div>
				  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
		
		
		
		
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title">Please Confirm</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<input type="hidden" name="confirm" value="yes" />
				<div class="col-md-12">
					<h4>Are you sure to <?=$taskP?> this <span class="confirm_task"></span> record</h4>
				</div>
				
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn red">Confirm</button>
		</div>
</form>







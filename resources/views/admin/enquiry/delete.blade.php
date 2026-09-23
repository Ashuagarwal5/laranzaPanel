	<div class="modal-header">
           <h4 id="user_delete_confirm_title" class="modal-title">Delete {{ $modal }}</h4>
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			</div>
			<div class="modal-body">
			 @if($error)
        <div>{!! $error !!}</div>
				@else
				 Are you sure to delete this  {{ $modal }}?
				@endif
			 </div>
			<div class="modal-footer">
			<button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
			 @if(!$error)
                <a href="{{ $confirm_route }}" type="button" class="btn btn-danger">Delete</a>
              @endif
		</div>




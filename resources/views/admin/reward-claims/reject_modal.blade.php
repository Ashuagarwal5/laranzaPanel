<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Reject Claim</h4>
</div>
@if($error)
<div class="modal-body">{!! $error !!}</div>
<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button></div>
@else
<form method="post" id="claim_reject_form" class="ajaxformclass" action="{{ $confirm_route }}">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="alert" style="margin-top:0;display:none;"><a href="javascript:void(0)" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong></div>
        <p>Rejecting the claim for <strong>{{ $claim->product_name }}</strong> ({{ $claim->points_spent }} points).</p>
        @if($allowPointRefund == 'Yes')
        <div class="alert alert-info" style="display:block;">{{ $claim->points_spent }} reward points will be credited back to the customer.</div>
        @else
        <div class="alert alert-warning" style="display:block;">Point refund is turned off in General Settings, so the points will <strong>not</strong> be returned.</div>
        @endif
        <div class="form-group">
            <label>Reason For Rejection *</label>
            <textarea class="form-control" name="admin_remark" rows="3" placeholder="Enter the reason shown to the customer"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Reject Claim</button>
    </div>
</form>
@endif

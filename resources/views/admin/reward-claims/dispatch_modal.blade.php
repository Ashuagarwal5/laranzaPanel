<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Send Product To Dealer</h4>
</div>
@if($error)
<div class="modal-body">{!! $error !!}</div>
<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button></div>
@else
<form method="post" id="claim_dispatch_form" class="ajaxformclass" action="{{ $confirm_route }}">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="alert" style="margin-top:0;display:none;"><a href="javascript:void(0)" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong></div>
        <p>Sending <strong>{{ $claim->product_name }}</strong> ({{ $claim->points_spent }} points) to the carpenter's dealer.</p>
        <table class="table table-condensed table-bordered">
            <tr><th width="30%">Dealer</th><td>{{ $claim->dealer_name }}</td></tr>
            <tr><th>Dealer Mobile</th><td>{{ $claim->dealer_mobile }}</td></tr>
            <tr><th>Ship To</th><td>{{ $claim->dealer_address }} {{ $claim->dealer_city }} {{ $claim->dealer_pincode }}</td></tr>
        </table>
        <div class="form-group">
            <label>Courier Name *</label>
            <input type="text" class="form-control input-sm" name="courier_name" placeholder="e.g. DTDC" autocomplete="off">
        </div>
        <div class="form-group">
            <label>Tracking / Docket Number *</label>
            <input type="text" class="form-control input-sm" name="tracking_number" placeholder="Enter tracking number" autocomplete="off">
        </div>
        <div class="form-group">
            <label>Remark</label>
            <textarea class="form-control" name="admin_remark" rows="3" placeholder="Optional note for this claim"></textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Mark As Sent To Dealer</button>
    </div>
</form>
@endif

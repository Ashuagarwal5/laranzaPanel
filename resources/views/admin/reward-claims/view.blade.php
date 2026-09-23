<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">Claim #{{ $claim->id }} &mdash; {{ $claim->status }}</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-4">
            @if($product && $product->image)
            <img src="{{ url('uploads/240/240/f/reward-products/'.$product->image) }}" class="img-responsive" />
            @else
            <p class="text-muted">No Image</p>
            @endif
        </div>
        <div class="col-sm-8">
            <table class="table table-condensed">
                <tr><th width="42%">Product</th><td>{{ $claim->product_name }}</td></tr>
                <tr><th>Quantity</th><td>{{ max(1, (int) $claim->quantity) }}</td></tr>
                <tr><th>Points Spent</th><td>{{ $claim->points_spent }} @if($claim->points_per_unit) <small class="text-muted">({{ $claim->points_per_unit }} x {{ max(1, (int) $claim->quantity) }})</small> @endif</td></tr>
                <tr><th>Status</th><td>{{ $claim->status }}</td></tr>
                @if($claim->redemption_code)
                <tr><th>Pickup Code</th><td><code>{{ $claim->redemption_code }}</code> @if($claim->code_verified_at) <span class="label label-success">Verified {{ $claim->code_verified_at }}</span> @else <span class="label label-default">Not yet used</span> @endif</td></tr>
                @endif
                <tr><th>Requested On</th><td>{{ $claim->created_at }}</td></tr>
            </table>
        </div>
    </div>

    @if(!empty($groupItems) && count($groupItems))
    <h5><strong>Submitted Together ({{ $claim->claim_group_id }})</strong></h5>
    <table class="table table-condensed table-bordered">
        <tr><th>Product</th><th width="12%">Qty</th><th width="20%">Points</th><th width="24%">Status</th></tr>
        @foreach($groupItems as $item)
        <tr @if($item->id == $claim->id) class="info" @endif>
            <td>{{ $item->product_name }}</td>
            <td>{{ max(1, (int) $item->quantity) }}</td>
            <td>{{ $item->points_spent }}</td>
            <td>{{ $item->status }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <h5><strong>Carpenter</strong></h5>
    <table class="table table-condensed table-bordered">
        <tr><th width="30%">Name</th><td>{{ $claim->user_name }}</td></tr>
        <tr><th>Mobile</th><td>{{ $claim->user_mobile }}</td></tr>
        @if(!empty($summary))
        <tr><th>Total Products Claimed</th><td>{{ (int) $summary->total_units }} item(s) across {{ (int) $summary->total_claims }} claim(s)</td></tr>
        <tr><th>Total Points Spent</th><td>{{ (int) $summary->total_points }}</td></tr>
        @endif
    </table>

    <h5><strong>Collect From Dealer</strong></h5>
    <table class="table table-condensed table-bordered">
        <tr><th width="30%">Dealer</th><td>{{ $claim->dealer_name }}</td></tr>
        <tr><th>Mobile</th><td>{{ $claim->dealer_mobile }}</td></tr>
        <tr><th>Address</th><td>{{ $claim->dealer_address }}</td></tr>
        <tr><th>City / Pincode</th><td>{{ $claim->dealer_city }} {{ $claim->dealer_pincode ? ' - '.$claim->dealer_pincode : '' }}</td></tr>
    </table>

    @if(in_array($claim->status, ['Dispatched', 'Delivered']))
    <h5><strong>Dispatch To Dealer</strong></h5>
    <table class="table table-condensed table-bordered">
        <tr><th width="30%">Courier</th><td>{{ $claim->courier_name }}</td></tr>
        <tr><th>Tracking Number</th><td>{{ $claim->tracking_number }}</td></tr>
        <tr><th>Sent On</th><td>{{ $claim->dispatched_at }}</td></tr>
    </table>
    @endif

    @if($claim->status == 'Delivered')
    <h5><strong>Handover To Carpenter</strong></h5>
    <table class="table table-condensed table-bordered">
        <tr><th width="30%">Collected On</th><td>{{ $claim->delivered_at }}</td></tr>
        <tr><th>Remark</th><td>{{ $claim->delivered_remark }}</td></tr>
    </table>
    @endif

    @if($claim->admin_remark)
    <h5><strong>Admin Remark</strong></h5>
    <p>{{ $claim->admin_remark }}</p>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

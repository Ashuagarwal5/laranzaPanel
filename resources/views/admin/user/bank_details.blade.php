<form method="post" id="bank_info" class="ajaxformclass" action="{{$navi['route']}}" enctype="multipart/form-data">
    <div class="col-sm-12">
        <div class="alert" style="margin-top:10px;display:none;">
            <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{csrf_token()}}" />
    <input type="hidden" name="full_name" value="bank_details" />

    <div class="form-group col-sm-4">
        <label for="validate-text"> Payment Type</label>
        <select name="payment_type" id="payment_type" class="form-control">
            <option value="">Select Type</option>
            <option value="Bank Details">Bank Details</option>
            <option value="UPI">UPI</option>
            <option value="Gpay">Gpay</option>
        </select>
        @if(!empty($errors->first('payment_type')))
            <div class="btn btn-sm btn-danger">{{$errors->first('payment_type')}}</div>
        @endif
    </div>
    <div id="bank_complete_details" style="display: none;">
        <div class="form-group col-sm-4">
            <label for="validate-text"> Bank Name *</label>
            <input type="text" class="form-control" name="bank_name" value="" id="validate-text" placeholder="Enter Bank Name">
            @if(!empty($errors->first('bank_name')))
                <div class="btn btn-sm btn-danger">{{$errors->first('bank_name')}}</div>
            @endif
        </div>

        <div class="form-group col-sm-4">
            <label for="validate-text"> Branch Name</label>
            <input type="text" class="form-control" name="branch_name" value="" id="validate-text" placeholder="Enter Branch Name">
            @if(!empty($errors->first('branch_name')))
                <div class="btn btn-sm btn-danger">{{$errors->first('branch_name')}}</div>
            @endif
        </div>

        <div class="form-group col-sm-4">
            <label for="validate-text"> IFSC Code</label>
            <input type="text" class="form-control" name="ifsc_code" value="" id="validate-text" placeholder="Enter IFSC Code">
        </div>

        <div class="form-group col-sm-4">
            <label for="validate-text"> Account Number</label>
            <input type="text" class="form-control" name="account_number" value="" id="validate-text" placeholder="Enter Account Number">
            @if(!empty($errors->first('account_number')))
                <div class="btn btn-sm btn-danger">{{$errors->first('account_number')}}</div>
            @endif
        </div>
    </div>
    <div id="upi_account" style="display: none;">
        <div class="form-group col-sm-4">
            <label for="validate-text"> UPI ID</label>
            <input type="text" class="form-control" name="upi_id" value="" id="validate-text" placeholder="Enter UPI ID">
            @if(!empty($errors->first('upi_id')))
                <div class="btn btn-sm btn-danger">{{$errors->first('upi_id')}}</div>
            @endif
        </div>
    </div>
    <div id="gpay_account" style="display: none;">
        <div class="form-group col-sm-4">
            <label for="validate-text"> Gpay</label>
            <input type="text" class="form-control" name="gpay_number" value="" id="validate-text" placeholder="Enter Gpay Number">
            @if(!empty($errors->first('gpay_number')))
                <div class="btn btn-sm btn-danger">{{$errors->first('gpay_number')}}</div>
            @endif
        </div>
    </div>


    <div class="form-group col-sm-4">
        <label for="validate-text"> Status</label>
        <select name="status" id="status" class="form-control">
            <option value="">Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        @if(!empty($errors->first('status')))
            <div class="btn btn-sm btn-danger">{{$errors->first('status')}}</div>
        @endif
    </div>



    <hr>
    <div class="clr"></div>
    <p class="text-right">
        <button type="submit" class="submit btn btn-space btn-primary">Submit</button>
        <a href="{{$navi['back_url']}}" class="btn btn-default">Cancel</a>
    </p>
</form>
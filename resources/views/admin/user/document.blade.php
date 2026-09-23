<form method="post" id="document_info" class="ajaxformclass" action="{{ $navi['route'] }}" enctype="multipart/form-data">
    <div class="col-sm-12">
        <div class="alert" style="margin-top:10px;display:none;">
            <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
        </div>
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="full_name" value="document_type" />
    <div class="form-group col-sm-6">
        <label for="validate-text">  Document Type </label>
        <select name="document_name" id="document_type" class="form-control">
            <option value="">Select Document</option>
            <option value="Aadhaar Card">Aadhaar Card</option>
            <option value="Voter ID">Voter ID</option>
            <option value="Driving Licence">Driving Licence</option>
        </select>
        @if(!empty($errors->first('full_name')))<div class="btn btn-sm btn-danger">{{ $errors->first('full_name') }}</div>@endif
    </div>
    <div class="form-group col-sm-6">
        <label for="validate-text">  Status</label>
        <select name="status" id="status" class="form-control">
            <option value="">Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        @if(!empty($errors->first('status')))<div class="btn btn-sm btn-danger">{{ $errors->first('status') }}</div>@endif
    </div>
<div class="form-group col-sm-6">
    <label for="validate-text"> Document Image 1 *</label>
    <input type="file"  class="form-control input-sm" name="document_image_1" id="validate-text"placeholder="Enter Document Image" >
    @if(!empty($errors->first('document_image_1')))<div class="btn btn-sm btn-danger">{{ $errors->first('document_image_1') }}</div>@endif
</div>

<div class="form-group col-sm-6">
    <label for="validate-text"> Document Image 2 *</label>
    <input type="file"  class="form-control input-sm" name="document_image_2" id="validate-text" placeholder="Enter Document Image" >
    @if(!empty($errors->first('document_image_2')))<div class="btn btn-sm btn-danger">{{ $errors->first('document_image_2') }}</div>@endif
</div>


<hr>
<div class="clr"></div>
<p class="text-right">
    <button type="submit" class="submit btn btn-space btn-primary">Submit</button>
    <a href="{{ $navi['back_url'] }}"class="btn btn-default">Cancel</a>
</p>
</form>



<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title">{{ isset($data) ? 'Edit' : 'Create' }} Product Category</h4>
</div>
<form method="post" id="category_modal_form" class="ajaxformclass" action="{{ $route_url }}" enctype="multipart/form-data">
    <div class="modal-body">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="from_modal" value="1" />
        <div class="alert" style="margin-top:0;display:none;"><a href="javascript:void(0)" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong></div>
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" class="form-control input-sm" name="name" value="{{ isset($data) ? $data->name : '' }}" placeholder="Enter category name" autocomplete="off">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

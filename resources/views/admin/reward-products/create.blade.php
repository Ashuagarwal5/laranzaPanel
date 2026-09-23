@extends('admin/layouts/default')
@section('title')
{{ $manager_name }} Manager::CRM
@stop
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop
@section('content')
<section class="content-header">
    <h1>{{ $manager_name }} Manager</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="livicon" data-name="home" data-size="14" data-color="#000"></i> Dashboard</a></li>
        <li>{{ $manager_name }} Manager</li>
        <li class="active">{{ isset($data) ? 'Edit' : 'Add' }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title"><i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i> {{ isset($data) ? 'Edit' : 'Add' }} Product</h3>
                    <div class="pull-right"><a href="{{ route('admin.reward-products') }}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></div>
                </div>
                <div class="panel-body">
                    <form method="post" id="reward_product_form" class="ajaxformclass" action="{{ $route_url }}" enctype="multipart/form-data">
                        <div class="col-sm-12">
                            <div class="alert" style="margin-top:10px;display:none;"><a href="javascript:void(0)" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong></div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <div class="form-group col-sm-6">
                            <label>Product Category *</label>
                            <select name="category_id" id="category_id" class="form-control input-sm">
                                <option value="">Select Product Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) old('category_id', isset($data) ? $data->category_id : '') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if(count($categories) == 0)
                            <p class="help-block text-danger">No active category found. <a href="{{ route('admin.reward-catalog-categories') }}">Create a product category first.</a></p>
                            @endif
                        </div>

                        <div class="form-group col-sm-6">
                            <label>Product Name *</label>
                            <input type="text" class="form-control input-sm" name="name" value="{{ old('name', isset($data) ? $data->name : '') }}" placeholder="Enter product name">
                        </div>

                        <div class="form-group col-sm-4">
                            <label>Reward Points Required *</label>
                            <input type="number" min="1" class="form-control input-sm" name="points_required" value="{{ old('points_required', isset($data) ? $data->points_required : '') }}" placeholder="e.g. 2000">
                        </div>

                        <div class="form-group col-sm-4">
                            <label>Price</label>
                            <input type="number" step="0.01" min="0" class="form-control input-sm" name="price" value="{{ old('price', isset($data) ? $data->price : '') }}" placeholder="Enter product price">
                        </div>

                        <div class="form-group col-sm-4">
                            <label>SKU / Product Code</label>
                            <input type="text" class="form-control input-sm" name="sku" value="{{ old('sku', isset($data) ? $data->sku : '') }}" placeholder="Enter SKU">
                        </div>

                        <div class="form-group col-sm-12">
                            <label>Short Description</label>
                            <input type="text" class="form-control input-sm" name="short_description" value="{{ old('short_description', isset($data) ? $data->short_description : '') }}" placeholder="One line summary shown in the app list">
                        </div>

                        <div class="form-group col-sm-12">
                            <label>Product Details</label>
                            <textarea class="form-control" name="description" rows="5" placeholder="Enter full product details">{{ old('description', isset($data) ? $data->description : '') }}</textarea>
                        </div>

                        <div class="form-group col-sm-6">
                            <label>Product Image {{ isset($data) ? '' : '*' }}</label>
                            <input type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/gif">
                            @if(isset($data) && $data->image)
                            <p class="help-block"><img src="{{ url('uploads/120/120/f/reward-products/'.$data->image) }}" width="90" /></p>
                            @endif
                        </div>

                        <div class="form-group col-sm-2">
                            <label>Stock</label>
                            <input type="number" min="0" class="form-control input-sm" name="stock" value="{{ old('stock', isset($data) ? $data->stock : '') }}" placeholder="Leave blank for unlimited">
                        </div>

                        <div class="form-group col-sm-2">
                            <label>Sort Order</label>
                            <input type="number" min="0" class="form-control input-sm" name="sort_order" value="{{ old('sort_order', isset($data) ? $data->sort_order : 0) }}">
                        </div>

                        <div class="form-group col-sm-2">
                            <label>Status *</label>
                            <select name="status" class="form-control input-sm">
                                <option value="1" {{ (string) old('status', isset($data) ? (int) $data->status : 1) === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (string) old('status', isset($data) ? (int) $data->status : 1) === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-space btn-primary">Submit</button>
                            <a href="{{ route('admin.reward-products') }}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                    @include('admin.notifications')
                </div>
            </div>
        </div>
    </div>
</section>
@stop

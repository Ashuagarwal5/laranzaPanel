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
        <li class="active">{{ isset($data) ? 'Edit' : 'Create' }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title"><i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i> {{ isset($data) ? 'Edit' : 'Create' }} {{ $manager_name }}</h3>
                    <div class="pull-right"><a href="{{ route('admin.reward-catalog-categories') }}" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></div>
                </div>
                <div class="panel-body">
                    <form method="post" id="basic_info" class="ajaxformclass" action="{{ $route_url }}" enctype="multipart/form-data">
                        <div class="col-sm-12">
                            <div class="alert" style="margin-top:10px;display:none;"><a href="javascript:void(0)" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong></div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group col-sm-6">
                            <label>Name *</label>
                            <input type="text" class="form-control input-sm" name="name" value="{{ old('name', isset($data) ? $data->name : '') }}" placeholder="Enter category name">
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Slug</label>
                            <input type="text" class="form-control input-sm" name="slug" value="{{ old('slug', isset($data) ? $data->slug : '') }}" placeholder="Generated from name when left blank">
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Enter description">{{ old('description', isset($data) ? $data->description : '') }}</textarea>
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Image</label>
                            <input type="file" class="form-control" name="image" accept="image/jpeg,image/png,image/gif">
                            @if(isset($data) && $data->image)
                                <p class="help-block">Current image: {{ $data->image }}</p>
                            @endif
                        </div>
                        <div class="form-group col-sm-6">
                            <label>Sort Order *</label>
                            <input type="number" min="0" class="form-control input-sm" name="sort_order" value="{{ old('sort_order', isset($data) ? $data->sort_order : 0) }}">
                        </div>
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-space btn-primary">Submit</button>
                            <a href="{{ route('admin.reward-catalog-categories') }}" class="btn btn-default">Cancel</a>
                        </div>
                    </form>
                    @include('admin.notifications')
                </div>
            </div>
        </div>
    </div>
</section>
@stop

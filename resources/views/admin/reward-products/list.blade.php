@extends('admin.layouts.default')
@section('title')
{{ $manager_name }} Manager::CRM
@parent
@stop
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop
@section('content')
<section class="content-header">
    <h1>{{ $manager_name }}</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="livicon" data-name="home" data-size="14" data-color="#000"></i> Dashboard</a></li>
        <li class="active">{{ $manager_name }} List</li>
    </ol>
</section>
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"><i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i> {{ $manager_name }} List</h4>
                <div class="pull-right">
                    <a href="{{ $route_category }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-tags"></span> Create Category</a>
                    <a href="{{ $route_create }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Add Product</a>
                </div>
            </div>
            <div class="panel-body">
                @include('admin.notifications')
                <div class="row">
                    <div class="form-group col-sm-4">
                        <label>Filter By Category</label>
                        <select id="filter_category_id" class="form-control input-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="reward_products_table">
                        <thead>
                            <tr class="filters">
                                <th>Sr. No.</th>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Points Required</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Create Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-regular" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content"></div>
    </div>
</div>
@stop
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript">
$(function () {
    var productsTable = $('#reward_products_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[8, 'desc']],
        ajax: {
            url: "{{ $route_data }}",
            data: function (d) {
                d.category_id = $('#filter_category_id').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', name: 'reward_products.image', orderable: false, searchable: false },
            { data: 'name', name: 'reward_products.name' },
            { data: 'category_name', name: 'reward_catalog_categories.name' },
            { data: 'points_required', name: 'reward_products.points_required' },
            { data: 'price', name: 'reward_products.price' },
            { data: 'stock', name: 'reward_products.stock' },
            { data: 'status', name: 'reward_products.status' },
            { data: 'add_date', name: 'reward_products.created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#filter_category_id').on('change', function () {
        productsTable.ajax.reload();
    });

    // Reset the shared confirmation modal so the next row loads fresh content.
    $('#modal-regular').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal').find('.modal-content').empty();
    });
});
</script>
@stop

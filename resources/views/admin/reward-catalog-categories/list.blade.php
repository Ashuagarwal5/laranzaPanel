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
                    <a href="javascript:void(0)" class="btn btn-sm btn-default category-form-modal" data-url="{{ $route_create }}"><span class="glyphicon glyphicon-plus"></span> Create Product Category</a>
                </div>
            </div>
            <div class="panel-body">
                @include('admin.notifications')
                <div class="table-responsive">
                    <table class="table table-bordered" id="reward_categories_table">
                        <thead>
                            <tr class="filters">
                                <th>Sr. No.</th>
                                <th>Category Name</th>
                                <th>Slug</th>
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

<div class="modal fade" id="modal-category-form" tabindex="-1" role="dialog">
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
    $('#reward_categories_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[3, 'desc']],
        ajax: "{{ $route_data }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'slug', name: 'slug' },
            { data: 'add_date', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    // Load the create / edit form into the popup on every click so it is never stale.
    $(document).on('click', '.category-form-modal', function (e) {
        e.preventDefault();
        var url = $(this).attr('data-url');
        $('#modal-category-form').find('.modal-content').html('<div class="modal-body text-center"><i class="fa fa-spinner fa-spin"></i> Please Wait...</div>');
        $('#modal-category-form').modal('show');
        $('#modal-category-form').find('.modal-content').load(url);
    });

    // Reset the shared confirmation modal so the next row loads fresh content.
    $('#modal-regular').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal').find('.modal-content').empty();
    });
});
</script>
@stop

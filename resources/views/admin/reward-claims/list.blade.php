@extends('admin.layouts.default')
@section('title')
{{ $manager_name }}::CRM
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
        <li class="active">{{ $manager_name }}</li>
    </ol>
</section>
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"><i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i> {{ $manager_name }}</h4>
            </div>
            <div class="panel-body">
                @include('admin.notifications')
                <div class="table-responsive">
                    <table class="table table-bordered" id="reward_claims_table">
                        <thead>
                            <tr class="filters">
                                <th>Sr. No.</th>
                                <th>Carpenter</th>
                                <th>Mobile</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Points Spent</th>
                                <th>Carpenter's {{ $status }} Total</th>
                                <th>Collect From Dealer</th>
                                <th>Status</th>
                                <th>Request Date</th>
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
    $('#reward_claims_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[9, 'desc']],
        ajax: "{{ $route_data }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_name', name: 'users.full_name' },
            { data: 'user_mobile', name: 'users.mobileno' },
            { data: 'product_name', name: 'reward_claims.product_name' },
            { data: 'quantity', name: 'reward_claims.quantity' },
            { data: 'points_spent', name: 'reward_claims.points_spent' },
            { data: 'user_claimed', name: 'user_claimed', orderable: false, searchable: false },
            { data: 'dealer_name', name: 'reward_claims.dealer_name' },
            { data: 'status', name: 'reward_claims.status' },
            { data: 'add_date', name: 'reward_claims.created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    // Reset the shared modal so the next row loads fresh content.
    $('#modal-regular').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal').find('.modal-content').empty();
    });
});
</script>
@stop

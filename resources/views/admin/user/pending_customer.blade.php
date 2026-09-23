@extends('admin.layouts.default')
@section('title')
    {{ $pending_customers }} Manager::CRM
    @parent
@stop
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
    <link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet" />
@stop
@section('content')
    <section class="content-header">
        <h1>{{ $pending_customers }} Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>{{ $pending_customers }} Manager</li>
            <li class="active">{{ $pending_customers }} List</li>
        </ol>
    </section>
    <div id="ajaxResponse"></div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <section class="content paddingleft_right15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true"
                            data-c="#fff" data-hc="white"></i>
                        {{ $pending_customers }} List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered " id="table_news">
                            <thead>
                                <tr class="filters">
                                    <th style="width:5%;">Sr. No.</th>
                                    <th>Name</th>
                                    <th>Mobile No</th>
                                    <th>City</th>
                                    <th>Profile Status</th>
                                    <th>Complete Step</th>
                                    <th>Create Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $('#filter_form').submit(function(event) {
                event.preventDefault();
                setData();
            });

            function setData() {
                if ($('select[name="user_type"]').val() != null && $('select[name="user_type"]').val() !=
                    undefined && $('select[name="user_type"]').val() != '') {
                    var user_type = $('select[name="user_type"]').val();
                } else {
                    var user_type = '';
                }

                if ($('input[name="fullname"]').val() != null && $('input[name="fullname"]').val() != undefined &&
                    $('input[name="fullname"]').val() != '') {
                    var fullname = $('input[name="fullname"]').val();
                } else {
                    var fullname = '';
                }

                if ($('input[name="mobileno"]').val() != null && $('input[name="mobileno"]').val() != undefined &&
                    $('input[name="mobileno"]').val() != '') {
                    var mobileno = $('input[name="mobileno"]').val();
                } else {
                    var mobileno = '';
                }
                set_table(fullname, mobileno, user_type);
            }
            if ($('select[name="user_type"]').val() != null && $('select[name="user_type"]').val() != undefined &&
                $('select[name="user_type"]').val() != '') {
                var user_type = $('select[name="user_type"]').val();
            } else {
                var user_type = '';
            }
            if ($('input[name="fullname"]').val() != null && $('input[name="fullname"]').val() != undefined && $(
                    'input[name="fullname"]').val() != '') {
                var fullname = $('input[name="fullname"]').val();
            } else {
                var fullname = '';
            }
            if ($('input[name="mobileno"]').val() != null && $('input[name="mobileno"]').val() != undefined && $(
                    'input[name="mobileno"]').val() != '') {
                var mobileno = $('input[name="mobileno"]').val();
            } else {
                var mobileno = '';
            }
            set_table(fullname, mobileno, user_type);

            function set_table(fullname, mobileno, user_type) {
                var route = "{{ route('admin.user.pendingcustomerdata') }}?fullname=" + fullname + "&mobileno=" +
                    mobileno + "&user_type=" + user_type;
                var table = $('#table_news').DataTable({
                    "select": {
                        style: 'single'
                    },
                    "colReorder": true,
                    processing: true,
                    stateSave: true,
                    destroy: true,
                    serverSide: true,
                    aaSorting: [
                        [0, 'desc']
                    ],
                    ajax: route,
                    columns: [{ data: 'id', name: 'id', searchable: false, render: function (data, type, row, meta) { return meta.settings._iDisplayStart + meta.row + 1; } },
                        {
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'mobileno',
                            name: 'mobileno'
                        },
                        {
                            data: 'city',
                            name: 'city'
                        },
                        {
                            data: 'profile_status',
                            name: 'profile_status'
                        },
                        {
                            data: 'step_completed',
                            name: 'step_completed'
                        },
                        {
                            data: 'add_date',
                            name: 'created_at'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: true
                        }
                    ],
                });
                table.on('draw', function() {
                    $('.livicon').each(function() {
                        $(this).updateLivicon();
                    });
                });
            }

            function statusChange(data, type, row, meta) {
                if (data == 'Active')
                    var str = 'No';
                else if (data == 'Inactive')
                    var str = 'Yes';
                return str;
            }
        });
    </script>
@stop

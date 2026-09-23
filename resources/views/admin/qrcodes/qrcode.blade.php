@extends('admin.layouts.default')
@section('title')
    Qr Code Manager::CRM
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
        <h1>QR Codes Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>QR Codes Manager</li>
            <li class="active">Product QR List</li>
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
                        All QR Codes List
                    </h4>
                    <div class="pull-right" style="margin-right: 6px;">
                        <a href="{{ URL::to('cpmin/products/bulk-create') }}" class="btn btn-sm btn-default"><span
                                class="glyphicon glyphicon-plus"></span>Bulk QR Code</a>
                    </div>
                    <div class="pull-right" style="margin-right: 6px;">
                        <a href="{{ URL::to('cpmin/products/create') }}" class="btn btn-sm btn-default"><span
                                class="glyphicon glyphicon-plus"></span>QR Code</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <form id="filter_form" method="get"
                            style="background: #f2f2f2;padding-top: 25px;margin-bottom: 17px;">
                            <div class="row">
                                @php
                                    $qr_value = app('request')->input('qr_value');
                                    $reward_points = app('request')->input('reward_points');
                                    $product_group_code = app('request')->input('product_group_code');
                                    $used_status = app('request')->input('used_status');
                                    
                                @endphp
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="agent">QR Value:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="qr_value" id="qr_value"
                                                style="width: 100%;" value="@if ($qr_value != null) {{ $qr_value }} @endif" />
                                            <span class="input-group-addon info">
                                                <span class="glyphicon glyphicon-user"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="agent">Reward Points:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="reward_points"
                                                id="reward_points" style="width: 100%;" value="@if ($reward_points != null) {{ $reward_points }} @endif" />
                                            <span class="input-group-addon info">
                                                <span class="glyphicon glyphicon-user"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="agent">Product Category:</label>
                                        <div class="input-group">
                                            <select name="product_group_code" id="product_group_code" class="form-control">
                                                <option value="">Select Product Category</option>
                                                @foreach ($category as $item)
                                                    <option value="{{ $item->id }}"@if ($product_group_code != null)  {{ $product_group_code == $item->id ? 'selected':'' }} @endif>{{ $item->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-addon info">
                                                <span class="glyphicon glyphicon-user"></span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="agent">Used Status:</label>
                                        <div class="input-group">
                                            <select name="used_status" id="used_status" class="form-control">
                                                <option value="">Select Status</option>
                                                <option value="Yes" @if ($used_status != null)  {{ $used_status == 'Yes' ? 'selected':'' }} @endif>Yes</option>
                                                <option value="No"  @if ($used_status != null)  {{ $used_status == 'No' ? 'selected':'' }} @endif>No</option>
                                            </select>
                                            <span class="input-group-addon info">
                                                <span class="glyphicon glyphicon-user"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12" style="margin-bottom:14px;text-align: center;">
                                    <a href="{{route('admin.qrcodes')}}">Reset Search &nbsp; &nbsp;</a>
                                    <button type="submit" class="btn btn-danger">Search</button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered " id="item-lists">
                            <thead>
                                <tr class="filters">
                                    <th>Sr. No.</th>
                                    <th>QR Value</th>
                                    <th>Reward Points</th>
                                    <th>Product Category</th>
                                    <th>Used Status</th>
                                    <th>Add Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productdata as $items)
                                    <tr>
                                        <td>{{ $productdata->firstItem() + $loop->index }}</td>
                                        <td>{{ $items->qr_value }}</td>
                                        <td>{{ $items->reward_points }}</td>
                                        <td>{{ $items->category_name }}</td>
                                        <td>{{ $items->used_status }}</td>
                                        <td>{{ date('d-M-Y',strtotime($items->created_at)) }}</td>
                                        <td>
                                            <a class="delval btn btn-xs btn-primary" title="Edit QR Code"
                                                href="{{ URL::to("cpmin/products/edit/{$items->id}") }}"><i
                                                    class="fa fa-edit"></i></a>
                                            <a class="delval btn btn-xs btn-info" title="Download QR Code"
                                                href="{{ route('products.download.images',['name' => $items->qr_value]) }}"><i
                                                    class="fa fa-download"></i></a>
                                            <input type="hidden" name="copy_code" id="copy_code" class="copy_code"
                                                value="{{ URL::to("download-code/{$items->qr_value}") }}">
                                            <a class="delval btn btn-xs btn-success copy_link"
                                                data_value="{{ URL::to("download-code/{$items->qr_value}") }}"
                                                title="Copy QR Code"><i class="fa fa-copy"></i></a>
                                            <a data-toggle="modal" data-target="#modal-regular"
                                                href="{{ URL::to("cpmin/products/{$items->id}/confirm-delete") }}"
                                                class="delval btn btn-xs btn-danger" title="Delete QR Code"><i
                                                    class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $productdata->appends(['product_group_code' => request()->product_group_code, 'reward_points' => request()->reward_points, 'qr_value' => request()->qr_value, 'used_status' => request()->used_status])->links() }}
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
    <script>
        $(document).on('click', '.copy_link', function(e) {
            var $temp = $("<input>");
            var $url = $(this).attr('data_value');
            $("body").append($temp);
            $temp.val($url).select();
            document.execCommand("copy");
            $temp.remove();
            alert('data copied successfully');
        });
    </script>
    
@stop

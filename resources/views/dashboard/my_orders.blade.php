@extends('dashboard.layout.default')
@section('title', 'My Orders')
@section('content')
    <div class="profile-box animate__animated animate__zoomInDown">
        <div class="row g-4 position-relative">


            <div class="col">
                <div class="p-2 text-center">
                    <h3 class="text-white mb-1 mt-5">My Orders</h3>
                    <p class="text-white-75 mb-1">View the Package purchased by you. </p>

                </div>
            </div>




        </div>
    </div>

    <div class="row position-relative  px-3" style="margin-top:-80px;">


        <div class="col-lg-10 mx-auto">

            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">

                <div class="card-body p-5">
                    <div class="table-responsive border rounded mb-4">
                        <div class="table-responsive">
                            <table class="table table-borderless table-order">
                                <thead>
                                    <tr class="bg-light">
                                        @php
                                            $id = 1000 + $myorder->id;
                                        @endphp
                                        <th colspan="5" class="text-success py-3">Order ID:{{ $id }}</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Package Name</th>
                                        <th>Payment Method</th>
                                        <th>Order Date</th>
                                        <th align="right" style="text-align:right;">Payment Status</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <img style="width:100px;" class="rounded border p-1"
                                                src="{{ url('/uploads/500/500/ff=ffffff//package/'.$myorder->package_image) }}" /><br>
                                            <strong>{{ $myorder->title }}</strong>
                                        </td>

                                        <td>
                                            <h4 class="price">{{ $myorder->payment_method }}</h4>
                                        </td>
                                        <td>{{ date('d-M-Y', strtotime($myorder->order_date)) }}</td>
                                        <td align="right"><span class="badge rounded-pill bg-success"><i
                                                    class="bi bi-check"></i> Successful</span></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

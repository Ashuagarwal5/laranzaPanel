@extends('dashboard.layout.default')
@section('title', 'My Sale')
@section('content')
    <div class="profile-box animate__animated animate__zoomInDown">
        <div class="row g-4 position-relative">

            <div class="col">
                <div class="p-2 text-center">
                    <h3 class="text-white mb-1 mt-5">My Sales</h3>
                    <p class="text-white-75 mb-1">List of sales completed by you or packages purchased by your referrals. </p>

                </div>
            </div>
        </div>
    </div>

    <div class="row position-relative  px-3" style="margin-top:-80px;">


        <div class="col-lg-10 mx-auto">

            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">

                <div class="card-body p-5">
                    @if (!$check->isEmpty())  
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 70px;">Sr. No.</th>
                                    <th scope="col" style="width: 30%;">Package</th>
                                    <th scope="col">Buy Date</th>
                                    <th scope="col">Buyer Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($check as $key => $item)
                                    @php
                                        $value = $key + 1;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $value }}</th>
                                        <td>
                                            <div> <strong>{{ $item->title }}</strong></div>
                                        </td>
                                        <td>{{ date('d/M/Y', strtotime($item->order_date)) }}</td>
                                        <td><strong>{{ $item->full_name }}</strong></td>
                                        <td><a href="#" class="btn btn-outline-success btn-sm m-1"><i
                                                    class="bi bi-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <h4>No sales found, Start referring and generate sales to achieve rewards. </h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

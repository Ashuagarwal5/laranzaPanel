@extends('dashboard.layout.default')
@section('title','My Dealers')
@section('content')
<div class="profile-box animate__animated animate__zoomInDown">
    <div class="row g-4 position-relative">


        <div class="col">
            <div class="p-2 text-center">
                <h3 class="text-white mb-1 mt-5">My Dealers</h3>
                <p class="text-white-75 mb-1">List of all of your dealers.
                </p>

            </div>
        </div>
    </div>
</div>

<div class="row position-relative  px-3" style="margin-top:-80px;">


    <div class="col-lg-12 mx-auto">

        <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">

            <div class="card-body p-2">
                @if (!$dealer->isEmpty())   
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Sr. No.</th>
                                <th scope="col">Dealer Name</th>
                                <th scope="col">Location</th>
                                <th scope="col">Email </th>
                                <th scope="col">Mobile Number </th>
                                <th scope="col">Action </th>
                        </thead>
                        <tbody>
                            @foreach ($dealer as $key => $item)
                            @php
                                $value = $key + 1;
                            @endphp
                            <tr>
                                <th scope="row">{{$value}}</th>
                                <td>
                                    <div> <strong>{{$item->dealer_name}}</strong></div>
                                </td>
                                <td>{{$item->dealership_location}} </td>
                                <td>{{$item->email}} </td>
                                <td><strong>{{$item->mobile_no}}</strong></td>
                                <td><a class="btn btn-success" href="{{route('salesofficer.transection',['id'=>$item->user_id])}}" title="All Transection"><i class="fa fa-money"></i> </a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <h4>You don’t have any dealer</h4>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
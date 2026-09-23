@extends('dashboard.layout.default')
@section('title', 'My Reward')
@section('content')

    <div class="profile-box animate__animated animate__zoomInDown">
        <div class="row g-4 position-relative">

            <div class="col">
                <div class="p-2 text-center">
                    <h3 class="text-white mb-1 mt-5">My Reward</h3>
                    <p class="text-white-75 mb-1">List of you Achievements/ Rewards earned by you so far. </p>

                </div>
            </div>




        </div>
    </div>

    <div class="row position-relative  px-3" style="margin-top:-80px;">


        <div class="col-lg-10 mx-auto">

            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">

                <div class="card-body p-5">
                  @if (!$total_reward->isEmpty())
                  <div class="table-responsive">
                    <table class="table table-striped table-order">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70px;">Sr. No.</th>
                                <th scope="col" style="width: 30%;">Current Achievement</th>
                                <th scope="col">Package Name</th>
                                <th scope="col">Achievement Date</th>
                                <th scope="col">On Completion of</th>

                            </tr>
                        </thead>
                        <tbody>
                          @foreach ($total_reward as $key => $item)
                          @php
                              $value = $key + 1;
                          @endphp
                          <tr>
                            <th scope="row">{{$value}}.</th>

                            <td>{{$item->reward_details}}</td>
                            <td>{{$item->title}}</td>
                            <td><strong>{{date('d M Y',strtotime($item->achieve_date))}}</strong></td>
                            <td><strong>{{$item->sales_completed}} Sales</strong></td>
                        </tr>
                          @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <h4>No Reward found, start referring people to earn rewards</h4>
                  @endif
                   
                </div>
            </div>
        </div>
    </div>
@endsection

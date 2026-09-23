@extends('dashboard.layout.default')
@section('title', 'Action Zone')
@section('content')
    <div class="profile-box animate__animated animate__zoomInDown">
        <div class="row g-4 position-relative">

            <div class="col">
                <div class="p-2 text-center">
                    <h3 class="text-white mb-1 mt-5">My Acton Zone</h3>
                    <p class="text-white-75 mb-1">List of Reward you can achieve by referring our packages. </p>

                </div>
            </div>
        </div>
    </div>

    <div class="row position-relative  px-3" style="margin-top:-80px;">

        <div class="col-lg-10 mx-auto">

            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">
                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0 m-3" id="pills-tab" role="tablist">
                    @foreach ($data as $key => $item)
                        @if ($key == 0)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active bg-white" id="{{ $item->slug }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#{{ $item->slug }}" type="button" role="tab"
                                    aria-controls="{{ $item->slug }}" aria-selected="true">{{ $item->title }}</button>
                            </li>

                        @else
                            <li class="nav-item" role="presentation">
                                <button class="nav-link  bg-white" id="{{ $item->slug }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#{{ $item->slug }}" type="button" role="tab"
                                    aria-controls="{{ $item->slug }}" aria-selected="false">{{ $item->title }}</button>
                            </li>

                        @endif
                    @endforeach
                </ul>


                <div class="tab-content" id="pills-tabContent">
                    @foreach ($data as $key => $item)
                        @if ($key == 0)
                            <div class="tab-pane fade show active" id="{{ $item->slug }}" role="tabpanel"
                                aria-labelledby="{{ $item->slug }}-tab" tabindex="0">
                            <h4 class="m-3">Total No. of Sales = {{$item->user}}</h4>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-order">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: 70px;">Sr. No.</th>
                                                    <th scope="col" style="width: 30%;">Reward Title</th>
                                                    <th scope="col">Reward Image</th>
                                                    <th scope="col">Target Sales</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->packages as $keys => $items)
                                                    @php
                                                        $values = $keys + 1;
                                                    @endphp
                                                    <tr>
                                                        <th scope="row">{{ $values }}.</th>
                                                        <td>
                                                            {{ $items->reward_title }}
                                                        </td>
                                                        <td><img style="width:100px;" class="rounded img-fluid"
                                                                src="images/zone/1.jpg" alt="..."></td>
                                                        <td><strong>{{ $items->sales_target }} Sales</strong></td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            @php
                                $values = $keys + 1;
                            @endphp
                            <div class="tab-pane fade" id="{{ $item->slug }}" role="tabpanel"
                                aria-labelledby="{{ $item->slug }}-tab" tabindex="0">
                                <h2>Total No. of Sales = {{$item->user}}</h2>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-order">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: 70px;">Sr. No.</th>
                                                    <th scope="col" style="width: 30%;">Reward Title</th>
                                                    <th scope="col">Reward Image</th>
                                                    <th scope="col">Target Sales</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->packages as $keys => $items)
                                                    @php
                                                        $values = $keys + 1;
                                                    @endphp
                                                    <tr>
                                                        <th scope="row">{{ $values }}.</th>
                                                        <td>
                                                            {{ $items->reward_title }}
                                                        </td>
                                                        <td><img style="width:100px;" class="rounded img-fluid"
                                                                src="images/zone/1.jpg" alt="..."></td>
                                                        <td><strong>{{ $items->sales_target }} Sales</strong></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

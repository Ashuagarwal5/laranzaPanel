@extends('dashboard.layout.default')
@section('title', 'My Profile')
@section('content')
    <div class="profile-box animate__animated animate__zoomInDown">
        <div class="row g-4 position-relative">
            {{-- <div class="col-auto">
                <div class="avatar-lg">
                    <!-- http://codespur.us/demo/ibs-trio/dashboard/images/avatar-1.jpg -->
                    <img src="{{ url('uploads/profile/' . $user_data->image) }}" alt="user-img"
                        class="img-thumbnail rounded-circle">
                </div>
            </div> --}}

            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{ $user_data->full_name ? $user_data->full_name : 'Unavailable' }}</h3>
                    <p class="text-white-75 mb-1">{{ $user_data->mobileno ? '+91' . $user_data->mobileno : 'Contact Unavailable' }}
                        - {{ $user_data->email ? $user_data->email : 'Email Unavailable' }}</p>
                    <div class="hstack text-white-50 gap-1">
                        <div class="me-2">
                            @if ($user_data->city || $user_data->state)
                                <i
                                    class="bi bi-geo-alt me-1 text-white-75 fs-16"></i>{{ $user_data->city ? $user_data->city : '' }}
                                {{ $user_data->state ? $user_data->state : '' }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!--<div class="col-12 col-lg-auto order-last order-lg-0">
                                    <div class="row text text-white-50 text-center">
                                        <div class="col-lg-6 col-4">
                                            <div class="p-2">
                                                <h4 class="text-white mb-1">24.3K</h4>
                                                <p class="fs-14 mb-0 text-white-75">Followers</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-4">
                                            <div class="p-2">
                                                <h4 class="text-white mb-1">1.3K</h4>
                                                <p class="fs-14 mb-0 text-white-75">Following</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->


        </div>
    </div>

    <div class="row position-relative  px-3" style="margin-top:-80px;">
        <div class="col-sm-12">
            <div class="d-flex mb-3">

                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                    <!--   <li class="nav-item">
                                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
                                                </a>
                                            </li>
                                         <li class="nav-item">
                                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#activities" role="tab">
                                                    <i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Activities</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#projects" role="tab">
                                                    <i class="ri-price-tag-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Projects</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link fs-14" data-bs-toggle="tab" href="#documents" role="tab">
                                                    <i class="ri-folder-4-line d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Documents</span>
                                                </a>
                                            </li>-->
                </ul>
                {{-- <div class="flex-shrink-0">
                    <a href="{{ route('user.edit_profile_password') }}" class="btn btn-outline-light rounded-pill px-3"><i
                            class="bi bi-pencil-square"></i> Edit Profile</a>
                </div> --}}
            </div>
        </div>
        <div class="col-lg-10  col-xxl-10 mx-auto">

            <div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Personal Info</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Full Name :</th>
                                    <td class="text-muted">{{ $user_data->full_name ? $user_data->full_name : 'Unavailable' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Mobile :</th>
                                    <td class="text-muted">
                                        {{ $user_data->mobileno ? '+(91)' . $user_data->mobileno : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Employee Id :</th>
                                    <td class="text-muted">{{ $user_data->employee_id ? $user_data->employee_id : 'Unavailable' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">E-mail :</th>
                                    <td class="text-muted">{{ $user_data->email ? $user_data->email : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Location :</th>
                                    <td class="text-muted">{{ $user_data->city ? $user_data->city : 'City Unavailable' }},
                                        {{ $user_data->state ? $user_data->state : 'State Unavailable' }},
                                        Pincode - {{ $user_data->pincode ? $user_data->pincode : 'Pincode Unavailable' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Joining Date</th>
                                    <td class="text-muted">{{ date('d M Y', strtotime($user_data->created_at)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div>
            {{-- <div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">Refer & Earn</h5>
                        </div>

                    </div>
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <img class="img-fluid" src="{{ asset('dashboard/images/connections.png') }}" />
                        </div>
                        <div class="flex-fill">
                            <small>Referral Code</small>
                            <h4 class="fw-bold text-danger">
                                {{ $user_data->referral_code ? $user_data->referral_code : 'Unavailable' }}</h4>
                        </div>
                        <img style="width:50px;" class="img-fluid" src="{{ asset('dashboard/images/wallet.png') }}" />
                    </div>

                </div>
            </div>
            <div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">My Package</h5>
                        </div>

                    </div>
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <img class="img-fluid" src="{{ asset('dashboard/images/wallet.png') }}" />
                        </div>
                        <div class="flex-fill">
                            <small>{{ $package_data->title }}</small>
                            <h4 class="fw-bold text-success">Rs.{{ $package_data->price }}</h4>
                        </div>
                        <!-- <a href="#" class="btn btn-danger rounded-pill"><i class="bi bi-currency-exchange"></i> Upgrade</a> -->
                    </div>

                </div>
            </div> --}}
        </div>
        {{-- <div class="col-lg-8  col-xxl-9">
            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">My Address</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width:110px;">Country :</th>
                                    <td class="text-muted">{{ $user_data->country ? $user_data->country : 'Unavailable' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">State :</th>
                                    <td class="text-muted">{{ $user_data->state ? $user_data->state : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">City :</th>
                                    <td class="text-muted">{{ $user_data->city ? $user_data->city : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Location :</th>
                                    <td class="text-muted">
                                        @if ($user_data->address)
                                            <strong>Codespur Software Pvt
                                                Ltd.</strong><br>{{ $user_data->address ? $user_data->address : 'Unavailable' }}
                                        @endif
                                    </td>
                                    <!-- <strong>Codespur Software Pvt Ltd.</strong><br>  -->
                                </tr>

                            </tbody>
                        </table>
                    </div>





                </div>
            </div> --}}
            {{-- <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Sponsor Details</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row" style="width:180px;">Sponsor Name :</th>
                                    <td class="text-muted">
                                        {{ $parent_data->full_name ? $parent_data->full_name : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Sponsor Mobile :</th>
                                    <td class="text-muted">
                                        {{ $parent_data->number ? '+91 ' . $parent_data->number : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Sponsor E-mail :</th>
                                    <td class="text-muted">{{ $parent_data->email ? $parent_data->email : 'Unavailable' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th class="ps-0" scope="row">Sponsor Location</th>
                                    <td>
                                        {{ $parent_data->city ? $parent_data->city : 'City Unavailable' }},
                                        {{ $parent_data->state ? $parent_data->state : 'State Unavailable' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>





                </div>
            </div> --}}

        {{-- </div> --}}
    </div>
@endsection

@extends('dashboard.layout.default')
@section('title', 'Edit Profile')
@section('content')
    <div class="profile-box animate__animated animate__zoomInDown">

        <div class="row g-4 position-relative">
            {{-- <div class="col-auto">
                                <div class="avatar-lg">
                                    <img src="{{url('uploads/profile/'.$user_data->image)}}" alt="user-img" class="img-thumbnail rounded-circle">
                                </div>
                            </div> --}}

            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1">{{ $user_data->full_name ? $user_data->full_name : 'Unavailable' }}</h3>
                    <p class="text-white-75 mb-1">
                        {{ $user_data->mobileno ? '+91' . $user_data->mobileno : 'Contact Unavailable' }} -
                        {{ $user_data->email ? $user_data->email : 'Email Unavailable' }}</p>
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



        </div>
    </div>

    <div class="row position-relative  px-3" style="margin-top:-80px;">

        {{-- <div class="col-lg-4 col-xxl-3">
            <div class="card animate__animated animate__bounceInLeft border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Personal Info</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" scope="row">Full Name :</th>
                                    <td class="text-muted">
                                        {{ $user_data->full_name ? $user_data->full_name : 'Unavailable' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Mobile :</th>
                                    <td class="text-muted">
                                        {{ $user_data->mobileno ? '+(91)' . $user_data->mobileno : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">E-mail :</th>
                                    <td class="text-muted">{{ $user_data->email ? $user_data->email : 'Unavailable' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0" scope="row">Location :</th>
                                    <td class="text-muted">{{ $user_data->city ? $user_data->city : 'City Unavailable' }},
                                        {{ $user_data->state ? $user_data->state : 'State Unavailable' }}
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
        </div> --}}
        <div class="col-lg-10  col-xxl-10 mx-auto">

            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">
                <div class="card-header bg-white px-4 pt-4">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        {{-- <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#firmDetails" role="tab">
                                Edit Profile
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#changePassword" role="tab">
                                Change Password
                            </a>
                        </li>

                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        {{-- <div class="tab-pane active" id="firmDetails" role="tabpanel">
                            <form action="{{ route('salesofficer.save_edit_profile_password') }}" method="POST"
                                class="ajaxformclass" id="save_editProfile">

                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="alert" style="margin-top:10px;display:none;">
                                    <a href="javascript:void()" class="close" data-dismiss=""
                                        aria-label="close">&times;</a><strong class="ajax_message"></strong>
                                </div>
                                <input type="hidden" name="id"
                                    value="{{ isset($user_data->id) ? Crypt::encrypt($user_data->id) : '' }}">

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="full_name"
                                                value="{{ isset($user_data) ? $user_data->full_name : old('full_name') }}"
                                                id="floatingInput" placeholder="Enter Name">
                                            <label for="floatingInput">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="mobileno"
                                                value="{{ isset($user_data) ? $user_data->mobileno : old('mobileno') }}"
                                                id="floatingInput" placeholder="Firm Phone Number" readonly>
                                            <label for="floatingInput">Mobile Number</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" name="email"
                                                value="{{ isset($user_data) ? $user_data->email : old('email') }}"
                                                id="floatingInput" placeholder="Email Address" readonly>
                                            <label for="floatingInput">Email Address</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="country"
                                                value="{{ isset($user_data) ? $user_data->country : old('country') }}"
                                                id="floatingInput" placeholder="Country">
                                            <label for="floatingInput">Country</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="state"
                                                value="{{ isset($user_data) ? $user_data->state : old('state') }}"
                                                id="floatingInput" placeholder="Zip Code">
                                            <label for="floatingInput">State</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="city" class="form-control"
                                                value="{{ isset($user_data) ? $user_data->city : old('city') }}"
                                                id="floatingInput" placeholder="www.example.com">
                                            <label for="floatingInput">City</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <textarea class="form-control" name="address" placeholder="Leave a comment here" id="floatingTextarea"
                                                rows="4" style="height:100px;resize:none;">{{ isset($user_data) ? $user_data->address : old('address') }}</textarea>
                                            <label for="floatingTextarea">Address</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="uploadimg">
                                            <div
                                                class="d-flex align-items-center justify-content-center h-100 w-100 flex-column">
                                                <input class="fileinput" type="file" name="image" />
                                                <i class="bi bi-box-arrow-in-down fa-2x text-muted"></i>
                                                <small class="text-muted"> UPLOAD Profile PHOTO</small>
                                                <!-- @if (isset($user_data))
                                                <img src="{{ url('uploads/profile/' . $user_data->image) }}" class="img-thumbnail" width="500px" height="60px" alt="...">
                                                @endif -->
                                            </div>
                                        </div>
                                        <div class="img-thumb small-thumb">
                                            <a href="#" class="text-danger deletebtn"><i
                                                    class="bi bi-x-circle-fill fa-2x"></i></a>
                                            <!-- <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Nnx8dXNlcnxlbnwwfHwwfHw%3D&auto=format&fit=crop&w=500&q=60" class="img-thumbnail" alt="..."> -->
                                            @if (isset($user_data))
                                                <img src="{{ url('uploads/profile/' . $user_data->image) }}"
                                                    class="img-thumbnail" width="500px" height="60px" alt="...">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit"
                                                class="btn btn-primary rounded-pill px-3">Updates</button>
                                            <button type="button" class="btn btn-soft-success">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div> --}}
                        <div class="tab-pane active" id="changePassword" role="tabpanel">
                            <form action="{{ route('salesofficer.save_password') }}" method="POST"
                                class="ajaxformclass" id="save_editProfile">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="alert" style="margin-top:10px;display:none;">
                                    <a href="javascript:void()" class="close" data-dismiss=""
                                        aria-label="close">&times;</a><strong class="ajax_message"></strong>
                                </div>
                                <input type="hidden" name="id"
                                    value="{{ isset($user_data->id) ? Crypt::encrypt($user_data->id) : '' }}">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="password" name="old_password"
                                                >
                                            <label for="floatingInput">Old Password *</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="floatingInput"
                                                placeholder="New Password" name="new_password">
                                            <label for="floatingInput">New Password *</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" id="floatingInput"
                                                placeholder="Confirm Password" name="confirm_password">
                                            <label for="floatingInput">Confirm Password *</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary rounded-pill px-3">Update
                                                Password</button>
                                        </div>
                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@extends('dashboard.layout.default')
@section('title', 'Dashboard')
@section('content')
    <div class=" bg-white shadow-sm p-3 my-3" style="border-left: 10px solid #ea2b2d;border-radius:2px;">
        <h3 class="m-0 text-uppercase">Dashboard</h3>
    </div>

    <div class="row">
        <div class="col-lg-6 col-xl-3">
            <div class=" shadow-sm p-3 rounded-10 mb-3 box animate__animated animate__bounceInLeft">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img style="width:80px;" class="img-fluid rounded" src="{{ asset('dashboard/images/dealer.png') }}"
                            alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold text-uppercase">Total Dealers</h4>
                        <h1 class="text-warning">@if(isset($dealers)){{$dealers}}@else{{'0'}}@endif</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3">
            <div class="box shadow-sm p-3 rounded-10 mb-3 animate__animated animate__bounceInDown">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img style="width:80px;" class="img-fluid" src="{{ asset('dashboard/images/refral.png') }}"
                            alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold text-uppercase">Total Customers</h4>
                        <h1 class="text-info">@if(isset($data)){{$data}}@else{{'0'}}@endif</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3">
            <div class="box shadow-sm p-3 rounded-10 mb-3 animate__animated animate__bounceInUp">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img style="width:80px;" class="img-fluid" src="{{ asset('dashboard/images/img3.png') }}"
                            alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold text-uppercase">Total Sales</h4>
                        <h1 class="text-primary">@if(isset($sales)){{$sales}}@else{{'0'}}@endif</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-3">
            <div class="box shadow-sm p-3 rounded-10 mb-3 animate__animated animate__bounceInRight">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img style="width:80px;" class="img-fluid" src="{{ asset('dashboard/images/transtion.png') }}"
                            alt="...">
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h4 class="fw-bold text-uppercase">Transactions</h4>
                        <h1 class="text-danger">@if(isset($transactions)){{$transactions}}@else{{'0'}}@endif</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--
    <div class="row">
        <div class="col-lg-12 col-xl-6">
            <div class="box shadow-sm p-4 rounded-10 mb-3 animate__animated animate__bounceInDown">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img style="width:80px;" class="img-fluid" src="{{ asset('dashboard/images/zone/1.jpg') }}"
                            alt="...">
                    </div>
                    <div class="flex-grow-1 ms-4">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="fw-bold text-uppercase">Your Current Achievement</h4>
                                {{-- @if ($last_reward != null)
                                <h1 class="text-warning">{{$last_reward->reward_details}}</h1>
                                @else
                                <h1 class="text-danger">Not Reward Found</h1>
                                @endif --}}
                                Not Reward Found
                            </div>
                            <div>
                                {{-- @if ($last_reward != null) --}}
                                <a href="#" class="btn btn-success rounded-pill"><i class="bi bi-currency-exchange"></i>5000</a>
                                {{-- @endif --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-6">
            <div class=" shadow-sm p-4 rounded-10 mb-3 box animate__animated animate__bounceInLeft">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img style="width:80px;" class="img-fluid" src="{{ asset('dashboard/images/connections.png') }}"
                            alt="...">
                    </div>
                    <div class="flex-grow-1 ms-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="fw-bold text-uppercase">Refer & Earn</h4>
                                <small class="text-muted text-uppercase">Referral Code</small>
                                <h1 class="text-primary">ABC!@3</h1>
                            </div>
                            <div>
                                <a href="javascript:void(0)" class="text-muted copy_code"
                                    value_id="ABC@!3"><i class="bi bi-clipboard fa-2x"></i></a>
                                <input type="hidden" name="myInput" id="myInput" value="ABC@!3">
                                <div>
                                    <small class="text-uppercase">Copy Code</small>
                                </div>
                            </div>
                            <div>
                                {{-- <a href="#" class="text-muted"><i class="bi bi-share fa-2x"></i></a> --}}
                                
                                <div class="sharethis-inline-share-buttons btn-link text-muted p-2"  data-url="ABC@!3" data-title="Join to earn great travel package with refer and earn programm" style="display:inline;border: 0px;color: #ddd !important;"></div>
                                <div>
                                    <small class="text-uppercase">Share Your Code</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
-->
    {{-- <div class="row">
        <div class="col-sm-12">
            <div class="shadow-sm p-3 rounded-10 mb-3 bg-white animate__animated animate__bounceInLeft">
                <div class="heading">
                    Action Zone
                </div>
                <div class="wizard-section">
                    <ul class="sbs sbs--border">
                        <li>
                            <div class="step">
                                <span class="description">Enterepreneurs Club</span>
                            </div>
                            <div class="mt-3">
                                <h6>12 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/1.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li class="active">
                            <span class="activetag">You Are Here</span>
                            <div class="step">
                                <span class="description">Initiator</span>
                            </div>
                            <div class="mt-3">
                                <h6>25 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/2.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Just you & me</span>
                            </div>
                            <div class="mt-3">
                                <h6>30 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/3.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">All Major Packages</span>
                            </div>
                            <div class="mt-3">
                                <h6>60 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/4.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Language Club</span>
                            </div>
                            <div class="mt-3">
                                <h6>70 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/5.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Club 100</span>
                            </div>
                            <div class="mt-3">
                                <h6>120 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/6.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">We love Caring</span>
                            </div>
                            <div class="mt-3">
                                <h6>100 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/7.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Healy Holistic Education</span>
                            </div>
                            <div class="mt-3">
                                <h6>120 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/8.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Franchies Investors Arena</span>
                            </div>
                            <div class="mt-3">
                                <h6>240 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/9.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>
                        <li>
                            <div class="step">
                                <span class="description">Sprinter</span>
                            </div>
                            <div class="mt-3">
                                <h6>400 Sales</h6>
                                <img style="width:50px;" class="rounded img-fluid"
                                    src="{{ asset('dashboard/images/zone/10.jpg') }}" alt="...">
                            </div>
                        </li>

                    </ul>


                </div>


            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="shadow-sm p-3 rounded-10 mb-3 bg-white animate__animated animate__bounceInLeft">
                <div class="heading">
                    Monthly Sales Growth
                </div>
                <!-- <div id="line-area-chart"></div> -->
                <div id="user_chart"></div>
            </div>
        </div>
       {{--
        <div class="col-lg-12 col-xl-12">
            <div class="shadow-sm p-3 rounded-10 mb-3 bg-white animate__animated animate__bounceInLeft">
                <div class="heading">
                    Monthly Referral Graph
                </div>
                <div id="column-chart"></div>
            </div>
        </div>
        {{-- <div class="col-lg-12 col-xl-6">
            <div class="shadow-sm p-3 rounded-10 mb-3 bg-white animate__animated animate__bounceInLeft">
                <div class="heading">
                    Monthly Conversion Rate
                </div>
                <div id="radialbar-chart"></div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-6">
            <div class="shadow-sm p-3 rounded-10 mb-3 bg-white animate__animated animate__bounceInLeft">
                <div class="heading">
                    Monthly Conversion Rate
                </div>
                <div id="donut-chart"></div>
            </div>
        </div> --}}
    </div>
@endsection
@section('scripts')
    <script>
        // return a promise
        function copyToClipboard(textToCopy) {
            // navigator clipboard api needs a secure context (https)
            if (navigator.clipboard && window.isSecureContext) {
                // navigator clipboard api method'
                return navigator.clipboard.writeText(textToCopy);
            } else {
                // text area method
                let textArea = document.createElement("textarea");
                textArea.value = textToCopy;
                // make the textarea out of viewport
                textArea.style.position = "fixed";
                textArea.style.left = "-999999px";
                textArea.style.top = "-999999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                return new Promise((res, rej) => {
                    // here the magic happens
                    document.execCommand('copy') ? res() : rej();
                    textArea.remove();
                });
            }
        }
        $(document).on('click', '.copy_code', function(e) {

            let val = $("#myInput").val();
            copyToClipboard(val)
                .then(() => {
                    console.log('text copied !')
                    alert('Code copy successfully');
                    toastr.success('Code copy successfully ');
                })
                .catch(() => console.log('error'));
        });
    </script>
 
    <script src="{{asset('dashboard/js/charts/apexcharts.min.js')}}"></script>
    <script>
        var options = {
            chart: {
                type: 'bar'
            },
            series: [{
                name: 'sales',
                data: [ <?php foreach ($sales_by_date as $key => $value) { echo $value.','; }?> ]
            }],
            xaxis: {
                categories: [ <?php foreach ($dates as $key => $value) { echo date('d', strtotime($value)).','; }?> ]
            }
        }
        var chart = new ApexCharts(document.querySelector("#user_chart"), options);
        chart.render();
    </script> 
    <script src="{{asset('dashboard/js/chart-apex.js')}}"></script>
    <script src="{{asset('dashboard/js/app.js')}}"></script>
@endsection

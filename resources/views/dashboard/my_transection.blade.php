@extends('dashboard.layout.default')
@section('title', 'Transaction')
@section('content')
    <div class="profile-box animate__animated animate__zoomInDown">
        <div class="row g-4 position-relative">
            <div class="col-sm-12">
                <div class="p-2 text-center">
                    <h3 class="text-white mb-1 mt-5">All Transactions</h3>
                    <p class="text-white-75 mb-1">Transactions of all QR Code Scans by Users(Carpanters)
                </div>
            </div>
        </div>
    </div>
    <div class="row position-relative  px-3" style="margin-top:-80px;">
        <div class="col-lg-12 mx-auto">
            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">
                <form method="get" action="{{ route('salesofficer.transection') }}">
                    <div class="card-body">
                        <h4 class="fw-bold"><i class="bi bi-funnel"></i> Transactions Filter</h4>
                        <hr />
                        <div class="row align-items-end g-3">
                            <div class="col">
                                <label for="inputPassword6" class="col-form-label">from</label>
                                <input type="date" id="inputPassword6" name="from" value="@if(isset($from)){{ $from }}@endif" class="form-control" aria-describedby="passwordHelpInline">
                            </div>
                            <div class="col">
                                <label for="inputPassword6" class="col-form-label">To</label>
                                <input type="date" id="inputPassword6" name="to" class="form-control" value="@if(isset($to)){{ $to }}@endif" aria-describedby="passwordHelpInline">
                            </div>
                            <div class="col">
                                <label for="inputPassword6" class="col-form-label">Select Dealers</label>
                                <select class="form-select" name="dealer_id" aria-label="Default select example">
                                    <option value="">Select Dealers</option>
                                    @foreach ($dealers as $dealer)
                                        <option value={{ $dealer->user_id }} @if(isset($dealer_id)){!! $dealer_id == $dealer->user_id? 'selected':'' !!}@endif>{{ $dealer->dealer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-search"></i> Find Delers</button>
                            </div>
                            <div class="col">
                                <a href="{{route('salesofficer.transection')}}"><button type="button" class="btn btn-success btn-lg">Reset</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">
                <div class="card-body">
                    @if (!$transaction->isEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr. No.</th>
                                        <th scope="col">Dealer's Name</th>
                                        <th scope="col">Customer's Name</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">QR</th>
                                        <th scope="col"> Points</th>
                                        <th>Transaction Date </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>@if($item->dealer_name != null){{ $item->dealer_name }}@else{{ '-NA-' }}@endif</td>
                                            <td>@if($item->user_name != null){{ $item->user_name }}@else{{ '-NA-' }}@endif</td>
                                            <td>@if($item->product_id != null){!! App\Products::modulename($item->product_id) !!}@else{{ '-NA-' }}@endif</td>
                                            <td>@if($item->qr_value != null){{ $item->qr_value }}@else{{ '-NA-' }}@endif</td>
                                            <td>@if($item->point != null){{ $item->point }}@else{{ '-NA-' }}@endif</td>
                                            <td>@if($item->created_at != null){{ date('d/M/Y', strtotime($item->created_at)) }}@else{{ '-NA-' }}@endif</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <h4>No transaction found under any of your dealer</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
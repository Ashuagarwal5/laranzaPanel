@extends('admin.layouts.default')
@section('title')
    Orders
    @parent
@stop
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
@stop
@section('content')

    <section class="content-header">
        <h1>Genrate Reports List</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Orders</li>
            <li class="active">Pending Orders List</li>
        </ol>
   
    </section>
    
    <div id="ajaxResponse"></div>
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />

<div class="row" id="month_filter">
<div class="whars-report">
<div class="col-sm-12">
<h4><p><strong>Month Wise Filter</strong></p></h4>
 <h5>&nbsp;</h5> 
</div>
	<form method="post" id="countryForm" action="{{route('generate_report')}}">
       	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="variable" value="month" />

<div class="col-sm-4">
 <label>Select Year</label>
 <div class="smart-forms">
 <label class="field select">
 <select id="year" name="year" class="form-control ">
	 <option value="">Select Year</option>
    @for ($i = 1990; $i <= 2025; $i++)
        <option value="{{ $i }}">{{ $i }}</option>
    @endfor
</select>
   <i class="arrow"></i>    
 </label>
 </div>
 </div>
	
<div class="col-sm-4">
 <label>Select Month</label>
 <div class="smart-forms">
 <label class="field select">
<select class="form-control" name="month">
 <option value="">Select Month</option>
 <option value="January">January</option>
 <option value="February">February</option>
 <option value="March">March</option>
 <option value="April">April</option>
 <option value="May">May</option>
 <option value="June">June</option>
 <option value="July">July</option>
 <option value="August">August</option>
 <option value="September">September</option>
 <option value="Octomber">Octomber</option>
 <option value="November">November</option>
 <option value="December">December</option>
 </select>
  
 
   <i class="arrow"></i>    
 </label>
 </div>
 </div>

 <div class="col-sm-1 text-center">

    <div class="form-group">
		<label>&nbsp;</label>
        <br>
      <button class="btn btn-success submit" id="preview1"> &nbsp; Generate Report &nbsp;</button>
    </div>
  </div>
 </form>

  
  <div class="clr"></div>
</div>
<div class="clr"></div>
</div>


@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
     <script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>

@stop

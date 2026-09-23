@extends('vendor/header')

{{-- Page title --}}
@section('title')
SelllerCompanyProfile
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

    <div class="container-fluid">
      <div class="row">
         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Welcome to {{$sellerdetails->company_name}}</h1>

<div class="panel" style="min-height: 570px;">
<div class="panel-heading"><h2 class="pull-left">Comming Soon</h2>

          <div class="clr">
          </div>
</div>
<div class="panel-body">
<div  class="account clearfix " style="margin-top: 170px;">
    
    
    <center><h1>Comming Soon</h1></center>

</div>
</div>
        </div>
      </div>
    </div>



    <!-- //Container End -->
@stop

{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->
   
<script src="{{ asset('assets/default/js/jquery.canvasjs.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/default/js/canvasjs.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
var tabData = JSON.parse('{!!$sale_value!!}');

</script>
<script src="{{ asset('assets/default/js/columnChart.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/default/js/seller.js') }}" type="text/javascript"></script>




    <!--page level js ends-->

@stop


@extends('vendor/header')

{{-- Page title --}}
@section('title')
{{$order->order_id}}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />

	<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>

	<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/pages/invoice.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/css/pages/toastr.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/vendors/toastr/css/toastr.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
     <link href="{{ asset('assets/css/skins/all.css') }}" rel="stylesheet" type="text/css"/>

@stop

{{-- Page content --}}
@section('content')
<div class="container-fluid">
      <div class="row">

         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Order List</h1>
           <h3 class="panel-title">
                                     <i class="livicon" data-name="rocket" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    <strong>Order:</strong>
                                  {{$order->order_id}}
                                </h3>
                                <div class=" kal pull-right" style=" margin-top: -24px;">
	                                 <img  src="{{ asset('assets/images/') }}/{{config('constants.paymenticon.'.$order->payment_method)}}" class="panel-title">
	                                
<button class="btn btn-sm btn-danger" type="button" style="margin-left: 10px;" id="backbtn" data-url='{{ URL::previous()}}'>
                                            <span class="btn-label">
                                                <i class="glyphicon glyphicon-chevron-left"></i>
                                            </span>
                                            Back
                                        </button>
                                </div>

<div class="panel">
<div class="panel-heading">


          <div class="clr"></div>
</div>
<div class="panel-body">
<div class="account clearfix" style="margin-top:10px;">


     <div class="tabbable-panel">
                    <!-- Tabbablw-line Start -->
                    <div class="tabbable-line">
                        <!-- Nav Nav-tabs Start -->
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a data-toggle="tab" href="#overview">
                                Overview </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#status">
                                Status </a>
                            </li>                            
                           
                       
                          @if(!empty($invoice))
                            <li>
                                <a id="shipmenta" data-toggle="tab" href="#shipment" onclick="gettracktable()">
                                Shipment </a>
                            </li>
                              @endif
                           
                            <li>
                                <a data-toggle="tab" href="#history" onclick="customeroldhistory()">
                                History </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#discussion">
                                Discussion </a>
                            </li>
                         
                            <li>
                                <a data-toggle="tab" href="#replacement">
                                Replacement </a>
                            </li>
                          
                        </ul>
                        <!-- //Nav Nav-tabs End -->
                        <!-- Tab-content Start -->
                        <div class="tab-content">
                                <div id="overview" class="tab-pane active">
                                  @include('vendor/order/overview')

                            </div>
                            <div id="status" class="tab-pane">
                               @include('vendor/order/status')

                            </div>
                            

                           
							 <div id="shipment" class="tab-pane">
                              
                                @include('vendor/order/shipment')
                              
                              <center></center>
                            </div>
                          


                           

                            <div id="history" class="tab-pane">
                             @include('vendor/order/history')
                            </div>

                          

                            <div id="discussion" class="tab-pane">
                               @include('vendor/order/discussion')

                             </div>
                             <div id="replacement" class="tab-pane">
                               @include('vendor/order/replacement')

                            </div>
                           
                           
                           
                           
                            <!-- Tab-content End -->
                        </div>
                        <!-- //Tabbable-line End -->
                    </div>
                    <!-- Tabbable_panel End -->
                </div>
            </div>
   <div class="clr"></div>
        </div>
</div>
</div>
        </div>
      </div>
    </div>


        
    @stop

{{-- page level scripts --}}
@section('footer_scripts')
 <script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"
            type="text/javascript"></script>
             <script src="{{ asset('assets/vendors/intl-tel-input/js/intlTelInput.min.js') }}"
            type="text/javascript"></script>
<script src="{{ asset('assets/vendors/toastr/js/toastr.min.js') }}" ></script>
<script src="{{ asset('assets/js/icheck.js') }}" ></script>

<!--
  <script src="{{ asset('assets/js/pages/validation.js') }}" type="text/javascript"></script>
-->

    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>

    

    <script>

  $('input').iCheck({
    checkboxClass: 'icheckbox_minimal-green',
      radioClass: 'iradio_minimal-green',
    increaseArea: '20%' // optional
  });
  
	var discussionurl='{!! route('seller.orders.ordercomment') !!}/'+'{{$order->order_id}}';
	var statusurl='{!! route('seller.orders.orderstatus') !!}/'+'{{$order->order_id}}';
	var gifimageurl = '{{ URL::to('assets/images/103.gif') }}';
	var stateurl = '{{ URL::to('sellerpanel/orders/state/') }}';
	var remainstateurl='{{ URL::to('sellerpanel/orders/remainstate/') }}';

	var generateinvoiceurl='{!! route('seller.orders.generateinvoice') !!}/'+'{{$order->order_id}}';	
	var shipmenttrack = '{{ URL::to('sellerpanel/orders/'.$order->order_id.'/track') }}';
	var emailinvoiceurl = '{{ URL::to('sellerpanel/orders/'.$order->order_id.'/emailinvoice') }}';
	var shipmeturl= '{{ URL::to('sellerpanel/orders/'.$order->order_id.'/shipment') }}';
	var customerhistoryurl='{!! route('seller.orders.customerhistory') !!}/'+'{{$order->member_id}}';
	

    </script>
<script src="{{ asset('assets/js/order.js') }}" ></script>



@stop

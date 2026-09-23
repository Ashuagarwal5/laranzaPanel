<!DOCTYPE html>
<html>
<head>
	 <meta charset="UTF-8">
    <title>
        @section('title')
          {{$order->order_id}}-Invoice
        @show
    </title>
     <link href="css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/css/pages/invoice.css') }}" rel="stylesheet" type="text/css"/>
<style>

.bgcolor{
 background-color: #eee;
    border-bottom-left-radius: 6px;
    border-top-left-radius: 6px;
    padding: 15px 15px 23px;
    text-align: right;
}
.bg-primary {
    color: #fff;
}

body {
    color: #333;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 11px;
    line-height: 1.42857;
}

.panel {
	box-shadow:none;
	border:none;
	font-size:11px !important;
	}
td {
 padding: 6px;
font-size:11px !important;
}

.clr {
	clear:both;
	}
.header {
	padding:10px 0;
	 background:#ccc;
	}
@media print {
      .header {
	    padding:10px 0;
        background-color: #ccc !important;

      }
#invoice {
	    padding:10px 0;
        background-color: #ccc !important;

      }
.table-responsive th {
	    padding:10px 0;
        background-color: #ccc !important;

      }
 #footer-bg {
	    padding:10px 0;
        background-color: #ccc !important;

      }
    }
</style>
<body>
<!--
<div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="livicon" data-name="money" data-size="14" data-loop="true" data-c="#fff" data-hc="#fff"></i> Invoice from <bold>Mobile E Mart</bold></h3>
                             <span class="pull-right">

                                    </span>
                            </div>
-->
<div class="panel-body" style="padding:0;margin:0 auto; width:100%;">
                                <div class="row" style="padding: 15px;margin-top:5px;">
                                <div  class="header">
                                    <div class="col-md-6 pull-left">
                                      
                                      <img src="{{ asset(config('constants.admin.logo')) }}" width="200" alt="logo" height="65">
                                      
                               
                                    </div>
                                    <div class="col-md-6 pull-right">
                                        <div class="pull-right">
                                            <strong></strong><br>
                                            <strong>Bazaar Mitra</strong><br>
                                            3rd Floor ,253, New Aatish Market
                                           
                                            Mansarover<br>
                                            support@bazzarmitra.com.com<br/>
                                            <strong>VAT No:</strong>08453132120<br/>
                                            <strong>CST No:</strong>065451213232
                                        </div>
                                    </div>
                                     <div class="clr"></div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                           
                           
                           
                           
                                <div class="row" style="padding: 10px 0;">
                                    <div class="col-md-9 col-xs-6" style="margin-top:5px;">
                                        <strong>Invoice To:</strong><br>
                                         {{ucfirst($order->first_name)}} {{ucfirst($order->last_name)}}<br>
                                        {{$order->address}}<br>
                                        {{ucfirst($order->city)}}, {{$order->pincode}}<br>
                                        {{ucfirst($order->st_name)}}<br>

                                        Contact No: {{$order->phone}}
                                    </div>
                                    <div class="col-md-3 col-xs-6" >
                                        <div id="invoice" style="text-align:center; background:#ccc; border-radius:4px;padding: 5px;">
	                                        @if($invoice)
                                            <h4><strong>Invoice: {{$invoice->invoice_id}}</strong></h4>
                                            @endif
                                            <h4><strong>Order: {{$order->order_id}}</strong></h4>
                                            <h4><strong>{{date("d M ,Y",strtotime($invoice->created_at))}}</strong></h4>
                                        </div>
                                    </div>
                                </div>
                                
                             
                             
                                <div class="row">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr style="background:#ccc;">
                                                    <th>SI No.</th>
                                                    <th>Details</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price(₹)</th>
                                                    <th>Tax%</th>
                                                     <th>Tax Amount(₹)</th>
                                                     <th>Discount</th>
                                                    <th>NetSubtotal(₹)</th>
                                                </thead>
                                              <tbody>
	                                            @foreach($OrderProduct as $key=> $item)   
	                                              
                                                <tr>
                                                    <td>{{$key+1}}.</td>
                                                    <td>{{$item->product_name}}</td>
                                                    <td>{{(int)@$item->quantity_order}}</td>
                                                    <td>₹{{number_format(@$item->price,2)}}</td>
                                                     <td>{{$item->tax_percentage}}%</td>
                                                      <td>₹{{number_format( $item->tax_amount, 2, '.', '') }}</td>
                                                 
                                                      <td>₹{{number_format(@$item->base_price - $item->price,2)}}</td>
                                                    
                                                      <td>₹{{number_format(@$item->row_total,2)}}</td>
                                                </tr>
                                             
                                               @endforeach
                                              
                                                 <tr>
                                                    <td></td>
                                                    <td></td>
                                                   <td></td>
                                                    <td></td>
                                                     <td></td>
                                                    <td></td>
                                                    <td>Net Total</td>
                                                   <td>₹{{number_format($order->sub_total,2)}}</td>
                                                </tr>
                                                
                                                     <tr>
                                                    <td></td>
                                                    <td></td>
                                                   <td></td>
                                                    <td></td>
                                                     <td></td>
                                                    <td></td>
                                                    <td>Discount</td>
                                                   <td>₹{{number_format($order->discount_amount,2)}}</td>
                                                </tr>
                                                <tr>
													  <tr>
                                                    <td></td>
                                                    <td></td>
                                                   <td></td>
                                                    <td></td>
                                                     <td></td>
                                                    <td></td>
                                                    <td>Tax </td>
                                                   <td>₹{{number_format($order-> tax_amount ,2)}}</td>
                                                </tr>
                                                
                                           
                                               
                                              
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                  <td></td>
                                                    <td></td>
                                                     <td></td>
                                                    <td></td>
                                                    <td><strong>TOTAL</strong></td>
                                                     <td><strong>₹{{number_format($order->grand_total,2)}}</strong></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        
                                        
                                        
                                            </table>
                                        </div>
                                    </div>
                              
                              
                              
                                </div>
                                <div class="panel panel-success" style="background:#ccc;padding:10px;" id="footer-bg">
                                    <div class="row">
                                        <div class="col-md-5 pull-left">
                                            <strong>Payment Details</strong><br>
                                            <strong>
                                            @if($payment->payment_method =='Pay u' or $payment->payment_method=='Paytm' )
                                            Online Payment(Credit Card,Debit Card,Net Banking) 
                                            @else
                                            Cash on delivery 
                                            @endif
                                            </strong><br>
                                                                                    </div>
                                        <div class="col-md-5 pull-right">
                                            <div class="pull-right">
                                               <br>
                                                                                            </div>
                                        </div>
                                    </div>

                                </div>
                           
                           
                           
                            </div>
                          
                          
                          
                            </div>
                           
                           
                           
                            </div></body>
</html>

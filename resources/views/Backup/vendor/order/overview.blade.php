<div class="panel-body" style="border:1px solid #ccc;padding:0;margin:0;">
                                <div class="row" style="padding: 15px;">
                                    @if($order->state == 'processing' and $order->status== 'processing')
                                    <div class="col-md-6 col-xs-6" style="margin-top:5px;">
									@else
									<div class="col-md-9 col-xs-6" style="margin-top:5px;">	
									@endif	
										
                                      <strong>Ship To:</strong><br>
                                        {{ucfirst($order->first_name)}} {{ucfirst($order->last_name)}}<br>
                                       
                                            {{ucfirst($order->address)}} <br>
                                        {{ucfirst($order->city)}}, {{$order->pincode}}<br>                                       
                                        {{ucfirst($order->st_name)}}<br>                                        
                                        Contact NO: {{$order->phone}}
                                    
                                      
                                        </div>
                                      
                                       <div class="col-md-3 col-xs-6" style="margin-top:5px;">
                     
                                        
                                       </div>
                                  
                                   
                                    <div class="col-md-3 col-xs-6 " style="padding-right:0">
										
                                        <div id="invoice" style="background-color: #eee;text-align:right;padding: 15px;padding-bottom:23px;border-bottom-left-radius: 6px;border-top-left-radius: 6px;" class="{{config('constants.invoicecss.'.$order->status)}}">
                                           
                                            <h4><strong>Order: {{$order->order_id}}</strong></h4>
                                            <h4><strong> {{date("d M Y",strtotime($order->created_at))}}</strong></h4>
                                                {{config('constants.Orderstate.'.$order->status)}}
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row" style="padding:15px;">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>SI No.</th>
                                                    <th>Details</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price(₹)</th>
                                                    <th>Tax%</th>
                                                     <th>Tax Amount(₹)</th>
                                                     <th>Discount</th>
                                                    <th>NetSubtotal(₹)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
												<? 	$netotal=0;$tax=0;?>
	                                            @foreach($OrderProduct as $key=> $item)   
	                                              
                                                <tr>
                                                    <td>{{$key+1}}.</td>
                                                    <td>{{$item->product_name}}</td>
                                                    <td>{{(int)@$item->quantity_order}}</td>
                                                    <td>{{config('constants.frontend.currency')}}{{number_format(@$item->price,2)}}</td>
                                                     <td>{{$item->tax_percentage}}%</td>
                                                      <td>{{config('constants.frontend.currency')}}{{number_format( $item->tax_amount, 2, '.', '') }}</td>
                                                 
                                                      <td>{{config('constants.frontend.currency')}}{{number_format(@$item->base_price - $item->price,2)}}</td>
                                                    
                                                      <td>{{config('constants.frontend.currency')}}{{number_format(@$item->row_total,2)}}</td>
                                                </tr>
                                                
                                                <? $netotal = $netotal+$item->price;
                                                $tax = $tax+$item->tax_amount;
                                                ?>
                                             
                                               @endforeach
                                              
                                                 <tr>
                                                    <td></td>
                                                    <td></td>
                                                   <td></td>
                                                    <td></td>
                                                     <td></td>
                                                    <td></td>
                                                    <td>Net Total</td>
                                                   <td>{{config('constants.frontend.currency')}}{{number_format($netotal,2)}}</td>
                                                </tr>
                                                
                                                     <tr>
                                                    <td></td>
                                                    <td></td>
                                                   <td></td>
                                                    <td></td>
                                                     <td></td>
                                                    <td></td>
                                                    <td>Discount</td>
                                                   <td>{{config('constants.frontend.currency')}}{{number_format($order->discount_amount,2)}}</td>
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
                                                   <td>{{config('constants.frontend.currency')}}{{$tax}}</td>
                                                </tr>
                                                
                                           
                                               
                                              
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                  <td></td>
                                                    <td></td>
                                                     <td></td>
                                                    <td></td>
                                                    <td><strong>TOTAL</strong></td>
                                                     <td><strong>{{config('constants.frontend.currency')}}{{number_format($netotal+$tax,2)}}</strong></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        
                                        
                                        
                                        </div>
                                    </div>
                                </div>
                                <div style="background-color: #eee;padding:15px;" id="footer-bg">
                                                                       <hr>
                                                                                                       </div>
                            </div>

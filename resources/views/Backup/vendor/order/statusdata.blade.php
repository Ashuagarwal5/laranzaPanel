                                                    <table class="table table-bordered table-striped" id="users">

                                                        <tr>
                                                            <td>Current Status</td>
                                                            <td>
                                                              {{ ucfirst($Order->status)}}
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>Order Date</td>
                                                            <td>
                                                                 {{date("d M Y",strtotime($Order->order_date))}}
                                                            </td>

                                                        </tr>
                                                        <tr>
                                                            <td>Payment Amount / Payment Method</td>
                                                            <td>
                                                        {{ $Order->order_currency_code}}  &nbsp; {{ $Order->grand_total}}  / {{ ucfirst($Order->payment_method)}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                               Awaiting Shipment Date
                                                            </td>
                                                            <td>
                                                               {{date("d M Y",strtotime($Order->awaiting_shipment_date))}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Dispach Date</td>
                                                            <td>
                                                              @foreach($Order->shipment as $value)
                                                              
                                                               {{date("d M Y",strtotime($value->dispach_date))}} &nbsp; &nbsp;
                                                              @endforeach  
                                                            </td>
                                                        </tr>
                                                        
                                                        
                                                    </table>
                                  
                                  
                                  
                               

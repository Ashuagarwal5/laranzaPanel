 <div id="tab4" class="tab-pane fade" >
                        <div class="row">
                            <div class="col-md-12 pd-top">
                             
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped" id="users">

                                                        <tr>
                                                            <td>Total Uploaded Products</td>
                                                            <td>
                                                              {{ $noOfProduct }}
                                                            </td>

                                                        </tr>
                                                      
                                                        <tr>
                                                            <td>Total Orders Received</td>
                                                            <td>
                                                                {{ $noOfOrder }}
                                                            </td>
                                                        </tr>
                                                       
                                                        <tr>
                                                            <td>Total Cancelled Orders</td>
                                                            <td>
                                                                 {{ $noOfOrderCancel }}
                                                              
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Orders Pending for Ship</td>
                                                            <td>
                                                              {{ $noOfOrderShipPending }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Total Shipped Orders</td>
                                                            <td>
                                                               {{ $noOfOrderShip }}
                                                            </td>
                                                        </tr>
                                                       @if($seller->approve_date != '0000-00-00')
                                                         <tr>
                                                            <td>Approve Date</td>
                                                            <td>
															
															{{date("d M Y",strtotime($seller->approve_date))}}	
                                                            
                                                            </td>
                                                        </tr>
                                                        @endif	 
                                                        
                                                    </table>
                                                </div>
                                            </div>
                                      
                                    
                                    
                                    
                           
                            </div>
                        </div>
                    </div>
               
               
               

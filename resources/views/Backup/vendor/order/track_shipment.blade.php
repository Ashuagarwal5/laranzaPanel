
 <div class="row" style="text-align:center">
 
 <div class="col-sm-12">
 <table class="table table-bordered " id="productimage">
                        <thead >
                        <tr class="filters">
                          
                            <th style="text-align:center">Courier Service</th>
                           
                            <th style="text-align:center">Tracking Details</th>
          
                            <th style="text-align:center">shipment Date</th>
          
                        </tr>
                        </thead>
                        
                   






<tbody >
	 @for($i=0;isset($track[$i]);$i++)	
                                            <tr>
                                                
                                                
                                                <td>{{ $track[$i]->courier_name }}</td>
                                              
                                                <td>
                                                   {{ $track[$i]->tracking_detail }}
                                                </td>
                                                 <td>
                                                   {{ date('d M Y', strtotime($track[$i]->created_at)) }}
                                                </td>
                                                 <td>
                                                   {{ $track[$i]->is_mail }}
                                                </td>
                                            </tr>
             @endfor                         
                                           
                                          
                                        </tbody>
                                    
 </table>
</div>
</div>


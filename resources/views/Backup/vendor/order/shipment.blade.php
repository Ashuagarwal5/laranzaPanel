
                <!--main content-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Shipment History
                                </h3>
                                
                                
                                
                                
                                <div class=" kal pull-right">
									
									
						
									
									
                                <!-- Tabs -->
                                <ul class="nav panel-tabs" id="shipmenttracklist">
	                              
                                    <li class="">
                                        <a data-toggle="tab" href=""   aria-expanded="true"></a>
                                    </li>

                                   
                                    <li class="active">
                                        <a data-toggle="tab" href="#0"    aria-expanded="true"></a>
                                    </li>
                                   
                                </ul>
                            </div>
                            </div>
  <div class="panel-body">
	  
	  
	  
                        <form method="post" class="ajaxformshipment" id="example-form" >
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

                       
       <div class="row">
		   
		   <div class="col-md-4">             
                             <div class="form-group">
                                <label for="validate-text">Courier Service</label>

                                <div class="input-group" >
                                    <select id="courier_name" name="courier_name" class="form-control" size="1"  required>
                                                                <option value="">Please select</option>
                                                              @foreach($couriernames as $key=> $value)
                                                                <option value="{{ $value->courier_name}}" >{{ $value->courier_name}}</option>
                                                              @endforeach
                                                            </select>
                                                           
                                           
                                            
                                </div>
                                <div class="courier_name"></div>
                            </div>
                           
              </div>  
                
             <div class="col-md-3">                     
                             <div class="form-group">

                                <label for="validate-text">Tracking Details</label>

                                <div class="input-group">
                                    <input type="text" class="form-control" name="tracking_detail" id="tracking_detail"
                                           placeholder="Enter Tracking Details" required>
                                          

                                </div>
                               <div class="tracking_detail"></div>
                            </div>
                        </div>     
                            
              <div class="col-md-2">       

            
                            <div class="form-group">
                                <label for="validate-phone">Notify By Mail</label>

                            
                                   <label >
                                            <input type="radio" name="is_mail" value="Yes"> Yes
                                   </label>
                                     &nbsp;
                                   
                                     <label>
                                           <input type="radio" name="is_mail" value="No" checked> No
                                        </label>
                                
                            </div>
                         </div>        


            <div class="col-md-2">          
                            <div class="form-group col-sm-10">
								    <label for="validate-phone"></label>
                                <div class="input-group">
                               
                                   <button type="submit"  class="btn btn-primary btn-block btn-md btn-responsive submitbtn">
										@lang('button.save')
										</button>        
                                     
                            
                                  </div>
                              
                            </div>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        </div>    
            
            
            
             </div>          
       </form>
                   
                   
                   
                   
                    </div>
             
                 
             
             
                            <div class="tab-content" id="slim1">
	                            <span class="hide" id="">

	                            </span>
	                            
	                             <div class="tab-pane text-justify active trackingdetailfull">
                                 <hr>
		                          <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Tracking Detail</th>
                           <th>Shipped On</th>
                          
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table> 
		                            <div class="track">
                  
                                    </div>
		                           
		                          
                                </div>

                                </div>
                        </div>
                    </div>
                </div>

<!--main content ends-->


<script>
	
	

       function gettracktable(){
		
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: shipmenttrack,
                columns: [
					{ data: 'ord_shp_id', name: 'ord_shp_id' },
					{ data: 'courier_name', name: 'courier_name' },
					{ data: 'tracking_detail', name: 'tracking_detail' },
					{ data: 'add_date', name: 'created_at' },

                   	
                ]
            });
           
        }

  function customeroldhistory()
  {
	  
	
	 $('#customerhistory').children('tbody').html("");
            var table2 = $('#customerhistory').DataTable({
                processing: true,
                serverSide: true,
                bDestroy: true,
                ajax: customerhistoryurl,
                columns: [
					{ data: 'order_id', name: 'order.order_id',render:function(data,type,row,meta){ return orderdetail(data,type,row,meta)}},
					{ data: 'shipping_name', name: 'order_address.first_name' },
					{ data: 'grand_total', name: 'order.grand_total',render:function(data){return "₹ "+parseFloat(data).toFixed(2)} },
					{ data: 'method', name: 'order_payment.payment_method'},
					{ data: 'status', name: 'order.status'},
					{ data:"order_date",name: 'order.created_at',render:function (data){ return data}},

                    
                ],
            });
            
	  
	  }
    function orderdetail(data,type,row,meta)
      {
	      var orderdetailurl='{!! route('seller.orders.show') !!}/'+data;
	      var str='<a href="'+orderdetailurl+'">'+data+'</a>';
	      if(row.invoice_id)
	      {
		     str=str+'<br>'+row.invoice_id;
	      }


	      return str;
      }
        



 </script>

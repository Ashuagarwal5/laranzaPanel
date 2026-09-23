<section class="content">
    <div class="row">
		
		<br/>
		<div class="col-md-4">
                        <!--md-6 starts-->
                        <!--form control starts-->
                        <div class="panel panel-primary" id="hidepanel6">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="share" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                                    Create New Purchase
                                </h3>
                                
                            </div>
                            <div class="panel-body">
                                <form class="form-horizontal ajaxform" id="addapurchase" role="form" method="post" action="{{ URL::to('admin/orders/'.$order->increment_id.'/create') }}" id="ajax">
									 <!-- CSRF Token -->
									<input type="hidden" name="_token" value="{{ csrf_token() }}" />
									<input type="hidden" name="order_id" value="{{$order->increment_id}}" />
	                                
                                     <div class="form-group">
                                        <label>Item</label>
                                       
                                        <select class="form-control" name="item" id="item"  required>
	                                        <option value="">Select Item</option>
	                                        @if ($order->total_qty_ordered > 1)
	                                         @foreach($order->items->complexObjectArray as $key=> $item)
												<option value="{{ $item->item_id}}">{{ $item->name}}</option>
                                            @endforeach
											@else
												<option value="{{ $order->items->complexObjectArray->item_id}}">{{ $order->items->complexObjectArray->name}}</option>
											@endif

                                        </select>
                                    </div>
                                                           
                                    
                                     <div class="form-group">
                                        <label>Supplier</label>
                                        <select class="form-control" name="supplier" id="supplier" required>
	                                        <option value="">Select Supplier</option>
	                                        @foreach($vander as $key=> $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                           
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Purchase Rate</label>
                                        <input class="form-control" name="rate" id="rate" placeholder="Purchase rate" required>
                                    </div>
                                    
                                    
                                    <div class="form-group">
                                           <div class="col-sm-offset-2 col-sm-4">
<!--
											<a class="btn btn-danger" href="{{ route('vanders') }}">
												@lang('button.cancel')
											</a>
-->
											<button type="submit" class="btn btn-success submitbtn">
												@lang('Submit')
											</button>
                                         </div>
                                 </div>
                                   
                                </form>
                                
                                
                                
                            </div>
                        </div>
                    </div>
		
		
		
		
                
        <div class="col-lg-8 hide" id="purchaseotherhistory" >
            <div class="panel panel-primary ">
               <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i><span class="purchaseotherhistory_title"></span>                    </h4>
                </div>
                 <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Price(₹)</th>
                           
                            <th>Actions</th>
                        </tr>                        
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="modal fade in" id="responsive" tabindex="-1" role="dialog" aria-hidden="false" style="display:none;">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Product Images</h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Some More Images</h4>
                                       <p>
										<img src="#">
                                       </p>
                                        <p>
                                            <img src="#">
                                        </p>
                                        <p>
                                            <img src="#">
                                        </p>
                                        <p>
                                            <img src="#">
                                        </p>
                                        <p>
                                            <img src="#">
                                        </p>
                                        <p>
                                            <img src="#">
                                        </p>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
        
        <div class="col-lg-12">
            <div class="panel panel-primary ">
               <div class="panel-heading">
                    <h4 class="panel-title"> <i class="livicon" data-name="users-add" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Purchase History
                    </h4>
                </div>
                 <div class="panel-body">
                    <table class="table table-bordered " id="table2">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Supplier</th>
                            <th>Item</th>
                            <th>Purchase Rate</th>
                            <th>Status</th>
                        </tr> </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>               
            </div>
                
                
        </div>
          
        
    </div>
    <!-- row-->
</section>

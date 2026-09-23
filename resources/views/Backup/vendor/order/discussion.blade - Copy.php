
                <!--main content-->
                <div class="row">
                    <div class="col-md-12">
                         <div class="panel panel-primary ">
                            <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Discussion
                    </h4>
                    <div class="pull-right">

						<a class="btn btn-sm btn-default" data-toggle="modal" data-href="#discussion_create" href="#discussion_create"><span class="glyphicon glyphicon-plus"></span>@lang('button.create')</a>
                    </div>
                </div>
                            <div class="panel-body">
                                <!--timeline-->
                                <div class="row discussioncomment">
                                    
                                </div>
                                <!--timeline ends-->
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="modal fade in" id="discussion_create" tabindex="-1" role="dialog" aria-hidden="false" style="display:none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Discussion</h4>
                            </div>
                            
                      <form id="updatestatus" method="post" action="{{ URL::to('admin/orders/updatestatus/'.$order->order_id) }}" >   
                           							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
                           
                            <div class="modal-body">
                                <div class="row">
                                 
                                    <div class="col-md-12">
                                      <h4>State</h4>
                                        <p>
                                            <select name="state" id="state" class="form-control" required>
										  <option value="">select</option>
										  	  @foreach((config('constants.Orderstatus')) as $key =>  $value)	
                                         
                                            <option value="{{ $key }}" >{{ ucfirst($key) }}</option>
                                         
                                                @endforeach
                                            
                                            </select>
                                        </p>
                                         <h4>Status</h4>
                                        <p>
                                            <select name="status"  id="ch_user1" class="form-control" required>
										
										     <option>select</option>
                                         
                                           
                                            
                                            
                                            
                                            
                                            </select>
                                        </p>
                                       
                                       
                                        <h4>Message</h4>
                                        <p>
                                            <textarea name="comment" id="textarea" class="form-control" maxlength="225" rows="2" placeholder="Message" required></textarea>
                                        </p>
                                        <p>
                                             <div class="form-group">
                                        <label class="checkbox-inline">
                                            <input type="checkbox">Send Email</label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox">Send Message</label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox">Notify For Front</label>
                                    </div>
                                        </p>

                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn">Close</button>
                                <button type="submit" class="btn btn-primary submitbtn" >Save changes</button>
                            </div>
                       
                       </form>   
                       
                        </div>
                    </div>
                </div>

<!--main content ends-->



<div class="panel panel-primary">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">
                            <i class="livicon" data-name="wrench" data-size="20" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                          Tag Information 
                        </h4>
                                
                    </div>
                    <div class="panel-body border">
                                             
                                             
                                         <br>
                                         <br>
                                         
                                           
                                                <form action="{{ URL::to('admin/product/'.$product->pro_id.'/tag') }}" id="tag-form" method="post"  class="form-horizontal form-bordered ajaxformtag">
                                                   <input type="hidden" name="_token" value="{{ csrf_token() }}" /> 
                                                   
                                                   
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="example-text-input">Tag</label>
                                                        <div class="col-md-6">
                                                            <input type="text"  name="tag_name" id="tag_name" class="form-control" value="" placeholder="tag" Required>
                                              <input type="hidden" name="tag_id" id="tag_id" value="">
                                              
                                                    </div>
                                                    </div>
                                                   
                                                  
                                                  
                                                    <div class="form-group form-actions">
                                                        <div class="col-md-9 col-md-offset-3">
                                                              <button type="submit"  class="btn btn-effect-ripple btn-primary submitbtn">
										@lang('button.save')
										</button> 
                                                            
                                                           
                                                        </div>
                                                    </div>
                                                </form>
                                           
                                            </div>
                        
                        <hr>
                         <div class="tagrecord">
                   
                   
                   
                         </div>                
                </div>
        
        


                    <div id="tab1" class="tab-pane fade active in">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">

                                            Company Profile
                                        </h3>

                                    </div>
                                    <div class="panel-body" style="padding-top:15px !important;">
                                        <div class="col-md-4" style="border-right:solid 1px #ddd;">
                                            <div class="img-file">
                                                @if($seller->company_logo)
                                                    <img src="{{ URL::to(App\Helpers\Thumbnail::image("/seller/$seller->user_id/$seller->company_logo","200","200","ff=ffffff"))}}" alt="profile pic" class="img-max">
                                                @else
                                                    <img src="http://placehold.it/200x200" alt="profile pic">
                                                @endif
                                            </div>
                                           <div style="margin-top:30px; border-top:solid 1px #ddd; padding-top:15px;">
												
                                            
										  <form method="post" class="sellerstatus" id="example-form" >
											<input type="hidden" name="_token" value="{{ csrf_token() }}" />            
											<input type="hidden" name="auto_approval" value="Yes" />            
                       
                       
                             
                           <div class="form-group">
                                <label for="validate-text">Status</label>

                                <div class="input-group col-md-12" >
                                    <select id="status" name="status" class="form-control " size="1"  >
                                                              <option value="">Please select</option>
                                                             
                                                                <option value="Approve" {{ $seller->status == 'Approve' ?       'selected="selected"' : '' }}>Approve</option>
                                                                <option value="Disapprove" {{ $seller->status == 'Disapprove' ?       'selected="selected"' : '' }}>Disapprove</option>
                                                                <option value="Rejected"  {{ $seller->status == 'Rejected' ?       'selected="selected"' : '' }}>Reject</option>
                                                            
                                                            </select>
                                                           
                                           
                                            
                                </div>
                                <div class="status"></div>
                            </div>
                            <!--
                           <div class="form-group">
                                <label for="validate-text">Product Approval</label>

                                <div class="input-group col-md-12" >
									<input type="radio" value="Yes" name="auto_approval"{{ $seller->auto_approval == 'Yes' ?       'checked="checked"' : '' }}>Auto Approval
									<input type="radio" value="No" name="auto_approval" {{ $seller->auto_approval == 'No' ?       'checked="checked"' : '' }}>Not Auto Approval
                                </div>
                                <div class="status"></div>
                            </div>
                            
                          
                                -->      
                                            
                                            

                                          <button type="submit" class="btn submitbtn btn-info">Save</button>
                                          </br>
                                           </br>
                                     
                                   </form>     
                                   
                                    </br>    
                                            </div>
                        
                                        </div>
                                        <div class="col-md-8">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped" id="users">

                                                        <tr>
                                                            <td>Comapny Name</td>
                                                            <td>
                                                                {{ $seller->company_name }}
                                                            </td>

                                                        </tr>
                                                        
                                                        
                                                         <tr>
                                                            <td>Address</td>
                                                            <td>
                                                                {{ $seller->address }}
                                                            </td>
                                                        </tr>
                                                         <tr>
                                                            <td>Company Telephone</td>
                                                            <td>
                                                                {{ $seller->company_telephone }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Town/Area/Landmark</td>
                                                            <td>
                                                                {{ ucfirst($seller->city) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                               City
                                                            </td>
                                                            <td>
                                                                {{ $seller->st_name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                               Industry/Category
                                                            </td>
                                                            <td>
                                                                {{ $seller->category_name }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Postcode/Zipcode</td>
                                                            <td>
                                                                {{ $seller->postcode }}
                                                            </td>
                                                        </tr>
                                                       
                                                        <tr>
                                                            <td>Status</td>
                                                            <td>
                                                            
                                                            {{ $seller->status }}
                                                           
                                                           
                                                            </td>
                                                        </tr>
                                                       
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    

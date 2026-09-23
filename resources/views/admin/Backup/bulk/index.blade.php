<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Bulk Upload</h4>
</div>

		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
                <div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
								<thead>
									<tr>
									<th>Field Name</th>
									<th>Data</th>
									</tr>
								</thead>
							<tbody>

						      <tr>
						        <td>All Categories</td>
						        <td><a href="{{ route('admin.bulk.get.all.cat') }}"  >click here to download CSV</a></td>
						      
						      </tr>
						      
						      <tr>
						        <td>All Sellers</td>
						        <td><a href="{{ route('admin.bulk.get.all.seller') }}"  >click here to download CSV</a></td>
						      
						      </tr>
						      
						      
						      <tr>
						        <td>Sample File for Bulk Upload</td>
						        <td><a href="{{ asset('assets/product_bulk_upload_sample.csv') }}"  download >click here to download CSV</a></td>
						      
						      </tr>
						      
						      

							</tbody>
					</table>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                      <form method="post" id="bulk-product-form123"  enctype="multipart/form-data" class="ajaxformclass" action="{{ route('admin.pro.bulk.upload.post') }}" >

							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

                           
                              <div class="alert " role="alert" style="display:none; width:92%;">
									<span class="close">×</span>
									<span class="ajax_message"> </span>
									</div>
                                    
                                    
                                    

                              	<div class="form-group has-success">
                                <label for="validate-text">CSV File  </label><br/>

                                <div class="input-group">
                                    <input accept="csv/*" type="file" class="form-control" name="csv_file" value="" id="validate-text">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
                                <span> </span>
								<div class="has-error">
									  {!! $errors->first('csv_file', '<span class="help-block">:message</span>') !!}
								</div>
                            </div>

                            
                            
                            
                            
                            <input type="hidden" name="re" >
                            
                            
                            		
							<div class="col-md-12 mar-10">
								<div class="col-xs-4 col-md-4"></div>
								<div class="col-xs-6 col-md-4">
                                   <button type="submit"  class="btn btn-primary btn-block btn-md submit">
										Upload
									</button>
								</div>
								
                            </div>
                       </form>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    </div>
				</div>
			</div>
		</div>

<div class="modal-footer">
	<button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

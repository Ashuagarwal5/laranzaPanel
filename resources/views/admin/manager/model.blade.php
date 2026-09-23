@extends('admin/layouts/default')

{{-- Web site Title --}}
@section('title')
    Brand Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
       
 <link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/> 
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">
@stop

{{-- Content --}}
@section('content')
<section class="content-header">
    <h1>
Brand Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                @lang('general.dashboard')
            </a>
        </li>
        <li>Brand Manager</li>
        <li class="active">
         Add 
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="livicon" data-name="wrench" data-size="20" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                                Add Model
                          
                        </h4>
						<div class="pull-right">
							<a href="{{ route('brand') }}" class="btn btn-sm btn-danger" ><span class="btn-label">
											<i class="glyphicon glyphicon-chevron-left"></i>
							</span><span style="margin-left:8px">Back</span></a>
						</div>

                                
                    </div>
                    <div class="panel-body">
                       
                       
                          
									<form  id="model_form" method="post"  class="form-horizontal form-bordered ajaxformtag">
										<input type="hidden" name="_token" value="{{ csrf_token() }}" /> 


										<div class="form-group">
										<label class="col-md-3 control-label" for="example-text-input">Model</label>
										<div class="col-md-6">
										<input type="text"  name="model_name" id="model_name" class="form-control" value="" placeholder="Model" Required>
										
										</div>
										</div>



										<div class="form-group form-actions">
										<div class="col-md-9 col-md-offset-3">
										<button type="submit"  class="btn btn-effect-ripple btn-primary submitbtn">
										Save
										</button> 


										</div>
										</div>
									</form>	
									
									<div class="tagrecord">

									<table class="table table-bordered " id="table1">
									<thead>
									<tr class="filters">
									<th>Model Name</th>

									<th>Actions</th>
									</tr>
									</thead>
									<tbody>
									</tbody>
									</table>
									</div>

									</div>     
                                           
                       
                    </div>
                </div>
        </div>
    </div>
    <!-- row-->
</section>
@stop
@section('footer_scripts')

	<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}"type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js') }}"type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/intl-tel-input/js/intlTelInput.min.js') }}"type="text/javascript"></script>
	<script src="{{ asset('assets/js/pages/validation.js') }}" type="text/javascript"></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
	  <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
	<script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
	
	    <script>
		var brand_id="{{$brand_id}}";
		var model_data="{{ URL::to('admin/brand/data') }}";
		var model_route="{{ URL::to('admin/brand/addmodel') }}";
		var delete_model="{{ URL::to('admin/brand/deletemodel')}}"
			
	function deletemodel(id)
    {
		var result = confirm("Want to delete?");
	
		if (result) {
				
				$.ajax({
				url: delete_model+'/'+id,
				type: "get",
				dataType: 'json',
				success: function(response) {
						
				toastr[response.status]("Sucessfully Delete", "Notifications");
				
				
					var table = $('#table1').DataTable({
					processing: true,
					serverSide: true,
					bDestroy: true,
					ajax: model_data+'/'+brand_id,
					columns: [
					{ data: 'model_name', name: 'model_name' },
					
					{ data: 'actions', name: 'actions', orderable: true, searchable: true }
					],

					});
					table.on( 'draw', function () {
					$('.livicon').each(function(){
					$(this).updateLivicon();
					});
					} );
					
				},
				
			});
		}
		return false;
            
    }    
    
		
    
			var table = $('#table1').DataTable({
				processing: true,
				serverSide: true,
				ajax: model_data+'/'+brand_id,
				columns: [
				{ data: 'model_name', name: 'model_name' },
				
				{ data: 'actions', name: 'actions', orderable: true, searchable: true }
				],

				});
				table.on( 'draw', function () {
				$('.livicon').each(function(){
				$(this).updateLivicon();
				});
		} );
	
			
					
	$('#model_form').submit(function( event ) {

	 event.preventDefault();
		$.ajax({
		url: model_route,
		type: "POST",
		data: $(this).serialize() + "&brand_id=" + brand_id,
		dataType: 'json',
		beforeSend:function(){
			
			$('.formmessage').remove();
			
			}
		,	
		success: function(response) {
					toastr[response.status]("Sucessfully Add", "Notifications");
					$('#model_name').val("");
					var table = $('#table1').DataTable({
					processing: true,
					serverSide: true,
					bDestroy: true,
					ajax: model_data+'/'+brand_id,
					columns: [
					{ data: 'model_name', name: 'model_name' },
					
					{ data: 'actions', name: 'actions', orderable: true, searchable: true }
					],

					});
					table.on( 'draw', function () {
					$('.livicon').each(function(){
					$(this).updateLivicon();
					});
					} );
					
		},
		});
	});
    
    </script>
@stop

@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
   Deleted Brand Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />


<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Deleted Brand Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Deleted Brand Manager</li>
            <li class="active">Brand</li>
        </ol>
    </section>
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Brand List
                    </h4>

                </div>

                                <br />
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Brand </th>
                             <th>Brand Category</th>
                             <th>Image </th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    <!-- row-->
    </section>
    
    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	
	<div class="modal-dialog">
    	
    	   <div class="modal-content">
			<div class="modal-header">
			<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
			<h4 id="user_delete_confirm_title" class="modal-title">Restore Record</h4>
			</div>
			<div class="modal-body"> Are you sure to restore this Record? </div>
			<div class="modal-footer">
			<button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
			<a class="btn btn-danger restorepage" type="button" href="javascript:">Restore</a>
			</div>
    	
    	
    	</div>
    	
    	
  </div>
</div>

@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                 aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.brand.deleteddata') !!}',
                columns: [
                    { data: 'brand_id', name: 'brand_id' },
                    { data: 'name', name: 'name' },
                    { data: 'brand_cat_name', name: 'brand_cat_name' },
                    { data: 'image', name: 'image' ,render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'add_date', name: 'add_date' },
					{ data: 'actions', name: 'actions', orderable: true, searchable: true }
                ],
                
				
              
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });
        
        
		function displayimage(data,type,row,meta)
		{
			var brandimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/brand/","185","101","ff=ffffff") ) }}/'+data;
			var str='<img src="'+brandimageurl+'" />';
			return str;
		}  
    </script>
    
  <script>
	  
	  
	function restorebrand(brand_id){  
	$('.restorepage').click(function(){
	
			 $.ajax(
				{
					url: '{{ URL::to('admin/brand/restorebrand') }}/' +brand_id,
					type: 'GET',
					dataType: "text",
					data: {
					 '_token': $('input[name=_token]').val(),
					},
					success:function(response){
						
						location.reload();
						
					}
				});   
			
		
		});
	}
   
		   
  
 </script>   
   
@stop

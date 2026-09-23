@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Brand Model::CRM
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
        <h1>Brand Model</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Brand Model</li>
            <li class="active">Brand Model</li>
        </ol>
    </section>

    <!-- Main content -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <section class="content paddingleft_right15">
        <div class="row">
			@include('notifications')
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Brand Model List
                    </h4>
                    
                </div>

                  
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Brand </th>
                             <th>Model Name</th>
                          
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
                ajax: '{!! route('admin.brandmodel.modeldata') !!}',
                columns: [
                    { data: 'brand_model_id', name: 'brand_model_id' },
                    { data: 'name', name: 'name' },
                    { data: 'model_name', name: 'model_name' },
                   
                    { data: 'created_at', name: 'created_at' },
					{ data: 'actions', name: 'actions', orderable: true, searchable: true }
                ],
                
				
              
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });
        
         function deletemodelfromlist(model_id){
			$('.deletepage').click(function(){
		
			 $.ajax(
				{
					url: '{{ URL::to('admin/model/deletedbrand') }}/' +brand_id,
					type: 'POST',
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

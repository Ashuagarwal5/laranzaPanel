@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Banip Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">


<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Banip Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Banip Manager</li>
            <li class="active">Banip</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
			
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        Banip List
                    </h4>
                     <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="pull-right">
                    <a href="{{ route('admin.banip.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> create</a>
                    </div>
                </div>

                        
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                           
                            <th>id</th>
                            <th>Ip</th>
                             
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
      <script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.banip.data') !!}',
                columns: [
                  
                    { data: 'ban_id', name: 'ban_id' },
                    { data: 'ban_ip', name: 'ban_ip' },
                    
                    { data: 'add_date', name: 'add_date' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true },
                    
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });
        
        
    
        
    </script>
    
     <script>

	 function deleterecord(id){
	$('.deletepage').click(function(){
			
			
			 $.ajax(
				{
					url: '{{ URL::to('admin/banip/deletedbanip') }}/' +id,
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
 <script>
$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>      
@stop

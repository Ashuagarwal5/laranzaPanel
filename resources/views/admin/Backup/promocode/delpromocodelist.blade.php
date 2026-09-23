@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
   Deleted Promo Code List Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/toastr.css') }}">
   <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1> Deleted Promo Code List Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li> Deleted Promo Code List Manager</li>
            <li class="active">Promo Code</li>
        </ol>
    </section>
      
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Deleted Promo Code List
                    </h4>
                  
                </div> 
                               
                                <br />
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                           
                            <th>Title</th>
                           
							<th>From Date</th>
							<th>To Date</th>
							
														
							<th>Discount</th>
							
							<th>Action</th>
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
      <script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
    <script>
		var restore_route="{{URL::to('admin/promocode/restorePromocode')}}";
		var deleteList="{{URL::to('admin/promocode/deletedData')}}"
		var currency="{{config('constants.frontend.currency')}}";
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: deleteList,
                columns: [
                    { data: 'promo_code_id', name: 'promo_code_id' },
					{ data: 'title', name: 'title' },
					{ data: 'from_date', name: 'from_date' },     
					{ data: 'to_date', name: 'to_date' }, 					
					
					{ data: 'discount', name: 'discount',render:function(data,type,row,meta){ return discountType(data,type,row,meta)} },
					{ data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });
        
       
		
	
    </script>
    <script src="{{ asset('assets/js/promocode.js') }}" type="text/javascript"></script>
    
   
@stop

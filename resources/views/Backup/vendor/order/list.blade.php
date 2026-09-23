@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    {{$submenu}} Order
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
   
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Orders Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Order  Manager</li>
            
            
            <li class="active">{{$submenu}} </li>
           
        </ol>
    </section>
      
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                      {{$submenu}} Orders List
                    </h4>
                    
                </div> 
                               
           
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>OEDER ID</th>
                            <th>Name</th>
                            
                            
                            <th>Total</th>
                            <th>Purchased On</th>
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
	        var url='{!! route('admin.orders.data') !!}';
	        var slug='{{$slug}}';
	        url=(slug!="")?url+'?slug='+slug:url;
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                searchDelay:600,
                ajax: url,
                columns: [
                    { data: 'order_id', name: 'order_id' },
                   
                    { data: 'grand_total', name: 'grand_total' ,render:function(data){return "₹ "+parseFloat(data).toFixed(2)} },
                  
                    { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    
                ],
            });
            
            table.on( 'draw', function () {
                $('.livicon').each(function($data){
	                // alert($data.data);
                    $(this).updateLivicon();
                });
            } );
            
            
        });
    </script>
@stop

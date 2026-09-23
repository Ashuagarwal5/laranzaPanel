@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Promo Codes Manager::CRM
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
        <h1>Promo Codes Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Promo Codes Manager</li>
            <li class="active">All Promo Codes List</li>
        </ol>
    </section>
      
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                        All Promo Codes List
                    </h4>
                    <div class="pull-right">
                    <a href="{{ route('admin.promocode.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Create</a>
                    </div>
                </div> 
                               
                       
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                          <th>ID</th>
						  <th>Title</th>
						  <th>Coupen Code</th>
						  <th>From Date</th>
						  <th>To Date</th>
						  <th>Discount</th>
						  <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
						@foreach($Promocode as $val)
                        <tr>
                          <td>{{$val->promo_code_id}}</td>
                          <td>{{$val->title}}</td>
                          <td>{{$val->coupon_code}}</td>
                          <td>{{date('d/m/Y',$val->from_date)}}</td>
                          <td>{{date('d/m/Y',$val->to_date)}}</td>
                          <td>{{$val->discount}}</td>
                          <td>
							<a class="btn btn-success btn-xs purple" title="View" data-toggle="modal" data-target="#modal-email" href="{{ route('admin.promocode.view',$val->promo_code_id) }}">
							<i class="fa fa-eye"></i>
							</a>
							<a class="btn btn-primary btn-xs purple" title="Edit" href="{{ route('admin.promoode.edit',$val->promo_code_id) }}">
							<i class="fa fa-edit"></i>
							</a>
							<a class="btn btn-danger btn-xs purple" title="Delete" data-toggle="modal" data-target="#modal-large" href="{{URL::to('admin/promocode/'.$val->promo_code_id.'/confirm-delete')}}" title="Delete Page">
							<i class="fa fa-trash"></i>
							
							</a>
                         </td>
                        </tr>
                        @endforeach
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
		var delete_route="{{URL::to('admin/promocode/destory')}}";
		var currency="{{config('constants.frontend.currency')}}";
	    var list_route= "{{route('admin.promocode.data')}}";
	    
	    $(function() {
            $('#table1').DataTable({
				aaSorting : [[0, 'desc']],
				});
        });
        table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
    </script>
    <script src="{{ asset('assets/js/promocode.js') }}" type="text/javascript"></script>
    
   
@stop

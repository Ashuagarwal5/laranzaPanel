@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Seller List
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
@stop


{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Seller</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Seller</li>
        <li class="active">Seller</li>
    </ol>
</section>

<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
        <div class="panel panel-primary ">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Seller List
                </h4>
                
                <a href="{{route('admin.seller.create')}}" class="pull-right btn btn-default"><i class="fa fa-plus"></i> Add Seller</a>
            </div>
            <br />
            <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered " id="table">
                    <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Comapny Name</th>
                            <th>Seller E-mail</th>
                            <th>Category</th>
                            <th>Contact No</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($users as $user)
                    	<tr>
                            <td>{!! $user->id !!}</td>
                    		<td>{!! $user->company_name !!}</td>
            				<td>{!! $user->email !!}</td>
            				<td>{!! $user->category_name !!}</td>
            				<td>{!! $user->company_telephone !!}</td>
            				<td>{!! $user->status !!}</td>
            				
            				
            				<td> {{ $user->add_date}}</td>
            				<td>
                                <a href="{{ route('seller.show', $user->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-info"></i></a>

                               <a href="{{ route('admin.seller.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
                               <a href="{{ route('admin.seller.delete-modal', $user->id ) }}" data-toggle="modal" data-target="#modal-regular"  class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                
                            </td>
            			</tr>
                    @endforeach
                        
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>    <!-- row-->
</section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

<script>
$(document).ready(function() {
	  $('#table').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
    
    
	//$('#table').DataTable();
});
</script>

<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content"></div>
  </div>
</div>
<script>
$(function () {
	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('bs.modal');
	});
});
</script>
@stop

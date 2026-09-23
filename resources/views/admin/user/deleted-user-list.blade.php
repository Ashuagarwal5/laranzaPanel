@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
   Trash Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">

@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Trash Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Trash Manager</li>
            <li class="active">Customers Trash List</li>
        </ol>
    </section>


    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
             <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Customers Trash List
                    </h4>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile No</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
							@foreach($trashedUser as $key=>$value)
							<tr>
								<td>{{$value->id}}</td>
<!--
								<td><img src="{{URL::to(App\Helpers\Thumbnail::image("user/$value->profile_photo","200","160","ff=ffffff")) }}"></td>
-->
								<td>{{$value->full_name}}</td>
								<td>{{($value->email ? $value->email  : 'Not Provided')}}</td>
								<td>{{$value->mobileno}}</td>
								<td>
									<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to('admin/user/restore/'.$value->id)}}" class="btn btn-primary"><i class="fa fa-recycle"></i> Restore</a>
									<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to('admin/user/permanent-delete/'.$value->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
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
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
   <script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                //processing: true,
                //serverSide: true,
                aaSorting : [[0, 'desc']],
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

    </script>
<script>
	$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead);
	});
</script>

@stop

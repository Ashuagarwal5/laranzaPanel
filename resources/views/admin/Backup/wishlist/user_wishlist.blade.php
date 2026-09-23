@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
 User Wishlist Manager::CRM
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
        <h1>User Wishlist Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>User Wishlist Manager</li>
            <li class="active">Wishlist</li>
        </ol>
    </section>
    <div id="ajaxResponse"></div>
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
             <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                       User Wishlist for {{$pro_name}}
                    </h4>
                    <a href="{{route('admin.wishlist')}}" class="pull-right btn btn-default"><i class="fa fa-angle-double-left"></i> Back</a>
                    
                </div>
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>User Name</th>                            
                            <th>Product Name</th>  
                        </tr>
                        </thead>
                        <tbody>
							@foreach($detail as $key=>$value)
							<tr>
								<td>{{$value->id}}</td>
								<td>{{$value->full_name}}</td>
								<td>{{$value->product_title}}</td>
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
	<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
    <script>
        $(function() {
            var table = $('#table1').DataTable({
               
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

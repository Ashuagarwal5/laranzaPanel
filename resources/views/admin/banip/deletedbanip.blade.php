@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
   Deleted Banip Manager::CRM
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
        <h1>Deleted Banip Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Deleted Banip Manager</li>
            <li class="active">Banip</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
			@include('notifications')
	                     <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Deleted Banip List
                    </h4>
                     <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="pull-right">
                    <a href="{{ route('admin.banip.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> @lang('button.create')</a>
                    </div>
                </div>

                        
                <div class="panel-body">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                           
                            <th>ID</th>
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
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.banip.deleteddata') !!}',
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
        
        
 function displayimage(data,type,row,meta)

        {
         if(data){

          var bannerimageurl='{{ URL::to('/public/uploads/banner/')  }}/'+data;
          var str='<img src="'+bannerimageurl+'" width="100" height="100"/>';


         }
         else{

               var str='No image found';
             }

          return str;

        }       
        
    </script>
    
       
@stop

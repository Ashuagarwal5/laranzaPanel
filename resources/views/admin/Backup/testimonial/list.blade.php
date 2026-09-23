@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
   {{ $manager_name }} Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />

   <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">

<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>{{ $manager_name }} Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>{{ $manager_name }} Manager</li>
            <li class="active">{{ $manager_name }} List</li>
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
                        {{ $manager_name }} List
                    </h4>
                    <div class="pull-right">
						  <a href="{{$route_create}}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Create</a>
                    </div>
                </div>
                <div class="panel-body">

					@include('admin.notifications')

                <div class="table-responsive">
                    <table class="table table-bordered " id="table_news">
                        <thead>
                        <tr class="filters">
                              <th >Id</th>
						<th >User Name</th>
						<th >User Feedback</th>
						 <th >Feedback Image</th>
                          <th>Create Date</th>
                        <th >Action</th>
                        </tr>
                        </thead>
                        <tbody>
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
     <script type="text/javascript">
	 $(function() {
		    var routes= "{{ $route_data }}";
            var table_news = $('#table_news').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: routes,
                columns: [
                    { data: 'id', name: 'id',visible:false },
                    { data: 'client_name', name: 'client_name' },
                    { data: 'feedback', name: 'feedback', render:function(data,type,row,meta){ return limitedText(data,type,row,meta)} },
                    { data: 'client_photo', name: 'client_photo', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'created_at', name: 'created_at' },
					{ data: 'actions', name: 'actions', orderable: false, searchable: true }
                ],
            });
            table_news.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );


        function displayimage(data,type,row,meta)
        {

			var imageurl='{{ URL::to(App\Helpers\Thumbnail::image("feedback/","100","85","cf")) }}/'+data;
			var str='<img src="'+imageurl+'" alt="-N/A-" />';

		 return str;

        }


         function limitedText(data,type,row,meta)
        {
			var limit = 50;
			var val = data;
			if (val.length > limit){
      return val.substring(0, limit)+'....';
      }else{
      return val;
    }






        }


	 });

    </script>
<script>
$(document).ready(function(){
	//toastr[response.msgType](response.msg, response.msgHead);
	});
</script>

@stop

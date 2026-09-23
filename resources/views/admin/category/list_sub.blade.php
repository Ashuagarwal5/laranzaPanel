@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Category Manager::CRM
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
        <h1>Category Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Category Manager</li>
            <li class="active">Sub Category List</li>
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
                        Sub Category List for {{$menuTitle}}
                    </h4>
                    <div class="pull-right">						
					
					
					<a class="btn btn-default" href="{{URL::to("admin/category/create_submenu/$id")}}" title="Add S"><i class="fa fa-edit"></i> Add SubCategory</a> 
					<a href="{{ route('admin.category') }}" class="btn btn-sm btn-default"><span class="btn-label">
						<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span>Back</span></a>  
					
                    </div>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Title</th>                          
                            <th>Parent Category</th>  
                            <th>Type</th>                          
                            <th>Create Date </th>
                            <th>Actions</th>
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
    <script>
        $(function() {
			var route = "{{route('admin.category.sub-data',$id) }}";
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: route,
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'category_name', name: 'category_name', render:function(data,type,row,meta){ return title(data,type,row,meta)}},
                    { data: 'parent_name', name: 'parent_name' },
                    { data: 'type', name: 'type' },
                    { data: 'add_date', name: 'add_date' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

		function title(data,type,row,meta)
		{
			if(data)
			return data;
			else
			return 'Not Available';
		}
		
		function description(data,type,row,meta)
		{
		   var tmp = document.createElement("DIV");
		   tmp.innerHTML = data;
		   var str=tmp.textContent || tmp.innerText || "";
		   var stripedtext=$(str).text();
		   str=stripedtext.substring(0,70);
		   return str;
		}
		function displayimage(data,type,row,meta)
        {
			 if(data){
			  var bannerimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/category/","200","65","ff=ffffff")) }}/'+data;
			  var str='<img src="'+bannerimageurl+'" />';
			 }
			 else{
				   var str='No image found';
				 }

			  return str;
        }

    </script>
<script>
$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>

@stop

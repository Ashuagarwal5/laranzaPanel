@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
   Products Manager::CRM
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
        <h1>Products Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Products Manager</li>
            <li class="active">Product List</li>
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
                        All Products List
                    </h4>
                    <div class="pull-right">
						  <a href="{{ URL::to('admin/products/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Create</a>
						 
                    </div>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Stock Status</th>
                            <th>Sale Price</th>
                            <th>Points</th> 
                            <th>Add Date </th>
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
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.products.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'product_title', name: 'product_title' },
                    { data: 'stock_status', name: 'stock_status' },
                    // { data: 'product_image', name: 'product_image', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'sale_price', name: 'sale_price',render:function(data,type,row,meta){ return price(data,type,row,meta)} },
                    // { data: 'company_name', name: 'company_name',render:function(data,type,row,meta){ return NA(data,type,row,meta)} },
                    { data: 'points', name: 'points' },
                    { data: 'added_date', name: 'added_date' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

		function changedateformate(data,type,row,meta)
		{
			return data;

		}
		function price(data,type,row,meta)
		{
			return str = data + "/-";
		} 
        function stock_status(data,type,row,meta)
        {
            if(data==1)
                return "Available";
            else
                return "Out Of Stock";
        }
		function NA(data,type,row,meta)
		{
			if(data)
			{
				return data;
				}
				else{
					return "N/A";
					}
		} 
		function date(data,type,row,meta)
		{
			if(data)
			{
				var dat = "{{date('d M y',strtotime(" + data + "))}}";
				return dat;
			}
			else{
				return "N/A";
				}
		} 
		
		function description(data,type,row,meta)
		{
		   var tmp = document.createElement("DIV");
		   tmp.innerHTML = data;
		   var str=tmp.textContent || tmp.innerText || "";
		   var stripedtext=$(str).text();
		   if(stripedtext.length > 50)
		   {
			str=stripedtext.substring(0,50)+'...';
		   }else
		   {
				str=stripedtext.substring(0,50);
			}
		   return str;
		}
	
	function displayimage(data,type,row,meta)
        {
			 if(data){
			  var storeitemimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/products/","200","80","ff=ffffff")) }}/'+data;
			  var str='<img src="'+storeitemimageurl+'" />';
			 }
			 
			  return str;
        }
    </script>
<script>
$(document).ready(function(){
//	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>

@stop

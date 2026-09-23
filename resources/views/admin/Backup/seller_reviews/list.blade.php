@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
    Seller Reviews Manager::CRM
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
        <h1>Seller Reviews Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Sellers</li>
            <li class="active">Seller Reviews List</li>
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
                        Seller Reviews List
                    </h4>

                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
							
                            <th>ID</th>
                            <th>Seller</th>
                            <th>Review</th>
                            <th>Rating</th>
                            <th>Review Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
							@foreach($data as $key=>$value)
							<tr>
								<td>{{$value->id}}</td>
								<td>{{$value->company_name}}</td>
								<td>{{strip_tags($value->review)}}</td>
								<td>{{$value->rate}}</td>
								<td>{{$value->add_date}}</td>
								<td>
									<div class="btn-group">
									<a title="@if($value->publish=='No') Publish @else Unpublish @endif" id="rv-{{$value->id}}" class="btn @if($value->publish=='No') btn-warning @else btn-success @endif btn-xs purple publish_rv" data-url="{{URL::to('admin/seller_reviews/publish/'.$value->id)}}">
									<i class="fa fa-bell"></i>
									
						
									<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to('admin/seller_reviews/delete/'.$value->id)}}" class="delval btn btn-xs btn-danger"  title="Delete Review">
<!--
									<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to('admin/product_reviews/'.$value->id.'/confirm-delete')}}" class="delval btn btn-xs btn-danger"  title="Delete Product Reviews">
-->
									<i class="fa fa-trash"></i>
									
									</a>
									</div>
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
                //~ processing: true,
                //~ serverSide: true,
                aaSorting : [[0, 'desc']],
                
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            });
        });

		
    </script>
<script>
$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>
<script>
$(document).on('click', '.publish_rv', function(){
	
	var url = $(this).attr('data-url');
	var id = $(this).attr('id');
	
      $.ajax({
		url: url,
		type: "GET",
		dataType: 'json',
		success: function(response) {
				//alert('success');
		    if(response.status=="success")
		    {
				if(response.publish=="No")
				{
					$('#'+id).removeClass('btn-success');
					$('#'+id).addClass('btn-warning');
					$('#'+id).attr('title','Publish');
					
				}else{
					$('#'+id).removeClass('btn-warning');
					$('#'+id).addClass('btn-success');
					$('#'+id).attr('title','Unpublish');
				}
				
				toastr[response.status](response.message, "Notifications");
				
			} 

		}
	});
	
});
</script>

@stop

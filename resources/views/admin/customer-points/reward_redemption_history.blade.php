@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
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
        <h1>Reward Redemption History</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Reward Redemption History History </li>
            <li class="active">Reward Redemption History List</li>
        </ol>
    </section>
   
   
    <!-- Main content -->
    <section class="content paddingleft_right15">
        <div class="row">
             <div class="panel panel-primary ">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
                       Reward Redemption History List
                    </h4>
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Reward</th>
                            <th>Image</th>
                            <th>Qualification Points</th>
                            <th>Date</th>
<!--
                            <th>Actions</th>
-->

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
                ajax: '{!! route('reward-redemption-history.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'reward', name: 'reward' },
                    { data: 'image', name: 'image', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'qualification_points', name: 'qualification_points' },
                    { data: 'add_date', name: 'add_date' }
                    //{ data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

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
		
		

function displayimage(data,type,row,meta)
        {
			 if(data){
			  var storeitemimageurl='{{ URL::to(App\Helpers\Thumbnail::image("/customer-reward/","200","80","ff=ffffff")) }}/'+data;
			  var str='<img src="'+storeitemimageurl+'" />';
			 }
			 
			  return str;
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

	
    </script>
<script>
	$(document).ready(function(){
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>

@stop

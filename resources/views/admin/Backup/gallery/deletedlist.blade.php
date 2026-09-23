@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')Gallery Manager @parent @stop
{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
	<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop
@section('content')
      <div class="be-content">
        <div class="page-head">
          <h2 class="page-head-title">Gallery Manager</h2>
          <ol class="breadcrumb page-head-nav">
            <li><a href="#">Dashboard</a></li>
            <li><a href="{{ route('admin.event')}}">Gallery Manager</a></li>
            <li class="active">Deleted Gallery List</li>
          </ol>
        </div>
        <div class="main-content container-fluid">
          <div class="row">
            <div class="col-sm-12">
              <div class="panel panel-default panel-table">
                <div class="panel-heading">Delete Gallery Manager
                </div>
                <div class="panel-border-color panel-border-color-primary "></div>
                <div class="panel-body">
				<div class="col-sm-12">
					@include('admin.notifications')
				</div>
               <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>Image</th>
                          	<th>Type</th>
                           	<th>Deleted Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <div id="ajaxResponse"></div>
@stop
@section('footer_scripts')
<script>
var routes = "{{ route('admin.news.data') }}";
</script>
    <script src="{{ asset('assets/admin/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('assets/admin/js/app-tables-datatables.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
      	//initialize the javascript
      	App.init();
      	App.formElements();
      	App.dataTables();
      });
    </script>
    <script>
        $(function() {
        });

		function changedateformate(data,type,row,meta)
		{
			return data;
		}
    </script>
<script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                 aaSorting : [[0, 'desc']],
                ajax: '{!! route('admin.gallery.deleteddata') !!}',
                columns: [
					{ data: 'image', name: 'image', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
                    { data: 'type', name: 'type' },
                    { data: 'deleted_date', name: 'deleted_date' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
		
	   function displayimage(data,type,row,meta)
        {
         if(row["type"]=='Vedio'){
        	//	return row["url"];
		var str='<div align="center">Video</div>';
		 }else{
			var imageurl='{{ URL::to(App\Helpers\Thumbnail::image("/gallery/","200","65","ff=ffffff")) }}/'+data;
			var str='<img src="'+imageurl+'" />'; 
		 }
		 return str;         
        } 
	 });
    </script>
  <script>
	function restorerecord(newsid){
		$('.restorepage').click(function(){
			$.ajax(
					{
						url: '{{ URL::to('admin/news/restorepage') }}/' +newsid,
						type: 'GET',
						dataType: "text",
						data: {
						 '_token': $('input[name=_token]').val(),
						},
						success:function(response){
							location.reload();
						}
					});
			});
	}
 </script>
@stop
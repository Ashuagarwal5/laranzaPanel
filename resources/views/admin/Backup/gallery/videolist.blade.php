@extends('admin.layouts.default')
@section('title')Gallery Manager::CRM @parent @stop
@section('header_styles')
    <link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
	<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
	<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
@stop
@section('content')
      <div class="be-content">
        <div class="page-head">
          <h2 class="page-head-title"> Gallery Manager</h2>
          <ol class="breadcrumb page-head-nav">
            <li><a href="admin.dashboard">Dashboard</a></li>
            <li><a href="{{ route('admin.video') }}"> Gallery Manager</a></li>
            <li class="active"> Gallery List</li>
          </ol>
        </div>
        <div class="main-content container-fluid">
          <div class="row">
			  <div class="col-sm-12">
              <div class="panel panel-default panel-table">
                <div class="panel-heading">Video Manager
                  <div class="tools">
                    <a href="{{ route('create.gallery') }}"><i class="fa fa-plus"></i> Create</a>
                  </div>
                </div>
                <div class="panel-border-color panel-border-color-primary "></div>
                <div class="panel-body">
					<div class="col-sm-12">
					@include('admin.notifications')
				</div>
               <table class="table table-striped table-hover table-fw-widget" id="table_news">
                    <thead>
                      <tr align="right">
                        <th style="width:0%;">Id</th>
                        <th style="width:15%;">File</th>
                        <th style="width:25%;">Title</th>
                        <th style="width:15%;">Create Date</th>
                        <th style="width:30%;">Action</th>
                      </tr>
                    </thead>
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
     <script src="{{ asset('assets/admin/lib/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script type="text/javascript">
	 $(function() {
		    var routes= "{{ route('admin.video.data') }}";
            var table_blog = $('#table_news').DataTable({
                processing: true,
                serverSide: true,
                aaSorting : [[0, 'desc']],
                ajax: routes,
                columns: [
                    { data: 'id', name: 'id',visible:false },
					{ data: 'vedio', name: 'vedio', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },
					{ data: 'title', name: 'title', render:function(data,type,row,meta){ return wordTrim(data,type,row,meta)} },
                    { data: 'add_date', name: 'created_at' },
					{ data: 'actions', name: 'actions', orderable: false, searchable: true }
                ],
                 columnDefs: [{
                  "render": function ( data, type, row ) {
        						if(row["type"]=='Vedio')
        						return row["vedio"];
        						else
        						return data;
        					},
        					"targets": 1
        				}
						
              ]
            });
            table_news.on( 'draw', function () {
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
		function wordTrim(data,type,row,meta) {
			 var str=data;
    var res = str.substr(0,42);
              if(str.length>42)
                res=res+"..";
              return res;
			}
	 });
    </script>
	<script>
	 $(document).ready(function(){
      	//initialize the javascript
     	App.init();
     	App.formElements();
		 });
	</script>
@stop

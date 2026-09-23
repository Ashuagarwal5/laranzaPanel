@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
Sub Admin Manager::CRM
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

<style>
  #table1 tbody td:nth-child(1){
    text-transform: capitalize;
  }

</style>
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
  <h1>Sub Admin Manager</h1>
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('admin.dashboard') }}">
        <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
        Dashboard
      </a>
    </li>
    <li>Sub Admin Manager</li>
    <li class="active">Sub Admin List</li>
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
        Sub Admin List
      </h4>
      <div class="pull-right">
        <a href="{{ route('sub_admin.create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span> Add Sub Admin</a>
      </div>
    </div>
    <div class="panel-body">
      <div class="table-responsive">
        <table class="table table-bordered " id="table1">
          <thead>
            <tr class="filters">
             <!-- <th>Id</th> -->
             <th>Name</th>
             <th >Email </th>
             <th >Mobile No </th>
             <th >Create Date</th>
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
<script>
 $(function() {
  var routes= "{{ route('admin.sub_admin.data') }}";
  var table_blog = $('#table1').DataTable({
    processing: true,
    serverSide: true,
    aaSorting : [[0, 'desc']],
    ajax: routes,
    columns: [
    // { data: 'id', name: 'id',visible:false },
    { data: 'name', name: 'name', render:function(data,type,row,meta){ return wordTrim(data,type,row,meta)} },
    { data: 'email_address', name: 'email_address' },
    { data: 'mobile_no', name: 'mobile_no' },
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
      "targets": [0],
      'orderable': false,
    }

    ]
  });
  table_blog.on( 'draw', function () {
    $('.livicon').each(function(){
      $(this).updateLivicon();
    });
  } );


  function displayimage(data,type,row,meta)
  {

   var imageurl='{{ URL::to(App\Helpers\Thumbnail::image("/service/","200","65","ff=ffffff")) }}/'+data;
   var str='<img src="'+imageurl+'" />'; 

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



 function changedateformate(data,type,row,meta)
 {
   return data;

 }
</script>
<script>
 //  $(document).ready(function(){
 //   toastr[response.msgType](response.msg, response.msgHead); 
 // });
</script>

@stop

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
      
        <a href="" class="btn btn-default pull-right export_button"><span class="glyphicon glyphicon-plus"></span>Export Customer Data</a>
	 
        {{-- <div class="pull-right" style="margin-right: 5px;">
          <a href="{{$route_create}}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Add {{ $manager_name }}</a>
        </div> --}}
      </div>
  
      <div class="panel-body">
        @include('admin.notifications')
        <div class="table-responsive">
          <form id="filter_form" method="get" style="background: #f2f2f2;padding-top: 25px;margin-bottom: 17px;">
            <div class="row">
            <!--
              <div class="col-xs-4 col-sm-4">
                <div class="form-group">
                  <label for="agent">User Type:</label>
                  <div class="input-group">


                    <select name="user_type" id="user_type" class="form-control">
                    <option value="">Select Customer Type</option>
                    <option value="Dealer">Dealer</option>
                    <option value="Dealer Sales Person">Dealer Sales Person</option>
                    <option value="User">Karigar</option>
                    <option value="Interior Designer">Architect/Interior Designer</option>
                  </select>

                  <span class="input-group-addon info">
                    <span class="glyphicon glyphicon-user"></span></span>
                  </div>
                </div>
              </div>
            -->
            <div class="col-xs-4 col-sm-4">
            <div class="form-group">
              <label for="agent">Customer Name:</label>
              <div class="input-group">
                <input type="text" class="form-control" name="fullname" id="fullname" style="width: 100%;" value="" />
                <span class="input-group-addon info">
                  <span class="glyphicon glyphicon-user"></span></span>
                </div>
              </div>
            </div> 
            <div class="col-xs-4 col-sm-4">
            <div class="form-group">
              <label for="agent">Customer Mobile Number:</label>
              <div class="input-group">
                <input type="text" class="form-control" name="mobileno" id="mobileno" style="width: 100%;" value="" />
                <span class="input-group-addon info">
                  <span class="glyphicon glyphicon-user"></span></span>
                </div>
              </div>
            </div>
            <div class="col-xs-4 col-sm-4">
              <div class="form-group">
                <label for="agent">Dealer:</label>
                  <select name="dealer_id" class="form-control">
                      <option selected value="">Select Dealers</option>
                      @foreach($dealer as $dealer)
                        <option value="{{$dealer->id}}">{{$dealer->dealer_name}}</option>
                      @endforeach
                  </select>
              </div>
            </div>
            </div>
            <div class="row">
              <div class="col-xs-12 col-sm-12" style="margin-bottom:14px;text-align: center;">
                <a href="{{ route('admin.user') }}"  class="btn btn-primary">Reset Search &nbsp; &nbsp;</a>
                <button type="submit" class="btn btn-danger">Search</button>
              </div>
            </div>
          </form>
        </div>
        <table class="table table-bordered " id="table_news">
          <thead>
            <tr class="filters">
              <th style="width:5%;">Sr. No.</th>
              <th>Name</th>
              <th>Profile Photo</th>
              <th>Mobile No</th>
              <th>City</th>
              <th>Dealer</th>
              <th>Profile Status</th>
              <th>Create Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

@stop
{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
  $(function() {
    $('#filter_form').submit(function(event) {
      event.preventDefault();
      setData();
    });
    function setData(){
      if($('select[name="dealer_id"]').val()!=null && $('select[name="dealer_id"]').val() != undefined && $('select[name="dealer_id"]').val()!=''){
        var dealer_id = $('select[name="dealer_id"]').val();
      } else {
        var dealer_id ='';
      }
      if($('input[name="fullname"]').val()!=null && $('input[name="fullname"]').val() != undefined && $('input[name="fullname"]').val()!=''){
        var fullname = $('input[name="fullname"]').val();
      } else {
        var fullname ='';
      }
      if($('input[name="mobileno"]').val()!=null && $('input[name="mobileno"]').val() != undefined && $('input[name="mobileno"]').val()!=''){
        var mobileno = $('input[name="mobileno"]').val();
      } else {
        var mobileno ='';
      }
      set_table(fullname,mobileno,dealer_id);
    }
    if($('select[name="dealer_id"]').val()!=null && $('select[name="dealer_id"]').val() != undefined && $('select[name="dealer_id"]').val()!=''){
      var dealer_id = $('select[name="dealer_id"]').val();
    } else {
      var dealer_id ='';
    }
    if($('input[name="fullname"]').val()!=null && $('input[name="fullname"]').val() != undefined && $('input[name="fullname"]').val()!=''){
      var fullname = $('input[name="fullname"]').val();
    } else {
      var fullname ='';
    }
    if($('input[name="mobileno"]').val()!=null && $('input[name="mobileno"]').val() != undefined && $('input[name="mobileno"]').val()!=''){
      var mobileno = $('input[name="mobileno"]').val();
    } else {
      var mobileno ='';
    }
    set_table(fullname,mobileno,dealer_id);
    function set_table(fullname,mobileno,dealer_id){
      var route= "{{ route('admin.user.data') }}?fullname="+fullname+"&mobileno="+mobileno+"&dealer_id="+dealer_id;
      var route_for_export="{{route('export.user')}}?fullname="+fullname+"&mobileno="+mobileno+"&dealer_id="+dealer_id;
      $('.export_button').attr('href',route_for_export);
      var table = $('#table_news').DataTable({
        buttons: [ 'copy', 'csv', 'excel' ],
        "select": {
          style: 'single'
        },
        "colReorder": true,
        processing: true,
        stateSave: true,
        destroy:true,
        serverSide: true,
        aaSorting : [[0, 'desc']],
        ajax: route,
        columns: [
          { data: 'id', name: 'id', searchable: false, render: function (data, type, row, meta) { return meta.settings._iDisplayStart + meta.row + 1; } },
          { data: 'full_name', name: 'full_name' },
          { data: 'profile_photo', name: 'profile_photo', render:function(data,type,row,meta){ return displayimage(data,type,row,meta)} },   
          { data: 'mobileno', name: 'mobileno' },
          { data: 'city', name: 'city' },
          { data: 'dealer_name', name: 'dealer_name' },
          { data: 'profile_status', name: 'profile_status'},
          { data: 'add_date', name: 'created_at' },
          { data: 'actions', name: 'actions', orderable: false, searchable: true }
        ],
      });
      table.on( 'draw', function () {
        $('.livicon').each(function(){
          $(this).updateLivicon();
        });
      });
    }
    function statusChange(data,type,row,meta){
      if(data=='Active')
      var str='No';
      else if (data=='Inactive')
      var str='Yes';
      return str;
    }

    function displayimage(data,type,row,meta){
      if(data){
        var imageurl='{{ URL::to(App\Helpers\Thumbnail::image("/user","200","200","ff=ffffff")) }}/'+data;
        //var imageurl='{{ asset("assets/admin/default_user.png") }}';
        var str='<img height="60" src="'+imageurl+'" />';
      } else {
        var imageurl='{{ asset("assets/admin/default_user.png") }}';
        var str='<img height="60" src="'+imageurl+'" />';
      }
      return str;
    }
    function displayUserType(data,type,row,meta){
      if(data == 'User')
      var str = 'User';   //first here was Karigar
      else if(data == ''|| data == null || data == 'undefined')
      var str = 'Not Specified';
      else
      var str = data;
      str ='<div style = "text-transform:capitalize;">'+str+'</div>';
      return str;
    }
  });
</script>
@stop
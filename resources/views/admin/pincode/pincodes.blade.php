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
    <h1>PIN Code Database</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>PIN Codes </li>
        <li class="active">PIN Code List</li>
    </ol>
</section>


<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
       <div class="panel panel-primary ">
          <div class="panel-heading clearfix">
            <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
             PIN Code Database
         </h4>
         <div class="pull-right">
          <a href="{{ route('create.pincode') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Create</a>

      </div>
  </div>

  <div class="panel-body">
    <div class="form-group col-sm-12">
     <input type="text"  class="form-control input-sm" name="search" value="{{ $_GET['search'] ?? ''}}"  id="validate-text"placeholder="Search" >
 </div>
 <button type="button" class="btn btn-space btn-primary submit">Submit</button>
 <a href="{{route('admin.pincode')}}" class="btn btn-space btn-danger">Clear</a>
 <div class="table-responsive">
    <table class="table table-bordered " id="table1">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Pin Code</th>
                <th>City</th>
                <th>District</th>
                <th>State</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
          @foreach($data as $key=>$value)
          <tr>
             <td>{{ $data->firstItem() + $loop->index }}</td>
             <td>{{$value->pincode}}</td>
             <td>{{ $value->city }}</td>
             <td>{{ $value->district }}</td>
             <td>{{ $value->state }}</td>
             <td><a href="{{route('update.pincode',$value->id)}}" class="btn btn-xs btn-primary" title=""><i class="fa fa-pencil"></i></a>
                <a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/pincode/".$value->id."/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete Section">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@if(isset($data) && count($data)>0)
<nav aria-label="Page navigation text-center">

    
    @if(!empty($_GET['search']))
    {{$data->appends(['search' => $_GET['search']])->links()}}
    @else
    {!! $data->render() !!}
    @endif
</nav>
@endif

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
   $(document).ready(function() {
       $(document).on('click', '.submit', function(event) {

           event.preventDefault();
          let search_val=$('input[name="search"]').val();
          if(search_val)
          {
            window.location.href="{{route('admin.pincode')}}"+'?search='+search_val;
          }
       });
   });


    </script>

    @stop

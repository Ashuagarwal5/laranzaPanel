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
    <h1>Customer Transactions History</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Customer Point History </li>
        <li class="active">Customer Point History List</li>
    </ol>
</section>


<!-- Main content -->
<section class="content paddingleft_right15">
    <div class="row">
     <div class="panel panel-primary ">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i>
           Customer Transactions History List
       </h4>
   </div>
   <div class="panel-body">
    <div class="table-responsive">
        <table class="table table-bordered " id="table1">
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Customer Name</th>
                    <th>Total Earned Points</th>
                    <th>Total Redeemed Points</th>
                    <th>Balance Points </th>
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
        ajax: '{!! route('admin.customer.point.history.data') !!}',
        columns: [
        { data: 'id', name: 'id' },
        { data: 'full_name', name: 'full_name'},
        { data: 'total_earned', name: 'total_earned', render:function(data,type,row,meta){ return earned(data,type,row,meta)}},
        { data: 'total_redeemed', name: 'total_redeemed', render:function(data,type,row,meta){ return redeemed(data,type,row,meta)}},
        { data: 'total_earned', name: 'total_earned', render:function(data,type,row,meta){ return total_earned(data,type,row,meta)}}
        ]


    });
    table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    } );
});


//   function full_name(data,type,row,meta)
//   {
//     if(data){
//         var url="{{ URL::to('user/rewards') }}/"+row.id+"/Earn";
//         return '<a href="'+url+'" target="_blank">'+data+'</a>';
//     }
//     else
//         return 'Not Available';
// }
function earned(data,type,row,meta)
{
    if(data){
        var url="{{ URL::to('admin/user/rewards') }}/"+row.id+"/Earn";
        return '<a href="'+url+'" target="_blank">'+data+'</a>';
    }
    else
        return 'Not Available';
}
function redeemed(data,type,row,meta)
{
    if(data){
        var url="{{ URL::to('admin/user/rewards') }}/"+row.id+"/Redeem";
        return '<a href="'+url+'" target="_blank">'+data+'</a>';
    }
    else
        return 'Not Available';
}
function total_earned(data,type,row,meta)
{
    if(data){
        var url="{{ URL::to('admin/user/rewards') }}/"+row.id+"/All";
        return '<a href="'+url+'" target="_blank">'+data+'</a>';
    }
    else
        return 'Not Available';
}


</script>
<script>
    $(document).ready(function(){
    // toastr[response.msgType](response.msg, response.msgHead);
});
</script>

@stop

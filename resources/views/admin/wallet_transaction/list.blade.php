@extends('admin.layouts.default')
{{-- Page title --}}
@section('title')
Point Transaction Manager::CRM
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/css/pages/form3.css') }}" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/select2.min.css') }}">
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
    <h1>Point Transaction Manager</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Point Transaction Manager</li>
        <li class="active">Point Transaction List</li>
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
                Point Transaction List
            </h4>
           <!-- <a href="{{ route('export.wallet-transaction') }}" class="btn btn-danger pull-right export_btn" title="">Export Data</a> -->
        </div>
        <div class="panel-body">
        <form id="filter_form" method="get">
                 <div class="row">
                    <div class="col-xs-4 col-sm-4">
                      <div class="form-group">
                        <label for="user">Select Customer :</label>
                        <div class="input-group">
                            <select class="form-control" name="user" id="user" style="width: 100%;">
                                <option value="">Please Select Customer</option>
                                @if(!empty($users))
                                @foreach($users as $key=>$value)
                                @if(!empty($value->full_name))
                                <option value="{{$value->id}}">{{$value->full_name}} - {{$value->mobileno}}</option>
                                @endif
                                @endforeach
                                @endif
                            </select>
                            <span class="input-group-addon">
                                <span class=""></span>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <div class="form-group">
                            <label for="trasanction_from">Transaction From:</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="trasanction_from" value="" id="trasanction_from" placeholder="Transaction From" style="width: 100%;">
                                <span class="input-group-addon">
                                <span class=""></span>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <div class="form-group">
                            <label for="trasanction_to">Transaction To:</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="trasanction_to" value="" id="trasanction_to" placeholder="Transaction From" style="width: 100%;">
                                <span class="input-group-addon">
                                <span class=""></span>
                            </span>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    
                    <div class="col-xs-4 col-sm-4">
                        <div class="form-group">
                            <label for="trasanction_type">Transaction Type:</label>
                            <div class="input-group">
                               <select class="form-control" name="trasanction_type" id="trasanction_type" style="width: 100%;">
                                <option value="">Please Transaction Type</option>

                                <option value="Earn">Earn</option>
                                <option value="Redeem">Redeem</option>
                             
                            </select>
                           <span class="input-group-addon">
                                <span class=""></span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4">
                        <div class="form-group">
                            <label for="trasanction_type">QR Value:</label>
                            <div class="input-group">
                               <input type="text" class="form-control" name="qr_value" id="qr_value" style="width: 100%;">
                                
                             
                            </select>
                           <span class="input-group-addon">
                                <span class=""></span>
                            </span>
                        </div>
                    </div>
                </div>
                   </div?  
                     <div class="row">
                <div class="col-xs-12 col-sm-12" style="">
                	<center>
                	<a href="{{ route('admin.wallet-transaction') }}">Reset Search</a>
                	 <button type="submit" class="btn btn-danger">Search</button></center>
            </div>
                     </div>
                
            </div>


            
       
    </form>
            <div class="table-responsive">
                
    <table class="table table-bordered " id="table1" style="margin-top: 10px;">
        <thead>
            <tr class="filters">
               <th>Sr. No.</th>
               <th>Customer Name</th>
               <th>QR Value</th>
               <th >Reward Points</th>
               <!--<th >Balance Points</th>-->
               <th >Transaction Type</th>
               <th >Create Date</th>
               <!-- <th >Action</th> -->
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
<script src="{{ asset('assets/admin/js/select2.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script>
    $(function()
    {
        $('#user').select2();
        $('#filter_form').submit(function(event) {
            event.preventDefault();
            setData();
        });
        function setData()
        {
            if($('select[name="user"]').val()!=null && $('select[name="user"]').val() != undefined && $('select[name="user"]').val()!='')
            {
                var user                = $('select[name="user"]').val();
            }
            else
            {
               var user                = '';
            }
            
           if($('select[name="trasanction_type"]').val()!=null && $('select[name="trasanction_type"]').val() != undefined && $('select[name="trasanction_type"]').val()!='')
           {
               var trasanction_type     = $('select[name="trasanction_type"]').val();
           }
           else
           {
            var trasanction_type     = '';
        }
        
        if($('select[name="payment_mode"]').val()!=null && $('select[name="payment_mode"]').val() != undefined && $('select[name="payment_mode"]').val()!='')
        {
           var payment_mode         = $('select[name="payment_mode"]').val();
       }
       else
       {
           var payment_mode         ='';
       }
       if($('input[name="trasanction_to"').val()!=null && $('input[name="trasanction_to"').val() != undefined && $('input[name="trasanction_to"').val()!='')
       {
           var trasanction_to       = $('input[name="trasanction_to"]').val();
       }
       else
       {
        var trasanction_to       = '';
    }
    
    if($('input[name="trasanction_from"').val()!=null && $('input[name="trasanction_from"').val() != undefined && $('input[name="trasanction_from"').val()!='')
    {
       var trasanction_from     = $('input[name="trasanction_from"]').val();
   }
   else
   {
       var trasanction_from     = '';
   }
   
   if($('input[name="coupon_no"]').val()!=null && $('input[name="coupon_no"]').val() != undefined && $('input[name="coupon_no"]').val()!='')
     {
      var coupon_no         = $('input[name="coupon_no"]').val();
    }
    else
    {
      var coupon_no         ='';
    }
    
    if($('input[name="qr_value"]').val()!=null && $('input[name="qr_value"]').val() != undefined && $('input[name="qr_value"]').val()!='')
     {
      var qr_value         = $('input[name="qr_value"]').val();
    }
    else
    {
      var qr_value         ='';
    }
    
    
   set_table(user,trasanction_type,payment_mode,trasanction_to,trasanction_from,coupon_no,qr_value);
}

if($('select[name="user"]').val()!=null && $('select[name="user"]').val() != undefined && $('select[name="user"]').val()!='')
{
    var user                = $('select[name="user"]').val();
}
else
{
   var user                = '';
}
if($('select[name="trasanction_type"]').val()!=null && $('select[name="trasanction_type"]').val() != undefined && $('select[name="trasanction_type"]').val()!='')
{
   var trasanction_type     = $('select[name="trasanction_type"]').val();
}
else
{
    var trasanction_type     = '';
}
if($('select[name="payment_mode"]').val()!=null && $('select[name="payment_mode"]').val() != undefined && $('select[name="payment_mode"]').val()!='')
{
   var payment_mode         = $('select[name="payment_mode"]').val();
}
else
{
   var payment_mode         ='';
}
if($('input[name="trasanction_to"').val()!=null && $('input[name="trasanction_to"').val() != undefined && $('input[name="trasanction_to"').val()!='')
{
   var trasanction_to       = $('input[name="trasanction_to"]').val();
}
else
{
    var trasanction_to       = '';
}
if($('input[name="trasanction_from"').val()!=null && $('input[name="trasanction_from"').val() != undefined && $('input[name="trasanction_from"').val()!='')
{
   var trasanction_from     = $('input[name="trasanction_from"]').val();
}
else
{
   var trasanction_from     = '';
}

   if($('input[name="coupon_no"]').val()!=null && $('input[name="coupon_no"]').val() != undefined && $('input[name="coupon_no"]').val()!='')
     {
      var coupon_no         = $('input[name="coupon_no"]').val();
    }
    else
    {
      var coupon_no         ='';
    }
    
        if($('input[name="qr_value"]').val()!=null && $('input[name="qr_value"]').val() != undefined && $('input[name="qr_value"]').val()!='')
     {
      var qr_value         = $('input[name="qr_value"]').val();
    }
    else
    {
      var qr_value         ='';
    }


set_table(user,trasanction_type,payment_mode,trasanction_to,trasanction_from,coupon_no,qr_value);

function set_table(user,trasanction_type,payment_mode,trasanction_to,trasanction_from,coupon_no,qr_value)
{
    // var routes= "{{ route('admin.wallet-transaction.data') }}";
    var routes= "{{ route('admin.wallet-transaction.data') }}?user="+user+"&trasanction_type="+trasanction_type+"&payment_mode="+payment_mode+"&trasanction_to="+trasanction_to+"&trasanction_from="+trasanction_from+"&coupon_no="+coupon_no+"&qr_value="+qr_value;
    var route_for_export="{{ route('export.wallet-transaction') }}?user="+user+"&trasanction_type="+trasanction_type+"&payment_mode="+payment_mode+"&trasanction_to="+trasanction_to+"&trasanction_from="+trasanction_from+"&coupon_no="+coupon_no+"&qr_value="+qr_value;
    $('.export_btn').attr('href', route_for_export);
    var table_blog = $('#table1').DataTable({
        processing: true,
        destroy:true,
        serverSide: true,
        aaSorting : [[0, 'desc']],
        ajax: routes,
        columns: [
        { data: 'id', name: 'id', searchable: false, render: function (data, type, row, meta) { return meta.settings._iDisplayStart + meta.row + 1; } },
        { data: 'full_name', name: 'full_name' },
        { data: 'qr_value', name: 'qr_value',visible:true },
        { data: 'point', name: 'point' },
        //{ data: 'current_points', name: 'current_points' },
        { data: 'transaction_type', name: 'transaction_type' },
        { data: 'add_date', name: 'created_at' },
        ]
    });
    table_blog.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });

    });
}


function displayimage(data,type,row,meta)
{
 var imageurl='{{ URL::to(App\Helpers\Thumbnail::image("/service/","200","65","ff=ffffff")) }}/'+data;
 var str='<img src="'+imageurl+'" />';
 return str;
}
function wordTrim(data,type,row,meta) {
  if(row.user === 'firm'){
   var str=row.firm_name;
   var res = str.substr(0,42);
   if(str.length>42)
    res=res+"..";
return res;
}else{
    var str=row.name;
    var res = str.substr(0,42);
    if(str.length>42)
        res=res+"..";
    return res;
}
}
});
function changedateformate(data,type,row,meta)
{
 return data;
}
</script>
<script>
    $(document).ready(function(){
       // toastr[response.msgType](response.msg, response.msgHead);
   });
</script>
@stop

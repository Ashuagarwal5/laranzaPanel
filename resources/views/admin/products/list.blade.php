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
{{-- <link rel="stylesheet" href="//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"> --}}
@stop
{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>QR Codes Manager</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>QR Codes Manager</li>
        <li class="active">Product QR  List</li>
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
                All QR Codes List
            </h4>
           <!--
            <div class="pull-right">
                <a href="{{ URL::to('admin/products/import') }}" class="btn btn-sm btn-info"><span class="glyphicon glyphicon-import"></span>Import Excel</a>
            </div>
            -->
            <div class="pull-right" style="margin-right: 6px;">
                <a href="{{ URL::to('cpmin/products/bulk-create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>Bulk QR Code</a>
            </div>
            <div class="pull-right" style="margin-right: 6px;">
                <a href="{{ URL::to('cpmin/products/create') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-plus"></span>QR Code</a>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
               <form id="filter_form" method="get" style="background: #f2f2f2;padding-top: 25px;margin-bottom: 17px;">
                 <div class="row">
                    <div class="col-xs-4 col-sm-4">
                     <div class="form-group">
                        <label for="agent">QR Value:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="qr_value" id="qr_value" style="width: 100%;" value="" />
                            <span class="input-group-addon info">
                                <span class="glyphicon glyphicon-user"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                     <div class="form-group">
                        <label for="agent">Reward Points:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="reward_points" id="reward_points" style="width: 100%;" value="" />
                            <span class="input-group-addon info">
                                <span class="glyphicon glyphicon-user"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                     <div class="form-group">
                        <label for="agent">Product Category:</label>
                        <div class="input-group">
                        <select name="product_group_code" id="product_group_code"  class="form-control"  >
                            <option value="">Select Product Category</option>
							@foreach ($category as $item)
								<option value="{{$item->id}}">{{$item->category_name}} </option>
							@endforeach
						</select>

                            <!-- <input type="text" class="form-control" name="product_group_code" id="product_group_code" style="width: 100%;" value="" /> -->
                            <span class="input-group-addon info">
                                <span class="glyphicon glyphicon-user"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                   <div class="col-xs-12 col-sm-12" style="margin-bottom:14px;text-align: center;">
                    <a href="{{ route('admin.products') }}">Reset Search &nbsp; &nbsp;</a>
                    <button type="submit" class="btn btn-danger">Search</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered " id="table_news">
            <thead>
                <tr class="filters">
                    <th>ID</th>
                    <th>QR Value</th>
                    <th>Reward Points</th>
                    <th>Product Category</th>
                    <th>Used Status</th>
                    <th>Add Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($data as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->qr_value}}</td>
                        <td>{{$item->reward_points}}</td>
                        <td>{{$item->category_name}}</td>
                        <td>{{$item->used_status}}</td>
                        <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                        <td>@if($item->used_status == "No")
                            <a class="delval btn btn-xs btn-primary" title="Edit QR Code" href="{{URL::to("cpmin/products/edit/$item->id")}}"><i class="fa fa-edit"></i></a>
                            @endif
                            <a class="delval btn btn-xs btn-info" title="Download QR Code" href="{{URL::to("download-code/$item->qr_value")}}"><i class="fa fa-download"></i></a>
                            <input type="hidden" name="copy_code" id="copy_code" class="copy_code" value="{{URL::to("download-code/$item->qr_value")}}">
                            <a class="delval btn btn-xs btn-success copy_link" data_value="{{URL::to("download-code/$item->qr_value")}}" title="Copy QR Code"><i class="fa fa-copy"></i></a>
                            @if($item->used_status == "No")
                            <a data-toggle="modal" data-target="#modal-regular" href="{{URL::to("cpmin/products/$item->id/confirm-delete")}}" class="delval btn btn-xs btn-danger"  title="Delete QR Code"><i class="fa fa-trash"></i></a>
                            @endif</td>
                    </tr>
                @endforeach --}}
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
{{-- <script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready( function () {
        $('#table_products').DataTable();
    } );
</script> --}}
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $('#filter_form').submit(function(event) {
            event.preventDefault();
            setData();
        });
        function setData()
        {
         if($('input[name="qr_value"]').val()!=null && $('input[name="qr_value"]').val() != undefined && $('input[name="qr_value"]').val()!='')
         {
            var qr_value         = $('input[name="qr_value"]').val();
        }
        else
        {
            var qr_value         ='';
        }
        if($('input[name="reward_points"]').val()!=null && $('input[name="reward_points"]').val() != undefined && $('input[name="reward_points"]').val()!='')
        {
            var reward_points         = $('input[name="reward_points"]').val();
        }
        else
        {
            var reward_points         ='';
        }
        if($('select[name="product_group_code"]').val()!=null && $('select[name="product_group_code"]').val() != undefined && $('select[name="product_group_code"]').val()!='')
        {
            var product_group_code         = $('select[name="product_group_code"]').val();
        }
        else
        {
            var product_group_code         ='';
        }
        set_table(qr_value,reward_points,product_group_code);
    }    
    if($('input[name="qr_value"]').val()!=null && $('input[name="qr_value"]').val() != undefined && $('input[name="qr_value"]').val()!='')
    {
        var qr_value         = $('input[name="qr_value"]').val();
    }
    else
    {
        var qr_value         ='';
    }
    if($('input[name="reward_points"]').val()!=null && $('input[name="reward_points"]').val() != undefined && $('input[name="reward_points"]').val()!='')
    {
        var reward_points         = $('input[name="reward_points"]').val();
    }
    else
    {
        var reward_points         ='';
    }
    if($('select[name="product_group_code"]').val()!=null && $('select[name="product_group_code"]').val() != undefined && $('select[name="product_group_code"]').val()!='')
    {
        var product_group_code         = $('select[name="product_group_code"]').val();
    }
    else
    {
        var product_group_code         ='';
    }
    set_table(qr_value,reward_points,product_group_code);
    function set_table(qr_value,reward_points,product_group_code)
    {
      var route= "{{ route('admin.products.data') }}?qr_value="+qr_value+"&reward_points="+reward_points+"&product_group_code="+product_group_code; 
      var table = $('#table_news').DataTable({
        processing: true,
        paging:true,
        destroy:true,
        serverSide: true,
        aaSorting : [[0, 'desc']],
        ajax: route,
        columns: [
        { data: 'id', name: 'id' },
        { data: 'qr_value', name: 'qr_value' },
        { data: 'reward_points', name: 'reward_points' },
        { data: 'category_name', name: 'category_name' },
        { data: 'used_status', name: 'used_status' },
        { data: 'add_date', name: 'add_date' },
        { data: 'actions', name: 'actions', orderable: true, searchable: true }
        ]
    });
      table.on( 'draw', function () {
        $('.livicon').each(function(){
            $(this).updateLivicon();
        });
    } );
  }
  function measurements(data,type,row,meta)
  {
   if(row.density==null)
       row.density = 'NA';
   if(row.thickness==null)
       row.thickness = 'NA';
   if(row.length==null)
       row.length = 'NA';
   if(row.width==null)
       row.width = 'NA';
   if(row.varient==null)
       row.varient = 'NA';
   var str='Density: '+row.density+'<br/>'+'Length: '+row.length+'&nbsp;&nbsp;'+'Width: '+row.width+'<br/>'+'Thickness: '+row.thickness+'&nbsp;&nbsp;'+'Varient: '+row.varient+'<br/>';
   return str;
}
});
</script>
<script>
	// return a promise
	function copyToClipboard(textToCopy) {
		// navigator clipboard api needs a secure context (https)
		if (navigator.clipboard && window.isSecureContext) {
			// navigator clipboard api method'
			return navigator.clipboard.writeText(textToCopy);
		} else {
			// text area method
			let textArea = document.createElement("textarea");
			textArea.value = textToCopy;
			// make the textarea out of viewport
			textArea.style.position = "fixed";
			textArea.style.left = "-999999px";
			textArea.style.top = "-999999px";
			document.body.appendChild(textArea);
			textArea.focus();
			textArea.select();
			return new Promise((res, rej) => {
				// here the magic happens
				document.execCommand('copy') ? res() : rej();
				textArea.remove();
			});
		}
	}
	$(document).on('click', '.copy_link', function(e) {

		let val = $(this).prev().val();
		copyToClipboard(val)
			.then(() => {
				toastr.success('Code copy successfully ');
			})
			.catch(() => console.log('error'));
	});
</script>
@stop

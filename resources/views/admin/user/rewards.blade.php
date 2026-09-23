@extends('admin/layouts/default')
@section('title')
{{ $manager_name }} Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
<link href="{{asset('assets/jquery-ui.css')}}" rel="stylesheet" type="text/css">
<style>
	.btn-default{
		border: 1px solid #ddd;
	}
	
	.header-points{
		min-height: 16.43px;
		padding: 15px;
		border: 1px solid #66a7ce;
		background: #ddeaff;
		margin: 1px;
	}
</style>

@stop
@section('content')
<section class="content-header">
  <h1>Customer Transaction History</h1>
  <ol class="breadcrumb">
    <li> <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="14" data-color="#000"></i> Dashboard </a> </li>
    <li>Customer Transaction History </li>
    <li class="active">Customer Point History List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content paddingleft_right15">
  <div class="row">
    <div class="panel panel-primary ">
      <div class="panel-heading clearfix">
        <h4 class="panel-title pull-left"> <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff" data-hc="white"></i> {{ $user->full_name }} Rewards Points Detail List </h4>
        <div class="pull-right"> <a href="{{ route('admin.user') }}" class="btn btn-sm btn-danger"> <i class="fa fa-arrow-left" aria-hidden="true"></i> <span style="margin-left:8px">Back</span></a> </div>
      </div>
      <div class="panel-body">
	 
	 
	 
	  <div class="header-points">
		<h5 class="modal-title"> Reward Point Summary:
		<a href="{{ route('user.rewards',[$ID,'Earn'])}}">Total Earned Points : {{ round($pointBalance['total_earn']) }}</a>   |  
		<a href="{{ route('user.rewards',[$ID,'Redeem'])}}">Total Redeem Points: {{ round($pointBalance['total_redeem']) }}</a>  |  
		<a href="{{ route('user.rewards',[$ID,'All'])}}">Balance Points: {{ round($pointBalance['balance']) }}</a> </h5>
		
		<div class="pull-right" style="margin-top: -26px;">
		 <a href="{{ route('admin.user.reward.add',['userid' => $ID]) }}" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-email"> <i class="fa fa-plus" aria-hidden="true"></i> <span style="margin-left:8px">Add Points</span></a>
		 </div>
</div>


        <div class="table-responsive">
        
        
        
        
         <form id="filter_form" method="get" style="background: #f2f2f2;;margin-bottom: 3px;" class="header-points">
                           <div class="row">
                            <div class="col-xs-4 col-sm-4">
                               <div class="form-group">
                                <label for="agent">QR Value:</label>
                                <div class="input-group">
<input type="text" class="form-control" name="qr_value" id="qr_value" style="width: 100%;" value="{{ isset($_GET['qr_value']) ? $_GET['qr_value'] : '' }}"  placeholder="Enter QR Value"/>
                                  <span class="input-group-addon info">
                                    <span class="glyphicon glyphicon-user"></span></span>
                                  </div>
                                </div>
                              </div>
                            
                              <div class="col-xs-4 col-sm-4">
                               <div class="form-group">
                                <label for="agent">Reward Points:</label>
                                <div class="input-group">
<input type="text" class="form-control" name="reward_points" id="reward_points" style="width: 100%;" value="{{ isset($_GET['reward_points']) ? $_GET['reward_points'] : '' }}" placeholder="Enter Reward Points"//>
                                  <span class="input-group-addon info">
                                    <span class="glyphicon glyphicon-user"></span></span>
                                  </div>
                                </div>
                              </div>
                              
                              <div class="col-xs-4 col-sm-4">
                               <div class="form-group" >
                                <label for="agent">Transaction Type</label>
                                
	                                <div class="input-group">
		                                <select name="transaction_type" class="form-control">
		                                	<option value="">Select</option>

			<option value="Earn" @if (isset($_GET['transaction_type'])) {{($_GET['transaction_type']=='Earn')}} selected="selected" @endif>Earn</option>
			<option value="Redeem" @if (isset($_GET['transaction_type'])) {{ ($_GET['transaction_type']=='Redeem') }}selected="selected" @endif>Redeem</option>
			<option value="All" @if (isset($_GET['transaction_type'])) {{($_GET['transaction_type']=='All') }}selected="selected" @endif>All</option>
										</select>
										
                                  <span class="input-group-addon info">
                                    <span class="glyphicon glyphicon-user"></span></span>
                                  </div>
                                </div>
                              </div>
                              

                            </div>
                 
                        
                        <div class="row">
	                        <div class="col-xs-12 col-sm-12" style="margin-bottom:14px;text-align: center;">
                                <a href="{{ route("user.rewards",[$ID,"All"]) }}">Reset Search &nbsp; &nbsp;</a>
                                
                                <button type="submit" class="btn btn-danger">Search</button>
                            </div>
	                        
                        </div>
                        
                    </form>
                    
                    
        
        
        
        
          <div class="modal-body" style="padding-top:13px;padding-left:0px;padding-right:0px;">
          
          
            <div class="row">
              <div class="">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                    <tr>
                      <th>Transaction Id </th>
                      <th>QR Value </th>
                      <th>Reward Points </th>
                      <th>Transaction Type </th>
                      <th>QR Info </th> 
                      <th>Current Balance</th>                                               
                      <th>Date</th>
                     <!-- <th>Action</th> -->
                    </tr>
                  </thead>
                  <tbody>
                  
                  @if(isset($points) && sizeof($points)>0)
                  @foreach($points as $key => $data)
                  <tr> 
                    <td>{{ $data->id }}</td>
                    <td>{{ isset($data->qr_value) ? $data->qr_value : '-NA-' }}</td>
                    <td>{{ $data->point }} Points</td>
                    <td>{{ $data->transaction_type }}<br/><small class="text-light"><i>{{ $data->description }}</i></small></td>
                    <td>
                     @php $product = App\Products::productMeasurements($data->product_id) @endphp
                     
                    @if($data->product_id!='') ID: {{ $data->product_id}} @else -NA- @endif
	              
	                    
                    </td>  
                    <td>
                    {{ $data->current_points }}
                    @if($data->lg!='') Longitude: {{ $data->lg}}  @endif
	                    @if($data->lt!='') &nbsp; Latitude: {{ $data->lt}}  @endif
                    </td>                                       
                    <td>{{ date('d/M/y', strtotime($data->created_at)) }}</td>
                    <!--<td>
                    
                    <a class="btn btn-primary" href="{{ route('admin.user.reward.edit',$data->id)}}" title="Edit"  data-toggle="modal" data-target="#modal-email"><i class="fa fa-edit"></i> </a>&nbsp; -->
					<!--
					<a  title="Delete" class="btn btn-danger enable-tooltip" href="{{ route("admin.reward.confirm-delete",$data->id) }}" data-toggle="modal" data-target="#modal-regular"><i class="fa fa-trash"></i> </a>
					</td>  -->
                  </tr>
                  @endforeach
                  @else
                  <tr>
                    <td colspan="8" style="height:85px" valign="middle" align="center"><br/>
                      No Record Found </td>
                  </tr>
                  @endif
                  </tbody>
                  
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- row-->
</section>
@stop
	@section('footer_scripts')
<script src="{{asset('assets/vendors/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
<script>
		$( function() {
			$( "#datepicker" ).datepicker({
				format: "dd/mm/yyyy",
				autoclose: true,
				changeMonth: true,
				changeYear: true,
				yearRange: '1900:+0'
			});
		} );
	</script>
@stop
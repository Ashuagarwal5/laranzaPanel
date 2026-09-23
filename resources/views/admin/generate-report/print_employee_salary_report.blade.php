<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>@section('title')Salary Report @show</title>
	<link href="{{ asset('assets/default/css/bootstrap.min.css') }}" rel="stylesheet">	
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<style>
body {
font-family: 'Poppins', sans-serif, Arial, Helvetica, sans-serif;
color:#000;	
	}
.table > thead > tr > th {
background:#222;
border-color:#333;
color:#fff;	
	}
.table-bordered > tbody > tr > td {
border-color:#333;	
	}
.table-bordered {
    border: 1px solid #333;
}
hr {
border-color:#333;	
	}
h1 {
font-size:18px;	
	}	
@media print {
.container {
width:100%;	
	}
.table > thead > tr > th {
background:#222 !important;
border-color:#333 !important;
color:#fff !important;	
	}
.table-bordered > tbody > tr > td {
border-color:#333 !important;	
	}
.table-bordered {
    border: 1px solid #333 !important;
}
hr {
border-color:#333 !important;	
	}
h1 {
font-size:18px;	
	}
small {
color:#fff !important;	
	}
clr {
clear:both !important;	
	}					
}
</style>

</head>
  <body>
  <div class="container">
 <div class="">
	
@if(count($data)==0)
No Record Found
@else

<div class="row">


 <div class="col-sm-4" style="float:left; width:30%;"> 
 
<br>
		<img class="img-responsive" src="{{ asset('assets/default/images/logo.png') }} "  width="170px" height="50px" alt="Company Logo">
	 
</div>

<div class="col-sm-4" style="float:right; width:30%;">
<br>
<strong>Codespur Technologies Pvt. Ltd</strong><br>
Ph: +91-141-2399664
</div>	


<div class="clr"></div>

<div class="col-sm-12" style="width:100%; float:left;">
<hr style="margin-bottom:0;">				   
</div>

 <div class="col-sm-12" style="width:100%; text-align:center; float:left;">              
    <h1 class="text-center" style="margin-top:15px;"><strong> Salary Report</strong></h1>
   <table class="table table-bordered" style="font-size:12px">
   <thead>
   <tr>
   
   
   <th>Period Of Report</th>
   <th>Company</th>
   <th>Employee</th>
   <th>Date Of Report</th>
   </tr>
   </thead>
   <tbody>
   <tr>
      <td align="left"> {{$from_year}} {{$from_month}} - {{$to_year}} {{$to_month}} </td>
      <td align="left"> {{$company}}  </td>
      <td align="left"> {{$employee}} </td>
     <td align="left">{{ $now->format('d-M-Y') }}</td>

   </tr>
   </tbody>
   </table> 
    </div> 
  
  
  
  </div>
  
<table class="table itemTable table-bordered table-striped" style="font-size:12px"> 

<!--<h4>Date Report Generated  :</h4>-->
  <thead> 
	  <tr>
              	@if($var == "month")
				<th>E_ID</th>
				<th>Name</th>
				<th>Company</th>
				@else
				<th>Year</th>
				<th>Month</th>
				@endif
				<th>Gross Salary</th>
				<th>Working Days</th>
				<th>Saved Leave</th>
			    <th>Leave Taken</th>
			    <th>Leave Earned</th>
			    <th>Paid Leave</th>
			    <th>Effective Leave</th>
			    <th>Leave Deduction Amt</th>
			    <th>Allowance</th>
			    <th>Payable Amt</th>
			    <th>Secutity Deduction Amt</th>
			    <th>Pay - Deduction</th>
			    <th>Refund</th>
			    <th>Net Payable</th>
			    <th>Deposit Details</th>
	  </tr> 
  </thead>
 
  <tbody>

     	@foreach($data as $key1 => $value)
			<tr>
		@if($var == "month")
			<td> @if($value->emp_id){{$value->emp_id}}@else 0 @endif</td>
		    <td> @if($value->employee_name){{ $value->employee_name }}@else 0 @endif</td>
		    <td> @if($value->company_name){{ $value->company_name }}@else 0 @endif</td>
		    @else
		    <td> @if($value->year){{ $value->year }}@else 0 @endif</td>
		    <td> @if($value->month){{ $value->month }}@else 0 @endif</td>
			@endif
		    <td>@if($value->gross_pay) {{ $value->gross_pay }}@else 0 @endif</td>
			<td>@if($value->working_days){{ $value->working_days }}@else 0 @endif</td>
			<td>@if($value->total_saved_leaves){{ $value->total_saved_leaves }}@else 0 @endif</td>
			<td>@if($value->leave_taken){{$value->leave_taken}} @else 0 @endif</td>
			<td>@if($value->leave_earned){{ $value->leave_earned }}@else 0 @endif</td>		
			<td>@if($value->paid_leave){{ $value->paid_leave }}@else 0 @endif</td>		
			<td>@if($value->effective_leave){{ $value->effective_leave }}@else 0 @endif</td>		
			<td>@if($value->leave_deduction_amt){{ $value->leave_deduction_amt }}@else 0 @endif</td>		
			<td>@if($value->allowance){{ $value->allowance }}@else 0 @endif</td>		
			<td>@if($value->payable_amount){{ $value->payable_amount }}@else 0 @endif</td>		
			<td>@if($value->security_deduction_amount){{ $value->security_deduction_amount }}@else 0 @endif</td>		
			<td>@if($value->pay_minus_deduction){{ $value->pay_minus_deduction }}@else 0 @endif</td>		
			<td>@if($value->refund){{ $value->refund }}@else 0 @endif</td>		
			<td>@if($value->net_payable_amount){{ $value->net_payable_amount }}@else 0 @endif</td>		
			<td>@if($value->deposit_details){{ $value->deposit_details }}@else 0 @endif</td>		
				
		</tr>
	
     	@endforeach	
			
            
   </tbody> 
   </table>


<!--

 <div class="col-sm-12"> 
<h1 class="text-center"><strong>SUMMARY </strong></h1>
</div>
<table class="table itemTable table-bordered" style="font-size:12px"> 
<thead>
<tr>
 				<th>head 1</th>
 				<th>head 2</th>
 				<th>head 3</th>

</tr>
</thead>
<tbody>
	   <tr> 
		   
		         <td>detail 1</td>	
		         <td>detail 2</td>	
		         <td>detail 3</td>	
			</tr>
		</tbody>    
 </table>
-->


@endif


</div>
</div>
<script type="text/javascript" src="{{ asset('assets/admin/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/default/js/jquery-ui-custom.min.js') }}"></script>

 </body>
 
</html>


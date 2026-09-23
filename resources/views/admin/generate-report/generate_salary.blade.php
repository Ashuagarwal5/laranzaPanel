<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
  
    <title>@section('title')Generate Salary @show</title>
	<link href="{{ asset('assets/default/css/bootstrap.min.css') }}" rel="stylesheet">	
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600" rel="stylesheet">
	<link href="{{ asset('assets/admin/css/toastr.css') }}" rel="stylesheet">
	

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
	.box-error{
border-color: red !important;
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
font-size:30px;	
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
font-size:30px;	
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
  <div class="container-fluid">
 <div class="">
	
@if(count($data)==0)
No Record Found
@else

<div class="row">

 <div class="col-sm-12" style="width:100%; text-align:center; float:left;">              
    <h1 class="text-center" style="margin-top:15px;"><strong> EMPLOYEE SALARY</strong></h1>

   <table class="table table-bordered">
   <thead>
   <tr>
   
    <th style="width:150px">Salary For</th>
   <th>Period Of Salary</th>
   <th>Salary Generate Date</th>
   </tr>
   </thead>
   <tbody>
   <tr>
      <td align="left" > {{ $month }} {{ $year }} </td>
      <td align="left"> 1 {{ $month }} - {{$days}} {{ $month }}</td>
   <td align="left"> {{ date('d F,Y',$today)}} </td>
 
   </tr>
   </tbody>
   </table> 
    </div> 
  
  </div>
  
 
 <span> <font color="black"> Note: Plese fill Allowance , Earned Leave and Refund Before Leave Taken If Required in Any Condition.</font></span>
  <div class="table-responsive">
	  
<table class="table itemTable table-bordered table-striped"> 

<!--<h4>Date Report Generated  :</h4>-->
  <thead> 
	  <tr>
                <th >ID</th>
				<th>Name</th>
				<th >Gross Pay</th>
				<th>Working Days</th>
				<th >Saved Leave</th>
			    <th >Leave Taken</th>
			    <th >Earned Leave</th>
			    <th>Paid Leave</th>
			    <th>Effective Leave</th>
			    <th>Available Leave</th>
			    <th>Leave Deduction Amt</th>
			    <th>Allowance</th>
			    <th>Payable Amt</th>
			    <th>Security Deduction Amt</th>
			    <th>Pay - Deduction Amt</th>
			    <th>Refund</th>
			    <th>Net Payable Amt</th>
			    <th>Deposit Details</th>
			    <th>Action</th>
		   
	  </tr> 
  </thead>
 
  <tbody>
	 

     	@foreach($data as $key1 => $value)
			<tr>
		<form method="post" id="edit_{{$value->emp_id}}" class="submitform" action="{{route('store_employee-salary')}}">
		 <input type="hidden" name="_token" value="{{ csrf_token() }}" />
		<input type="hidden" name="emp_id" value="{{$value->emp_id}}" />
		<input type="hidden" name="employee_name" value="{{$value->employee_name}}" />
		<input type="hidden" name="company_id" value="{{$value->company_id}}" />
		
				<div class="alert ajax_report alert-info alert-hide" role="alert" style="display:none">
				<span class="close" >&times;</span>
				<span class="ajax_message"><strong>Please wait! </strong>Your action is in proccess...</span>
				</div>
	    <td >@if($value->emp_id){{ $value->emp_id }}@else N/A @endif</td>
		<td> @if($value->employee_name){{ $value->employee_name }}@else N/A @endif</td>
		<td > @if($value->status=="paid"){{ $value->gross_pay }} @else  <input type="text" autocomplete="off" @if($value->gross_pay==0) style="width:100px;border-color: red;" @else style="width:100px" readonly="true"  @endif  value="{{$value->gross_pay}}" name="gross_pay" class="form-control" id="gross_pay{{$value->emp_id}}" placeholder="Gross Pay">  @endif </td>	 
	    <td > @if($value->status=="paid"){{ $days }} @else <input type="text" readonly="true" style="width:100px" value="{{$days}}" name="working_days" class="form-control" id="working_days{{$value->emp_id}}" placeholder="Working Days"> @endif </td> 
		<td > @if($value->status=="paid"){{ $value->total_saved_leaves }} @else <input type="text" autocomplete="off" readonly="true" style="width:100px" value="{{$value->total_saved_leaves }}" name="total_saved_leaves" class="form-control" id="total_saved_leaves{{$value->emp_id}}" placeholder="Save Leave"> @endif </td>
		<td>  @if($value->status=="paid"){{ $value->leave_taken }} @else<input type="text" autocomplete="off" style="width:100px" value="{{$value->leave_taken}}" data-id="{{$value->emp_id}}" name="leave_taken" class="leave form-control" id="taken_leave{{$value->emp_id}}" placeholder="Leave Taken"> @endif </td>
		<td > @if($value->status=="paid"){{ $value->leave_earned }} @else <input type="text" autocomplete="off" style="width:100px" value="{{$value->leave_earned}}" name="leave_earned" class="form-control" id="leave_earned{{$value->emp_id}}" placeholder="Leave Earned"> @endif</td>
		<td > @if($value->status=="paid"){{ $value->paid_leave }} @else<input type="text" readonly="true" style="width:100px" value="{{$value->paid_leave}}" name="paid_leave" class="form-control" id="paid_leave_{{$value->emp_id}}" placeholder="Paid Leave"> @endif </td>
		<td>  @if($value->status=="paid"){{ $value->effective_leave }} @else <input type="text" readonly="true" style="width:100px" value="{{$value->effective_leave}}" name="effective_leave" class="form-control" id="effective_leave{{$value->emp_id}}" placeholder="Effective Leave"> @endif </td>
		<td > @if($value->status=="paid") {{$value->available_leave}} @else <input type="text" readonly="true" style="width:100px" value="{{$value->available_leave}}" name="available_leave" class="form-control" id="available_leave{{$value->emp_id}}" placeholder="Available Leave"> @endif </td>
		<td> @if($value->status=="paid"){{ $value->leave_deduction_amt }} @else <input type="text" readonly="true" style="width:120px" value="{{$value->leave_deduction_amt}}" name="leave_deduction_amt" class="form-control" id="deduction_amt{{$value->emp_id}}" placeholder="Leave Deduction Amt"> @endif </td>
		<td>  @if($value->status=="paid"){{ $value->allowance }} @else <input type="text" autocomplete="off" style="width:90px" value="{{$value->allowance}}" name="allowance" class="form-control" id="allowance{{$value->emp_id}}" placeholder="Allowance"> @endif </td>
		<td> @if($value->status=="paid") {{ $value->payable_amount }} @else <input type="text" readonly="true" style="width:100px" value="{{$value->payable_amount}}" name="payable_amount" class="form-control" id="payable_amount{{$value->emp_id}}" placeholder="Payable Amt"> @endif </td>
		<td> @if($value->status=="paid") {{ $value->security_deduction_amount }} @else <input type="text" readonly="true" style="width:120px" value="{{$value->security_deduction_amount}}" name="security_deduction_amount" class="form-control" id="security_deduction_amount{{$value->emp_id}}" placeholder="Security Deduction Amt"> @endif </td>
		<td> @if($value->status=="paid") {{ $value->pay_minus_deduction }} @else <input type="text" readonly="true" style="width:120px" value="{{$value->pay_minus_deduction}}" name="pay_minus_deduction" class="form-control" id="pay_minus_deduction{{$value->emp_id}}" placeholder="Pay Minus Deduction Amt"> @endif </td>
		<td> @if($value->status=="paid") {{ $value->refund }} @else  <input type="text" style="width:90px" autocomplete="off" value="{{$value->refund}}" name="refund" class="form-control" id="refund{{$value->emp_id}}" placeholder="Refund"> @endif </td>
		<td>  @if($value->status=="paid") {{ $value->net_payable_amount }} @else  <input type="text" readonly="true" style="width:120px" value="{{$value->net_payable_amount}}" name="net_payable_amount" class="form-control" id="net_payable_amount{{$value->emp_id}}" placeholder="Net Payable Amt"> @endif </td>
		<td>  @if($value->status=="paid") {{ $value->deposit_details }} @else  <input type="text" readonly="true" style="width:120px" value="{{$value->deposit_details}}" name="deposit_details" class="form-control" id="exampleInputEmail1" placeholder="Deposite Details"> @endif </td>
	    <td> @if($value->status=="paid") <a href="{{route('edit_salary_report', ['year'=>$year,'month'=>$month,'id'=>$value->emp_id])}}" class="btn btn-info"> Edit</button> @else	<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Generate</button>  @endif </td>
		<!--	<td> @if($value->add_date){{ $value->add_date }}@else N/A @endif </td> -->		
		
		</form>		  
		
		
		</tr>
	
     	@endforeach	
			
            
   </tbody> 
   </table>

</div>
<!--

 <div class="col-sm-12"> 
<h1 class="text-center"><strong>SUMMARY </strong></h1>
</div>

<table class="table itemTable table-bordered"> 
<thead>
<tr>
                 <th>Total Received Amount</th>
                <th>Total Payable Amount</th>
                <th>Total Expenses Amount</th>
                <th>Net Payable Amount</th>
</tr>
</thead>
<tbody>
	   <tr> 
		  <td>100</td>
		  <td>100</td>
		  <td>100</td>
		  <td>100</td>
		  	</tr>
		</tbody>    
 </table>

-->
@endif

</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="{{ asset('assets/default/js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/default/js/jquery.form.js') }}" type="text/javascript"></script>

<!--
<script src="{{ asset('assets/default/js/formClass.js') }}" type="text/javascript"></script>
-->

<script>
$(".leave").keyup(function(){
   var leave = $(this).val();
	var id = $(this).attr('data-id');
	var paid_leave_id = "#paid_leave_"+id;
	var total_saved_leaves_id = "#total_saved_leaves"+id;
	var effective_leave_id = "#effective_leave"+id;
	var available_leave_id = "#available_leave"+id;
	var gross_pay_id = "#gross_pay"+id;
	var working_days_id ="#working_days"+id;
	var deduction_amt_id = "#deduction_amt"+id;
	var security_amount_id = "#security_deduction_amount"+id;
	var payable_amount_id = "#payable_amount"+id;
	var minus_id = "#pay_minus_deduction"+id;
	var net_id = "#net_payable_amount"+id;
	var refund_id = "#refund"+id;
	var allowance_id = "#allowance"+id;
	var leave_earned_id = "#leave_earned"+id;
	
	var gross_pay_amt =   $(gross_pay_id).val();
	var gross_pay_amt = parseFloat(gross_pay_amt);
	
	var working_days_int = $(working_days_id).val();
	var saved_leave = $(total_saved_leaves_id).val();
	var one_day_salary = parseFloat(gross_pay_amt/working_days_int).toFixed(2);
	
	
   if(leave > 0)
	 {	 
		 if(saved_leave==0)
			 {     $(paid_leave_id).val(leave); 
				    $(effective_leave_id).val(leave);
				    $(available_leave_id).val(0);
			  }
		  if(saved_leave==0.5)
			 {   
				 if(leave == 0.5)
				  { $(paid_leave_id).val(0.5); 
				    $(effective_leave_id).val(0);
				    $(available_leave_id).val(0);
			       }
			      if(leave > 0.5)
				  { $(paid_leave_id).val(0.5); 
				    $(effective_leave_id).val(leave - 0.5);
			        $(available_leave_id).val(0);
			       } 
	         }
			 if(saved_leave==1)
			 {   
				  if(leave == 0.5)
				  {   $(paid_leave_id).val(0.5); 
				      $(effective_leave_id).val(0);
			          $(available_leave_id).val(0.5);
			       }
			      if(leave == 1)
			       {  $(paid_leave_id).val(1); 
				      $(effective_leave_id).val(0);
				      $(available_leave_id).val(0);
			        }
				   if(leave > 1)
			       {  $(paid_leave_id).val(1); 
				      $(effective_leave_id).val(leave-1);
				      $(available_leave_id).val(0);
			       }
		     }
			 if(saved_leave==1.5)
			 {     
				  if(leave == 0.5)
				    {  $(paid_leave_id).val(0.5); 
					   $(effective_leave_id).val(0);
					   $(available_leave_id).val(1);
					 }
			      if(leave == 1)
			       { $(paid_leave_id).val(1); 
				     $(effective_leave_id).val(0);
				     $(available_leave_id).val(0.5);
			       } 
				  if(leave == 1.5)
				  {  $(paid_leave_id).val(1.5); 
				     $(effective_leave_id).val(0);
				     $(available_leave_id).val(0);
			       }
			      if(leave > 1.5)
			       { $(paid_leave_id).val(1.5); 
				     $(effective_leave_id).val(leave-1.5);
				     $(available_leave_id).val(0);
				   } 
		     }    
			 if(saved_leave==2)
			 {   
				  if(leave == 0.5)
				  { $(paid_leave_id).val(0.5); 
				    $(effective_leave_id).val(0);
			        $(available_leave_id).val(1.5);
			       }
			      if(leave == 1)
			       { $(paid_leave_id).val(1); 
				     $(effective_leave_id).val(0);
				     $(available_leave_id).val(1);
			       } 
				  if(leave == 1.5)
				  { $(paid_leave_id).val(1.5); 
				    $(effective_leave_id).val(0);
			        $(available_leave_id).val(0.5);
			       }
			      if(leave == 2)
			       { $(paid_leave_id).val(2); 
				     $(effective_leave_id).val(0);
				     $(available_leave_id).val(0);
			       }
				  if(leave > 2)
			       { $(paid_leave_id).val(2); 
				     $(effective_leave_id).val(leave-2);
				     $(available_leave_id).val(0);
				   } 	   
		     }
		    if(saved_leave >2 )
            {
				
			    if(leave == 0.5)
				  { $(paid_leave_id).val(0.5); 
				    $(effective_leave_id).val(0);
			        $(available_leave_id).val(saved_leave - 0.5);
			       }
			      if(leave == 1)
			       { $(paid_leave_id).val(1); 
				     $(effective_leave_id).val(0);
				     $(available_leave_id).val(saved_leave - 1);
			       } 
				  if(leave == 1.5)
				  { $(paid_leave_id).val(1.5); 
				    $(effective_leave_id).val(0);
			        $(available_leave_id).val(saved_leave - 1.5);
			       }
			      if(leave == 2)
			       { $(paid_leave_id).val(2); 
				     $(effective_leave_id).val(0);
				     $(available_leave_id).val(saved_leave - 2);
			       }
				  if(leave > 2)
			       {   
					   $(paid_leave_id).val(2); 
				       $(effective_leave_id).val(leave-2);
				       $(available_leave_id).val(saved_leave - 2);
				   
				   } 	   
				
			}		  
     }
	  else
	  { // alert("in else leave 0");
		    $(paid_leave_id).val(0); 
		    $(effective_leave_id).val(0);
		    $(available_leave_id).val(saved_leave);
	  }  
	
	
	var leave_earned =   $(leave_earned_id).val();
	var leave_earned = parseFloat(leave_earned);
	if(leave_earned > 0)
	{
	  var available_leave = $(available_leave_id).val();
	  var available_leave = parseFloat(available_leave);
	$(available_leave_id).val(available_leave + leave_earned);
	
	}		           
	var effective_leave_value = parseFloat($(effective_leave_id).val()).toFixed(2);
	$(deduction_amt_id).val(effective_leave_value*one_day_salary);
	
	var lda = $(deduction_amt_id).val();
	var lda = parseFloat(lda);
	
	var allowance = $(allowance_id).val();
	var allowance = parseFloat(allowance);
	var plus =  allowance + gross_pay_amt;
	var plus = parseFloat(plus);
	 
	
	$(payable_amount_id).val(plus - lda);      
	
	var payable_amt = $(payable_amount_id).val();
	var payable_amt = parseFloat(payable_amt);
	
	var security_amt = $(security_amount_id).val();
	
	if(security_amt == 0)
	{	$(security_amount_id).val(0.00);           
	 }
	else
	{  var security_amt_1 = (payable_amt*10)/100;
		var security_amt_1 = parseFloat(security_amt_1).toFixed(2);
		$(security_amount_id).val(security_amt_1);
    }

	
	var security_amount = $(security_amount_id).val();

	
	var pmd =  payable_amt - security_amount;
	var pmd =  parseFloat(pmd).toFixed(2);
	 
	 $(minus_id).val(pmd);
	
	
	var refund = $(refund_id).val();
	var pay_minus = $(minus_id).val();
	var pay_minus = parseFloat(pay_minus);
	var refund = parseFloat(refund);
	var net = refund + pay_minus;
	
	var net =  parseFloat(net).toFixed(2);
	$(net_id).val(net);
	
	
});
</script>





<script>
$(document).ready(function() {

	$(document).on("submit", ".submitform", function (event) {
		//alert('ok');
		//return false;
	var posturl=$(this).attr('action');
	var callbackFunction=$(this).attr('data-callback_function');
	if(callbackFunction)
	{
		if(callbackForm(callbackFunction) == false)
		{
			return false;
		}
	}
	var btn_txt;
	var formid = $(this).attr('id');
	if(formid)
	var formid = '#'+formid;
	else
	var formid = ".submitform";




	$(this).ajaxSubmit({
			url: posturl,
			dataType: 'json',
			beforeSend: function(){

				$('.formmessage').remove();
			$(formid).find('.box-error').attr("placeholder", "")
			$(formid).find('.box-error').removeClass('box-error');
			$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeIn(200);
			$(formid).find('.alert').addClass('alert-info');
			$(formid).find('.alert').show();
			$(formid).find('.ajax_message').html('<strong>Please Wait ! <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></strong>');
			},
			success: function(response){
				
            $(".submit").removeAttr("disabled", 'disabled');
             
			$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
            $(formid).find('.display-error').removeClass('box-error');
				           
              
                if (response.status == "success") {
					//alert("success");
					toastr[response.msgType](response.msg, response.msgHead);
                    $(formid).find('.alert').fadeIn();
                    $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
					
					$(formid).find('.alert').fadeOut();
                } 
                else {					
					//alert("erroe");
					
                    $(formid).find('.alert').fadeIn();
                    $(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
                    
                    $.each(response.errorArray, function(key, value) {
						
					
                        console.log(key + " => " + value);
                    	
						var placeH	=value;						
						$(formid).find('input[name="' + key + '"], textarea[name="' + key + '"]').attr("placeholder", placeH);
						$(formid).find('input[name="' + key + '"], textarea[name="' + key + '"]').addClass('box-error');
						

						
                    });
                }
           
		
            
            if (response.slideToTop) {
                $('html, body').animate({
					
                    scrollTop: $(formid).offset().top - 290
                }, 800);
            }
            if (response.url)
                window.location.href = response.url;
            if (response.selfReload)
            {
                window.location.reload();
			}
           
            if(response.ajaxPageCallBack)
			{
				
				response.formid = formid;				
				ajaxPageCallBack(response);

			}
			},
			error: function(response) {
			alert('server error');          
               
        }
		});
	return false;
	});

	$(document).on("click", ".alert .close", function (event) {
		$(this).closest(".ajax_report").hide();
		$(this).closest(".alert").hide();
	});
});



</script>





 </body>
 
</html>


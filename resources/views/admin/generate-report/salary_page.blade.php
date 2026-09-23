@extends('admin/layouts/default_inner')
@section('title')Reports @parent @stop
@section('header_styles')
@stop
@section('meta')
@stop
@section('content')
        <div class="m-container">
        <div class="col-sm-12 main">
       <div class="row">
<div class="col-lg-6"> <h2>Salary</h2></div>
<div class="col-lg-6">

<!--<p class="text-right">
<a href="javascript:" class="btn btn-success"><i class="fa fa-download"></i> Export Data </a></p>-->

</div>
<div class="clr"></div>
<hr>
</div>

<div class="jumbotron text-center">
<br>
<br>
<h1>Select Year & Month For Generate Salary</h1>
<br>



<div class="row" id="month_filter">
<div class="whars-report">
<div class="col-sm-12">

</div>
	
	<form method="post"  action="{{route('generate_salary_page')}}">
       	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
       
<h4>
	
<div class="col-sm-5" >

 <label>Select Year</label>
 <div class="smart-forms">
 <label class="field select">
 <select class="form-control shadow-n" name="year">
        <option>Select Year</option>
        <?php 	for($i=2018;$i<2050;$i++)  { ?>
        
 <option @if(isset($year) && $year == $i) selected="" @endif  value="{{$i}}">{{$i}}</option>
         <?php	
		    }
		  ?>	   
 </select>
   <i class="arrow"></i>    
 </label>
 </div>
 </div>
 

 

	
<div class="col-sm-5" >

 <label>Select Month</label>
 <div class="smart-forms">
 <label class="field select">
<select class="form-control shadow-n" name="month">
 <option value="">Select Month </option>
  <option @if(isset($mon) && $mon == "January") selected="" @endif  value="January">January </option>
 <option @if(isset($mon) && $mon == "February") selected="" @endif  value="February">February</option>
 <option @if(isset($mon) && $mon == "March") selected="" @endif  value="March">March</option>
 <option @if(isset($mon) && $mon == "April") selected="" @endif  value="April">April</option>
 <option @if(isset($mon) && $mon == "May") selected="" @endif  value="May">May</option>
 <option @if(isset($mon) && $mon == "June") selected="" @endif   value="June">June</option>
 <option @if(isset($mon) && $mon == "July") selected="" @endif  value="July">July</option>
 <option @if(isset($mon) && $mon == "August") selected="" @endif  value="August">August</option>
 <option @if(isset($mon) && $mon == "September") selected="" @endif  value="September">September </option>
 <option @if(isset($mon) && $mon == "October") selected="" @endif  value="October"> October</option>
 <option @if(isset($mon) && $mon == "November") selected="" @endif  value="November">November</option>
 <option @if(isset($mon) && $mon == "December") selected="" @endif  value="December">December</option>

 </select>
 
   <i class="arrow"></i>    
 </label>
 </div>
 </div>
 

</h4>

   
				 
		
 <div class="col-sm-2 text-center">

    <div class="form-group">
		<label>&nbsp;</label>
        <br>
      <button class="btn btn-success submit" id="preview1"> &nbsp; Generate &nbsp;</button>
    </div>
   
  </div>
 
 </form>
 
 
  
  <div class="clr"></div>
</div>
<div class="clr"></div>
</div>




<br>
</div>

          

        </div>
        <div class="clr"></div>
        </div>
@stop
@section('footer_scripts')
@stop

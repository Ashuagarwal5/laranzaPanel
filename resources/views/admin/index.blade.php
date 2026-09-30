@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

<link href="{{ asset('assets/admin/vendors/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/admin/css/pages/calendar_custom.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" media="all" href="{{ asset('assets/admin/vendors/bower-jvectormap/css/jquery-jvectormap-1.2.2.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/admin/vendors/animate/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/pages/only_dashboard.css') }}"/>
<meta name="_token" content="{{ csrf_token() }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/vendors/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/pages/jscharts.css') }}" />

@stop

{{-- Page content --}}
@section('content')
@php
use Cartalyst\Sentinel\Native\Facades\Sentinel;
$users=(App\RoleUser::manager(Sentinel::getUser()->id));
$privileges=json_decode($users->privileges,true);
@endphp
@if(Sentinel::inRole('sub-admin') && ($privileges[0]==null))

<center><h1>Welcome to Dashboard</h1></center>
@else
<section class="content-header">
    <div class="row">
        <div class="col-sm-8">
            <h1>Welcome to Dashboard</h1>
        </div>
    </div>
</section>
<section class="content">
  <div class="row">

    <div class="col-lg-4 col-md-4 col-sm-4 margin_10 animated fadeInLeftBig">
        <div class="redbg no-radius">
            <div class="panel-body squarebox square_boxs">
                <div class="col-xs-12 pull-left nopadmar">
                    <div class="row">
                        <div class="square_box col-xs-7 text-right">
                            <span>Total Customers</span>

                            <div class="number" id="myTargetElement1">@if(isset($user)){{$user}}@else 0 @endif</div>
                        </div>
                        <i class="livicon  pull-right" data-name="user" data-l="true" data-c="#fff"
                        data-hc="#fff" data-s="70"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-4 margin_10 animated fadeInRightBig">
        <div class="lightbluebg no-radius">
            <div class="panel-body squarebox square_boxs">
                <div class="col-xs-12 pull-left nopadmar">
                    <div class="row">
                        <div class="square_box col-xs-7 pull-left">
                            <span>Total Reward Point</span>

                            <div class="number" id="myTargetElement4">@if(isset($total_redeem_point)){{$total_redeem_point}}@else 0 @endif</div>
                        </div>
                        <i class="livicon pull-right" data-name="users" data-l="true" data-c="#fff"
                        data-hc="#fff" data-s="70"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-4 margin_10 animated fadeInLeftBig">
        <div class="palebluecolorbg no-radius">
            <div class="panel-body squarebox square_boxs">
                <div class="col-xs-12 pull-left nopadmar">
                    <div class="row">
                        <div class="square_box col-xs-7 text-right">
                            <span>Total Qr Codes</span>

                            <div class="number" id="myTargetElement1">@if(isset($total_qrcode)){{$total_qrcode}}@else 0 @endif</div>
                        </div>
                        <i class="livicon  pull-right" data-name="desktop" data-l="true" data-c="#fff"
                        data-hc="#fff" data-s="70"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-4 margin_10 animated fadeInDownBig">
        <div class="goldbg no-radius">
            <div class="panel-body squarebox square_boxs">
                <div class="col-xs-12 pull-left nopadmar">
                    <div class="row">
                        <div class="square_box col-xs-7 pull-left">
                            <span>Total Points Earned </span>
                            
                            <div class="number" id="myTargetElement3">@if(isset($total_earn_point)){{$total_earn_point}}@else 0 @endif</div>
                        </div>
                        <i class="livicon pull-right" data-name="box-add" data-l="true" data-c="#fff"
                        data-hc="#fff" data-s="70"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-4 margin_10 animated fadeInRightBig">
        <div class="palegreycolorbg no-radius">
            <div class="panel-body squarebox square_boxs">
                <div class="col-xs-12 pull-left nopadmar">
                    <div class="row">
                        <div class="square_box col-xs-7 pull-left">
                            <span>Total Redemption Points</span>

                            <div class="number" id="myTargetElement4">@if(isset($total_redeem_point_earned)){{$total_redeem_point_earned}}@else 0 @endif</div>
                        </div>
                        <i class="livicon pull-right" data-name="credit-card-out" data-l="true" data-c="#fff"
                        data-hc="#fff" data-s="70"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-4 margin_10 animated fadeInUpBig">
        <div class="palepurplecolorbg no-radius">
            <div class="panel-body squarebox square_boxs">
                <div class="col-xs-12 pull-left nopadmar">
                    <div class="row">
                        <div class="square_box col-xs-7 pull-left">
                            <span>Total Balance Points</span>
                            <div class="number" id="myTargetElement2">@if(isset($balanceTotal)){{$balanceTotal}}@else 0 @endif</div>
                        </div>
                        <i class="livicon pull-right" data-name="briefcase" data-l="true" data-c="#fff"
                        data-hc="#fff" data-s="70"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="row ">
    <div class="col-md-12 col-sm-12">
        <div class="panel panel-border">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="livicon" data-name="dashboard" data-size="20" data-loop="true" data-c="#F89A14"
                    data-hc="#F89A14"></i>
                    Monthly User Registration Graph
                    <small>-All Users</small>
                </h3>
            </div>
            <div class="panel-body">
                      <!--
                        <div id="realtimechart" style="height:350px;"></div>
                    -->
                    <div id="chartContainer" style="height: 350px;></div>

                </div>
            </div>
        </div>
       
        
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="panel panel-border">
                    <div class="panel-heading border-light">
                        <h4 class="panel-title">
                            <i class="livicon" data-name="calendar" data-size="16" data-loop="true" data-c="#333"
                            data-hc="#333"></i> Activity Calendar</h4>
                            <span class="pull-right">
                                <i class="glyphicon glyphicon-chevron-up showhide clickable"></i>
                                <i class="glyphicon glyphicon-remove removepanel clickable"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id='external-events'></div>
                            <div id="calendar"></div>
                            <div class="box-footer pad-5">
                                <a href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#myModal">Add
                                Activity</a>
                            </div>
                            <!-- Modal -->

                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                            aria-hidden="true">
                            <form method="POST" id="activity_cal_form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"
                                            aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">

                                                Create Event
                                            </h4>
                                        </div>
                                        <div class="modal-body">

                                          <div class="row">  

                                            <div class="col-lg-12">
                                              <div class="form-group">


                                               <input type="text" id="new-event" name="event" class="form-control event" placeholder="Event" required>
                                               <label class="multi-select">
                                                <select class="form-control " id="color-chooser" name="event_type" required>
                                                   <option value="" >--Type--</option>
                                                   <option value="primary" class="palette-primary" >Primary</option>
                                                   <option value="success" class="palette-success">Success</option>
                                                   <option value="info"    class="palette-info">Info</option>
                                                   <option value="warning" class="palette-warning">Warning</option>
                                                   <option value="danger" class="palette-danger">Danger</option>
                                                   <option value="default" class="palette-default">Default</option>
                                               </select>
                                               <i class="fa fa-caret-down m-arrow"></i>
                                           </label>

                                           <div class="clr"></div>



                                       </div>


                                   </div>

                                   <div class="col-sm-12">
                                      <div class="form-group">

                                        <input id="event_date" name="event_date" placeholder="Please select event date" class="form-control datepicker" required>
                                        
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">
                                Close
                                
                            </button>
                            <button type="submit" class="btn btn-success pull-left">
                             Add
                         </button>

                     </div>
                 </div>
             </div>
         </form>
     </div>

 </div>
</div>
</div>

<!-- To do list -->
<div class="col-lg-6 col-md-6 col-sm-6">
    <div class="panel panel-primary todolist">
        <div class="panel-heading border-light">
            <h4 class="panel-title">
                <i class="livicon" data-name="medal" data-size="18" data-color="gray" data-hc="gray" data-l="true" id="livicon-26" style="width: 18px; height: 18px;"><svg height="18" version="1.1" width="18" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.5px; top: 5px;" id="canvas-for-livicon-26"><desc>Created with Raphaël 2.1.2</desc><defs/><path style="" fill="#fff" stroke="none" d="M24.502,9.39L23.695,9.391L24.347,8.916C25.019000000000002,8.434000000000001,25.167,7.493,24.677,6.824L23.501,5.208C23.017000000000003,4.538,22.081000000000003,4.386,21.407,4.871L20.755,5.347L21.000999999999998,4.578C21.261,3.7950000000000004,20.828999999999997,2.947,20.041999999999998,2.6930000000000005L18.139999999999997,2.0760000000000005C17.350999999999996,1.8190000000000004,16.506999999999998,2.2470000000000003,16.246999999999996,3.0350000000000006L16,3.8L15.752,3.0359999999999996C15.496,2.2479999999999993,14.65,1.8169999999999995,13.861,2.0729999999999995L11.959000000000001,2.6909999999999994C11.172,2.946999999999999,10.740000000000002,3.7939999999999996,10.996000000000002,4.5809999999999995L11.245000000000003,5.348L10.594000000000003,4.874C9.923,4.387,8.985,4.537,8.497,5.207L7.323,6.824C6.836,7.496,6.983,8.433,7.655,8.92L8.306000000000001,9.394H7.5C6.672,9.394,6.0009999999999994,10.065,6,10.894L6.001,12.894C6.001,13.722,6.672000000000001,14.394,7.501,14.395H8.307L7.656000000000001,14.869C6.984000000000001,15.355,6.837000000000001,16.293,7.322000000000001,16.964L8.499,18.581C8.986,19.251,9.923,19.401,10.596,18.913L11.247,18.44L10.998,19.206000000000003C10.741,19.993000000000002,11.173,20.841000000000005,11.959999999999999,21.097L13.861999999999998,21.714000000000002C14.650999999999998,21.970000000000002,15.497999999999998,21.540000000000003,15.754999999999999,20.753000000000004L16.005,19.985000000000003L16.253,20.753000000000004C16.507,21.540000000000003,17.355,21.970000000000002,18.142,21.714000000000002L20.044,21.097C20.833000000000002,20.841,21.265,19.997,21.011,19.206000000000003L20.762999999999998,18.440000000000005L21.412999999999997,18.913000000000004C22.080999999999996,19.401000000000003,23.019999999999996,19.251000000000005,23.506999999999998,18.581000000000003L24.679,16.964000000000002C25.166999999999998,16.292,25.023,15.357000000000003,24.351,14.865000000000002L23.698999999999998,14.391000000000002H24.505999999999997C25.33,14.395000000000001,26.003999999999998,13.722000000000001,26.001999999999995,12.894000000000002V10.895000000000001C26,10.066,25.332,9.394,24.502,9.39ZM16.002,18.914C12.133999999999999,18.914,9.000999999999998,15.782000000000002,9.000999999999998,11.914000000000001S12.133999999999997,4.9140000000000015,16.002,4.9140000000000015C19.869,4.9140000000000015,23.002,8.047,23.002,11.914000000000001S19.869,18.914,16.002,18.914ZM21.73,21.006L25.197,27.009999999999998L21.099,25.909999999999997L20.001,30.009999999999998L16.001,23.081999999999997L12,30.009999999999998L10.902,25.909999999999997L6.803999999999999,27.009999999999998L10.274,21C10.582999999999998,21.537,11.055,21.949,11.649,22.143L13.550999999999998,22.76C13.801999999999998,22.842000000000002,14.062999999999999,22.883000000000003,14.323999999999998,22.883000000000003C14.959999999999997,22.883000000000003,15.550999999999998,22.645000000000003,15.999999999999998,22.240000000000002C16.447,22.644000000000002,17.037,22.883000000000003,17.674,22.883000000000003C17.936,22.883000000000003,18.197,22.842000000000002,18.447,22.76L20.349,22.143C20.963,21.945,21.434,21.523,21.73,21.006ZM16.002,14.648L12.751999999999999,16.357L13.371999999999998,12.738999999999999L10.743999999999998,10.177L14.376999999999999,9.648L16.002,6.356999999999999L17.625,9.648L21.259999999999998,10.177L18.630999999999997,12.738999999999999L19.249999999999996,16.357L16.002,14.648Z" transform="matrix(0.5625,0,0,0.5625,0,0)" stroke-width="0"/></svg></i>
                To Do List
            </h4>
        </div>
        <div class="panel-body nopadmar">
            <div class="panel-body">
             <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><div class="row list_of_items" style="overflow: hidden; width: auto; height: 250px;">
             </div><div class="slimScrollBar" style="background: rgb(169, 182, 188) none repeat scroll 0% 0%; width: 5px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 0px; z-index: 99; right: 1px; height: 286px;"></div><div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 0px; background: rgb(51, 51, 51) none repeat scroll 0% 0%; opacity: 0.2; z-index: 90; right: 1px;"></div></div>
             <div class="todolist_list adds">
                <form method="POST"  accept-charset="UTF-8" class="form" id="main_input_box">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    
                    <div class="form-group">
                        <label for="task_description">Task description: </label>
                        <input class="form-control" id="task_description" required name="task_description" type="text">
                    </div>
                    <div class="form-group">
                        <label for="task_deadline">Deadline: </label>
                        <input class="form-control datepicker" id="task_deadline" data-date-format="yyyy-mm-dd" required name="task_deadline" type="text">
                    </div>
                    <button type="submit" class="btn btn-primary add_button">
                        Add Task Todo
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
</div>

<!--
          
-->
</div>
<div class="clearfix"></div>

</section>
@endif
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script>
  var activity_route="{{URL::to('admin/add-activity')}}";
  var activity= "[{&quot;event&quot;:&quot;A meeting with UK client&quot;,&quot;created_at&quot;:&quot;2017-01-21 03:53:40&quot;,&quot;event_type&quot;:&quot;primary&quot;,&quot;event_date&quot;:&quot;01\/21\/2017&quot;},{&quot;event&quot;:&quot;this is testing&quot;,&quot;created_at&quot;:&quot;2017-01-23 06:39:15&quot;,&quot;event_type&quot;:&quot;success&quot;,&quot;event_date&quot;:&quot;01\/03\/2017&quot;}]";
  
  
  activity = JSON.parse(activity.replace(/&quot;/g,'"'));
  var jsonObj=[];
  var loadCalendarDataRoute="{{route('load.calendar.data')}}";


</script>
<script type="text/javascript" src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>

<script src="{{ asset('assets/admin/vendors/flotchart/js/jquery.flot.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/vendors/flotchart/js/jquery.flot.resize.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/admin/vendors/countUp.js/js/countUp.js') }}"></script>
<script src="{{ asset('assets/vendors/fullcalendar/js/fullcalendar.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/vendors/todolist.js') }}"></script>  

<script src="{{ asset('assets/admin/js/pages/dashboard.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/admin.js') }}"></script>
<script src="{{ asset('assets/vendors/canvasjs.min.js') }}"></script> 
<script>
    $(".datepicker").datepicker({});

</script>
<script>



 $.getJSON(loadCalendarDataRoute, function(response){
    var jsonObj=[];
    $.each(response.content, function( key, value ) {

      var date = new Date(value.event_date);
      
      var d = date.getDate();
      m = date.getMonth();
      y = date.getFullYear();

      item = {}
      item ["title"] = value.event;
      item ["start"] =  new Date(y, m, d);
      
      if(value.event_type=="primary")
         item ["backgroundColor"] = ('#0275d8')
     else if(value.event_type=="success")
         item ["backgroundColor"] = ('#5cb85c')
     else if(value.event_type=="info")
         item ["backgroundColor"] = ('#5bc0de')
     else if(value.event_type=="warning")
         item ["backgroundColor"] = ('#f0ad4e')
     else if(value.event_type=="danger")
         item ["backgroundColor"] = ('#d9534f')
     else if(value.event_type=="default")
         item ["backgroundColor"] = ('#a9b6bc')

     jsonObj.push(item);
 });
    
    $('#calendar').fullCalendar('destroy');
    
    $('#calendar').fullCalendar({
     header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month'
    },
    
    events: jsonObj,
    
    eventClick: function(event) {
     if (event.title) {
         $('#event_value').html(event.title);
         $('#event').modal();


     }
 },

 editable: true,
 droppable: false,
 height:450
});
    
    
    
    

});          


</script>

<script>
	
	
	
    window.onload = function () {

      var data= "{{ URL::to('cpmin/user-chart')}}";
      $.ajax({
       url: data,

       type: "GET",					
       dataType: 'json',
       success: function(response){

var dps = []; // dataPoints

var todaydate = new Date();

var locale = "en-us";
var month = todaydate.toLocaleString(locale, {month: "long"});


var chart = new CanvasJS.Chart("chartContainer", {
	title :{
		text: "User Graph"
	},
	axisX:{
		interval: 1,
		title: month,

	},
	axisY: {

		title: "No of Users",
		interval: 1,
        includeZero: false


    },
    toolTip:{   
     content: "{name} {label}: {y}"      
 },
 data: [{
  type: "column",
  name: month,
  namy: '1',
  dataPoints:dps,

  showInLegend: true,
}]






});

var dataLength = 0; // number of dataPoints visible at any point

$.each(response.detail, function(key, val) { 
	
 var yy = parseFloat(val.count);
 var dd = parseFloat(val.add_date);

 dps.push({
    y: yy,
    label: dd,


});

});


chart.render();
}, 



});
      


  }
</script>

@stop

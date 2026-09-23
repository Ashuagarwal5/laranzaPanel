@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
 Chat Manager::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<style>
.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%; padding:
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 450px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
 }
 .received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

 .sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 406px;
  overflow-y: auto;
}
</style>

@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Enquiries   Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Enquiries  Manager</li>
            <li class="active">Enquiries List</li>
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
                       Enquiries  List
                    </h4>
                    
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>Name</th>                                                   
                            <th>Enquiry Date</th>
                            <th>Actions</th>
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
                ajax: '{!! route('admin.chat.data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'full_name', name: 'full_name' },                             
                    { data: 'enquiry_date', name: 'enquiry_date' },
                    { data: 'actions', name: 'actions', orderable: true, searchable: true }
                ]
            });
            table.on( 'draw', function () {
                $('.livicon').each(function(){
                    $(this).updateLivicon();
                });
            } );
        });

		function changedateformate(data,type,row,meta)
		{
			return data;
		}
    </script>
    
    <script>
		
		


		
		//~ $('html, body').animate({
					//~ scrollTop: $('.msg_history').offset().top-290
					//~ },800);
		
		
		
	$('#backbtn').click(function(){
	var url=$(this).attr('data-url');

	window.location.href=url;

});
$(document).on("submit", ".chat_form", function(event) {
      var posturl = $(this).attr('action');
     
      var formid = '#' + $(this).attr('id');
      
      $(this).ajaxSubmit({
          url: posturl,
          dataType: 'json',
          type: "POST",
          beforeSend: function() {
              $(".submit").attr("disabled", 'disabled');
              $('.formmessage').hide();
              $('#wait-div').show();
          },
          success: function(response) {
              $(".submit").removeAttr("disabled", 'disabled');
             toastr[response.msgType](response.msg, response.msgHead); 
             
                  $(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
                  if (response.status == "success") {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
                    
                        $('.msg_history').append(response.html_append);
                       $('.message_input').val('');
                   
                 
                 
                  } else {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
						 
						 
						 
                  
                 
              }
              if (response.slideToTop){
                  //$('html, body').animate({scrollTop: 0 }, 'slow');
                  $('html, body').animate({
					scrollTop: $(formid).offset().top-290
					},800);
			  }
              if (response.url)
                  window.location.href = response.url;
              if (response.selfReload)
                  window.location.reload();
              if (response.status == 'success') {
				  //$("#chatwindow").load(location.href + " #chatwindow");
				  //$("#myDiv").load(document.URL + "#myDiv");
                  //$(formid)[0].reset();
              }
              if (response.redirect == 'yes') {
                  window.location.href = response.redirectUrl;
              }
          },
          error: function(response) {
              var data = response.responseJSON;
              $(".submit").removeAttr("disabled", 'disabled');
             
          }
      });
      return false;
  });


</script>
<script>
$(document).ready(function(){
	
	$(window).load(function() {
  $("html, body").animate({ scrollTop: $(document).height() }, 1000);
});


	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>



@stop

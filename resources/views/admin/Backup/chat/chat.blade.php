@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
 Contact Us Enquiry Manager::CRM
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
        <h1>Contact Us Enquiry Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Contact Us Enquiry Manager</li>
            <li class="active">Contact Us Enquiry List</li>
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
                       Contact Us Enquiry List
                    </h4>
                    
                </div>
                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered " id="table1">
                        <thead>
                        <tr class="filters">
                            <th>ID</th>
                            <th>User Name</th>                                                   
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
                    { data: 'send_by', name: 'send_by' },                             
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
	$('#backbtn').click(function(){
	var url=$(this).attr('data-url');

	window.location.href=url;

});
$(document).on("submit", ".ajax_form", function(event) {
      var posturl = $(this).attr('action');
      var callbackFunction = $(this).attr('data-callback_function');
      if (callbackFunction) {
          if (callbackForm() == false) {
              return false;
          }
      }
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
             $(formid).find('.form-group').removeClass('has-error');
             toastr[response.msgType](response.msg, response.msgHead); 
             
                  $(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
                  if (response.status == "success") {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
                  } else {
                      $(formid).find('.alert').fadeIn();
                      $(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
						 $.each(response.errorArray, function( key, value ) {
						  console.log(key + " => " + value);
						  var msg = '<label class="error formmessage" for="'+key+'"  style="color:ef6f6c">'+value+'</label>';
						   
						   $(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').closest('.form-group').addClass('has-error');
						   
						   $(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);
						  });
                  
                  
                 
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
	toastr[response.msgType](response.msg, response.msgHead); 
	});
</script>



@stop

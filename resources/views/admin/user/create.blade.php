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
</style>
@stop
@section('content')
<section class="content-header">
	<h1>
	{{ $manager_name }} Manager    </h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>{{ $manager_name }} Manager</li>
		<li class="active">
			@if(isset($data))
			Edit
			@else
			Create
			@endif
		</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">
						<i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff"
						data-hc="white"></i>
						@if(isset($data))
						Edit
						@else
						Create
						@endif {{ $manager_name }}
					</h3>
					<div class="pull-right">
						<a href="{{ $navi['back_url'] }}" class="btn btn-sm btn-danger">
							<i class="fa fa-arrow-left" aria-hidden="true"></i>
							<span style="margin-left:8px">Back</span></a>
						</div>
					</div>
					<div class="panel-body">
						<div class="panel panel-default">
						
						<div class="panel-heading panel-heading-nav">
							<ul class="nav nav-tabs">
							  <li role="presentation" class="active">
								<a href="#one" aria-controls="one" role="tab" data-toggle="tab">Basic Details</a>
							  </li>
							  @if(isset($data))
							  <li role="presentation">
								<a href="#two" aria-controls="two" role="tab" data-toggle="tab">Documents Details</a>
							  </li>
							  <li role="presentation">
								<a href="#three" aria-controls="three" role="tab" data-toggle="tab">Bank Details</a>
							  </li>
							  @endif
							</ul>
						  </div>

						  <div class="panel-body">
							<div class="tab-content">
							  <div role="tabpanel" class="tab-pane fade in active" id="one">
								@include('admin.user.basic')
							  </div>
							  <div role="tabpanel" class="tab-pane fade" id="two">
								@include('admin.user.document')
								<br>
								<hr>
								@include('admin.user.document_table')
							  </div>
							  <div role="tabpanel" class="tab-pane fade" id="three">
								@include('admin.user.bank_details')
								<br>
								<hr>
								@include('admin.user.bank_details_table')
							  </div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="ModalRestore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header label-success">
					<button type="button"  class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title addtax"  id="myModalLabel" style="color:#fff;">Restore Record
					</h4>
				</div>
				<div class="modal-body"> User is already exits in trashed
					<div class="exitname">
					</div>
					<div class="exitemail">
					</div>
					<div class="exitmob">
					</div>
				</div>
				<div class="modal-body"> Are you sure to restore this Record? </div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
					<a class="btn btn-danger restorepage" id="" type="button" href="">Restore</a>
				</div>
			</div>
		</div>
	</div>
	<div id="modal-regular" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
		<div class="modal-dialog">
			<div class="modal-content">
				<div style="text-align:center;"> <img style="float:center;" src="" alt="" class="loading"></div>
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

		set_dealer($('select[name="user_type"]').val())
		$('select[name="user_type"]').on('change', function(event) {
			event.preventDefault();
			// alert($(this).val())
			set_dealer($(this).val());
		});
	} );
	function set_dealer(d_id)
	{

		if(d_id=='Dealer')
		{
			$('.dealer_id').attr("disabled","disabled");
		}else{
			$('.dealer_id').removeAttr("disabled");
		}

	}
</script>
<script>
	$(function(){
		$(document).ready(function() {

			$('select[name="state_id"]').on('change', function() {
				var statedistrictId = $(this).val();
				if(statedistrictId) {
          //alert(statedistrictId);
          $.ajax({
          	url: 'agent/district/ajax/'+encodeURI(statedistrictId),
          	type: "GET",
          	dataType: "json",
          	success:function(respo) {
                  // console.log('respo data'+JSON.stringify(respo))
               // var obj = JSON.parse(respo);
               $('select[name="district_id"]').empty();
               $('select[name="district_id"]').append('<option value="">Select District</option>');
               $.each(respo.district, function(key, value) {
               	$('select[name="district_id"]').append('<option value="'+ value.id +'">'+ value.name +'</option>');
               });
             }
           });
        }else{
        	$('select[name="district_id"]').empty();
        }
      });
		});

	});
	$(document).on('change','#payment_type',function(e) {
		e.preventDefault();
		var value = $(this).val();
		if (value == "Bank Details") {
			$("#bank_complete_details").css("display", "block");
			$("#upi_account").css("display", "none");
			$("#gpay_account").css("display", "none");
			// $('#bank_complete_details').css("display":"block");
		}
		else if(value == "UPI"){
			$("#upi_account").css("display", "block");
			$("#bank_complete_details").css("display", "none");
			$("#gpay_account").css("display", "none");
		}
		else if(value == "Gpay"){
			$("#gpay_account").css("display", "block");
			$("#upi_account").css("display", "none");
			$("#bank_complete_details").css("display", "none");

		}
		else{
			$("#upi_account").css("display", "none");
			$("#gpay_account").css("display", "none");
			$("#bank_complete_details").css("display", "none");
		}
	})
</script>

@stop
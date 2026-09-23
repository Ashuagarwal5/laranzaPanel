@extends('admin/layouts/default')
@section('title')
QR Code Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}" />
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/toastr.css') }}">
<style>
	.tab {
		overflow: hidden;
		border: 1px solid #ccc;
		background-color: #f1f1f1;
	}

	/* Style the buttons inside the tab */
	.tab button {
		background-color: inherit;
		float: left;
		border: none;
		outline: none;
		cursor: pointer;
		padding: 14px 16px;
		transition: 0.3s;
		font-size: 17px;
	}

	/* Change background color of buttons on hover */
	.tab button:hover {
		background-color: #ddd;
	}

	/* Create an active/current tablink class */
	.tab button.active {
		background-color: #ccc;
	}

	/* Style the tab content */
	.tabcontent {
		display: none;
		padding: 6px 12px;
		border: 1px solid #ccc;
		border-top: none;
	}
</style>
@stop
@section('content')
<section class="content-header">
	<h1>
	Bulk QR Code Manager    </h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('admin.dashboard') }}">
				<i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
				Dashboard
			</a>
		</li>
		<li>Bulk QR Code Manager</li>
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
						@endif Bulk QR Code
					</h3>
					<div class="pull-right">

						<a href="{{ route('admin.products.history_bulk_qr') }}" class="btn btn-sm btn-danger"><span class="btn-label">
							<i class="glyphicon glyphicon-chevron-left"></i>
						</span><span style="margin-left:8px">Back</span></a>


					</div> 
				</div>
				<div class="panel-body" id="panel_body">
					@if(isset($_GET['tab']))
					@php($tab = $_GET['tab'])
					@endif
					<div class="tab">
						<button class="tablinks @if(!isset($tab)) active @endif" onclick="openCity(event, 'Basic')">Basic Information</button>
					</div>

					<div id="Basic" class="tabcontent" @if(!isset($tab)) style="display:block;" @endif>
						<form method="post" id="productForm" class="ajaxformclass" action="@if(isset($data)){{route('admin.products.bulk_store',['id'=>$data->id])}}@else{{route('admin.products.bulk_store')}}@endif" enctype="multipart/form-data">

							<input type="hidden" name="_token" value="{{ csrf_token() }}" />

						<div class="form-group has-success">
							<label for="unit">Product Category</label>
							<div class="input-group">
								<input type="hidden"value="@if(isset($data)) {{$data->id}} @endif">
								
							<select name="category_id" id="category_id"  class="form-control" @if ($edit_data == 5)
							disabled @endif>
								<option value="">Select Product Category</option>
								@foreach ($category as $item)
										<option value="{{$item->id}}"@if(isset($data)){{$item->id == $data->category_id ? 'selected':''}}@endif>{{$item->category_name}} </option>
										@endforeach
									</select>
								{{-- <input type="text" class="form-control" name="product_group_code" value="@if(isset($data->product_group_code)){{$data->product_group_code}}@else{{old('product_group_code')}}@endif" id="product_group_code" placeholder="Product Group Code"> --}}
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>

						</div>

						<div class="form-group has-success">
							<label for="unit">QR Code Size*</label>
							<div class="input-group">
								<select name="qr_size" id="qr_size" class="form-control" @if ($edit_data == 5)
								disabled @endif>
									<option value="">Select QR Code Size</option>
									@for ($i = 300; $i < 900; $i+=100)
									<option value="{{$i}}"@if(isset($data)){{$i == $data->qr_size ? 'selected':''}}@endif>{{$i}}</option>
									@endfor
								</select>
								{{-- <input type="text" class="form-control" name="qr_size" value="@if(isset($data->qr_size)){{$data->qr_size}}@else{{old('qr_size')}}@endif" id="size" placeholder="QR Code Size"> --}}
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span> 
								
							</div>
						</div>
						

						
						
						
						<div class="form-group has-success">
							<label for="unit">Reward Points*</label>
							<div class="input-group">
								<input  type="text" class="form-control" name="reward_points" value="@if(isset($data->reward_points)){{$data->reward_points}}@else{{old('reward_points')}}@endif" id="unit" placeholder="Reward Points">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>

						<div class="form-group has-success">
							<label for="unit">Total No. of Qr Code*</label>
							<div class="input-group">
								<input @if ($edit_data == 5) readonly @endif type="text" class="form-control" name="bulk_qr_code" value="@if(isset($data->bulk_qr_code)){{$data->bulk_qr_code}}@else{{old('bulk_qr_code')}}@endif" id="bulk_qr_code" placeholder="Reward Points">
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>
                        

						<div class="form-group has-success">
							<label for="unit">Remark</label>
							<div class="input-group">
								<textarea name="remark" id="remark" cols="30" rows="6" class="form-control">@if(isset($data->remark)){{$data->remark}}@else{{old('remark')}}@endif</textarea>
								<span class="input-group-addon success">
									<span class="glyphicon glyphicon-ok"></span>
								</span>  
							</div>
						</div>
						
						
						<div class="col-md-12 mar-10">
							<div class="col-xs-4 col-md-4"></div>

							<div class="col-xs-4 col-md-2">
								<input type="submit" class="btn btn-primary btn-block btn-md submit" value="Save">			
							</div>
							<div class="col-xs-4 col-md-2">
								<a class="btn btn-warning btn-block btn-md" href="{{ route('admin.products.history_bulk_qr') }}">Cancel</a>
							</div>

							

						</div>


					</form>
				</div>


			</div>
			<div id="show_body" style="display:none; padding-top: 15px;"></div>
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
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap-dialog.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/product.js') }}"></script>


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

		let val = $(".copy_code").val();
		copyToClipboard(val)
			.then(() => {
				toastr.success('Code copy successfully ');
			})
			.catch(() => console.log('error'));
	});
</script>
<script>
	var edit = '{{ (isset($data) ? "Yes": "No")   }}';
	var load_count = 1
	// alert(edit);

	if(edit == 'Yes'){
		changeSeller();
	}
	
	$(document).on('change','.seller_select',function(){
		changeSeller();
		
	});
	
	$(document).on('change','#category',function(){
		
		$('#sub_category').empty();
		var cat=$(this).val();
		var Url='{{route("admin.products.mainsubCatData")}}'
		//alert(Url);
		$.ajax({

			url: Url,
			data:{'cat':cat},
			success:function(response){
				//alert('all done');
				
				$('#main_sub_category').empty();
				$('#main_sub_category').append('<option value="">--Select Sub Category--</option>');
				for (var i = 0; i < response.subCat.length; i++) {
					//$('#sub_category').append('<option id=' + data[i].sysid + ' value=' + data[i].name + '>' + data[i].name + '</option>');
					$('#main_sub_category').append('<option value=' + response.subCat[i].id + '>' + response.subCat[i].category_name + '</option>');
				}
			}
		});
	});
	
	$(document).on('change','#main_sub_category',function(){
		
		$('#sub_category').empty();
		var cat=$(this).val();
		var Url='{{route("admin.products.subCatData")}}'
		//alert(Url);
		$.ajax({

			url: Url,
			data:{'sub_cat':cat},
			success:function(response){				
				$('#sub_category').empty();
				$('#sub_category').append('<option value="">--Select Sub Sub Category--</option>');
				for (var i = 0; i < response.subpartCat.length; i++) {
					$('#sub_category').append('<option value=' + response.subpartCat[i].id + '>' + response.subpartCat[i].category_name + '</option>');
				}
			}
		});
	});
	
	function changeSeller(){
		
		var seller_id=$('.seller_select').val();

					//$('select[name^="category"] option:selected').attr("selected",null);
					
					
					if(seller_id){

						$.ajax({

							url: "{{ route('get.seller.cate') }}",
							data:{'seller_id':seller_id},
							success:function(response){

								$('.category_div').show();  
				// alert(response.category_name);

				$('.category_name_input').val(response.category_name);
				$('.category_id').val(response.cate_id);

				getSubCat(response.cate_id);

				 //~ $('#category').prop('disabled', 'disabled');
				 


				//~ $('select[name^="category"] option[value=4]').attr("selected","selected");
				
				
				
				//alert(response.cate_id);
				
				
			}
		});
					}else{

						$('.category_div').hide();  
					}


				}
				function getSubCat(cat){

					load_count = load_count+ 1


					var cat=cat;
					var Url='{{route("admin.products.mainsubCatData")}}'
		//alert(Url);
		$.ajax({

			url: Url,
			data:{'cat':cat},
			success:function(response){
				//alert('all done');
				
				
				if(load_count > 2 || edit == 'No'){
					$('#sub_category').empty();
					
					
					$('#main_sub_category').empty();
					$('#main_sub_category').append('<option value="">--Select Sub Category--</option>');
					for (var i = 0; i < response.subCat.length; i++) {
					//$('#sub_category').append('<option id=' + data[i].sysid + ' value=' + data[i].name + '>' + data[i].name + '</option>');
					$('#main_sub_category').append('<option value=' + response.subCat[i].id + '>' + response.subCat[i].category_name + '</option>');
				}
				
			}




		}
	});
		
	}
	$(document).on('change','#branch_stores',function(){
	//console.log('dsds');
	var branchType = $(this).val();
	if(branchType=="specific")
	{
		$('#branches').show();
	}else{
		$('#branches').hide();
		//$('#branches input[name="branches[]"]').removeAttr('checked');
	}
	
});
</script>
<script>
	$('#sale_price').keyup(function(){
		
		var sale_price = parseInt($(this).val(),10);
		var offer_price = parseInt($('#offer_price').val(),10);

		if(offer_price)
		{
			if(offer_price > sale_price)
			{
				$('#offer_price_error').empty();
				$("#offer_price_error").css("color", "red");
				$("#offer_price").css("border", "1px red solid");
				$('#offer_price_error').append("Offer price must be small than sale price.");
			}
			else
			{
				$('#offer_price_error').empty();
				$("#offer_price").css("border", "");

			}
		}
		
	});
	$('#offer_price').keyup(function(){
		
		var sale_price = parseInt($('#sale_price').val(),10);
		var offer_price = parseInt($(this).val(),10);

		if(offer_price)
		{
			if(offer_price > sale_price)
			{
				$('#offer_price_error').empty();
				$("#offer_price_error").css("color", "red");
				$("#offer_price").css("border", "1px red solid");
				$('#offer_price_error').append("Offer price must be small than sale price.");
			}
			else
			{
				$('#offer_price_error').empty();
				$("#offer_price").css("border", "");

			}
		}
		
	});

</script>
<script>
	function openCity(evt, cityName) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(cityName).style.display = "block";
		evt.currentTarget.className += " active";
	}
</script>
<script>
	$(function() {
		var table = $('#attrTable').DataTable({

		});

	});

</script>

@stop

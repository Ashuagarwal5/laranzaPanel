@extends('vendor/header')

{{-- Page title --}}
@section('title')
Product Create
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

	<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
	<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/app1.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/default/css/autocomplete.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/default/tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
     <link href="{{ asset('assets/css/jquery.tree.min.css') }}" rel="stylesheet" type="text/css"/>
<!--
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme-jquery-ui.css') }}">
-->



    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css"/>
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

.box-error {
    border: 1px solid red !important;
}

.box-error::placeholder {
    color: #FF0000 !important;
}
</style>

   

@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	<div class="container-fluid">
      <div class="row">

         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-9 col-md-offset-3 main">

<div class="panel">


<section class="content-header">
    <h1>
Products Manager    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
               Dashboard
            </a>
        </li>
        <li>Products Manager</li>
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
            <div class="panel panel-primary m15">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="list" data-size="20" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                           @if(isset($data))
						  Edit 
						  @else
							Create
							  @endif Products Item
                        </h3>
                               <div class="pull-right">

								<a href="{{ route('admin.products') }}" class="btn btn-sm btn-danger" style="font-family: "Raleway",sans-serif !important;"><span class="btn-label">
                                     <i class="glyphicon glyphicon-chevron-left"></i>
                                </span><span style="margin-left:8px">Back</span></a>


                  </div> 
                    </div>
                    <div class="panel-body">
						@if(isset($_GET['tab']))
						@php($tab = $_GET['tab'])
						@endif
						<div class="tab">
						  <button class="tablinks @if(!isset($tab)) active @endif" onclick="openCity(event, 'Basic')">Basic Information</button>
						  @if(isset($data) && $data->product_type=='Composite')
						  <button class="tablinks" onclick="openCity(event, 'Images')">Product Images</button>
						  @endif
						  	
						  @if(isset($data->product_type) && $data->product_type=='Composite')<button class="tablinks @if(isset($tab) && $tab=='attrTable') active @endif" onclick="openCity(event, 'Price')">Price Variation</button>@endif

						</div>
						
						<div id="Basic" class="tabcontent" @if(!isset($tab)) style="display:block;" @endif>
							
                        <form method="post" id="productForm" class="ajaxformclass" action="@if(isset($data)){{route('seller.product.edit.store',['id'=>$data->id])}}@else{{route('seller.product.general')}}@endif" enctype="multipart/form-data">
								
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							
							<div class="form-group has-success">
                                <label for="validate-text">Product Title </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="product_title" id="validate-text"
                                         value="@if(isset($data->product_title)){{$data->product_title}}@endif" placeholder="Product Title ">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>  
                                </div>
								
                            </div>
							
							 <div class="form-group has-success">
                                <label for="validate-text"> Store Category </label>
                                <div class="input-group">
									<select class="form-control" readonly disabled>
										<option value="">--Select Category--</option>
										@foreach(App\Category::getAllMainCat() as $key=>$value)
										<option @if(isset($sellerdetail->category) && $sellerdetail->category==$value->id) selected="" @endif value="{{$value->id}}">{{$value->category_name}}</option>
										@endforeach
									</select>
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								 
                            </div>
                            
                             <div class="form-group has-success">
                                <label for="validate-text"> Sub Category </label>
                                <div class="input-group">
									<select id="main_sub_category" name="sub_category" class="form-control">
										<option value="">--Select Sub Category--</option>
										@if(isset($sellerdetail->category))
										@foreach(App\Category::getAllSubCat($sellerdetail->category) as $key=>$value)
										<option @if(isset($data->sub_category) && $data->sub_category==$value->id) selected="" @endif value="{{$value->id}}">{{$value->category_name}}</option>
										@endforeach
										@endif
									</select>
									<span class="input-group-addon success">
										<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								
                            </div>
                            
                             <div class="form-group has-success">
                                <label for="validate-text"> Sub Sub Category </label>
                                <div class="input-group">
									<select id="sub_category" name="sub_part_category" class="form-control">
										<option value="">--Select Sub Category--</option>
										@if(isset($data->sub_category))
										@foreach(App\Category::getAllSubCat($data->sub_category) as $key=>$value)
										<option @if($data->sub_part_category==$value->id) selected="" @endif value="{{$value->id}}">{{$value->category_name}}</option>
										@endforeach
										@endif
									</select>
									<span class="input-group-addon success">
										<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
								
                            </div>
							
                            
                            
                             <div class="form-group has-success">
                                <label for="validate-text">Product Image </label>
                                @if(isset($data->product_image))
                                <img src="{{ URL::to(App\Helpers\Thumbnail::image("/products/$data->product_image","200","80","ff=ffffff")) }}">
                                @endif
                                <div class="input-group">
                                    <input type="file" class="form-control" name="product_image">
									<span class="input-group-addon success">
										<span class="glyphicon glyphicon-ok"></span>
									</span>  
                                </div>
                            </div>
						  
						  <div class="form-group has-success">
							   <label for="validate-text"> Product Description </label>
							
							<div class="input-group">
								<textarea class="form-control" name="product_description" >@if(isset($data->product_description)){{$data->product_description}}@else{{old('product_description')}}@endif</textarea>
							<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
							
							</div>
							
						  </div>
						  
						  <div class="form-group has-success">
                                <label for="validate-text"> Stock Status </label>
                                <div class="input-group">
									<select id="stock_status" name="stock_status" class="form-control">
										<option value="">--Select Stock Status--</option>
										<option @if(isset($data->stock_status) && $data->stock_status=="instock") selected="" @endif value="instock">In stock</option>
										<option @if(isset($data->stock_status) && $data->stock_status=="outofstock") selected="" @endif value="outofstock">Out of Stock</option>
									</select>
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
                            </div>
                            
<!--
						  <div class="form-group has-success">
                                <label for="validate-text"> Product Type </label>
                                <div class="input-group">
									<select id="product_type" name="product_type" class="form-control">
										<option value="">--Select Product Type--</option>
										<option @if(isset($data->product_type) && $data->product_type=="Simple") selected="" @endif value="Simple">Simple</option>
										<option @if(isset($data->product_type) && $data->product_type=="Composite") selected="" @endif value="Composite">Composite</option>
									</select>
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
                            </div>
-->

							<div class="form-group has-success">
                                <label for="unit">Product Unit </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="unit" value="@if(isset($data->unit)){{$data->unit}}@else{{old('unit')}}@endif" id="unit"
                                           placeholder="Product Unit">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>  
                                </div>
                            </div>
	
						
						<div class="form-group has-success">
                                <label for="validate-text">Maximum Retail Price </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="sale_price" value="@if(isset($data->sale_price)){{$data->sale_price}}@else{{old('sale_price')}}@endif" id="sale_price"
                                           placeholder="Maximum Retail Price">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>  
                                </div>
								
                            </div>
                            
						<div class="form-group has-success">
                                <label for="validate-text">Offer Price </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="offer_price" value="@if(isset($data->offer_price)){{$data->offer_price}}@else{{old('offer_price')}}@endif" id="offer_price"
                                           placeholder="Offer Price">
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>  
                                </div>
                                <span id="offer_price_error"></span>
								
                            </div>
<!--
                       
                       <div class="form-group has-success">
                                <label for="validate-text"> Available On </label>
                                <div class="input-group">
									<select id="branch_stores" name="branch_stores" class="form-control">
										<option value="">--Select Product Type--</option>
										<option @if(isset($data->branch_stores) && $data->branch_stores=="all") selected="" @endif value="all">All Branches</option>
										<option @if(isset($data->branch_stores) && $data->branch_stores=="specific") selected="" @endif value="specific">Specific Branches</option>
									</select>
									<span class="input-group-addon success">
											<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
                            </div>
-->
                            
<!--
                         <div class="form-group has-success" id="branches" @if(isset($data->branch_stores) && $data->branch_stores!="specific" || empty($data))style="display:none;"@endif>
                                <label for="validate-text"> Branches </label>
                                <div class="input-group">
									<div class="row">
										@foreach(App\BranchStores::getAllBranches() as $key=>$value)
										<div class="col-sm-4"><input type="checkbox" name="branches[]" @if(isset($json_data) && in_array($value->id,$json_data)) checked="" @endif value="{{$value->id}}"> {{$value->branch_name}}</div>
										@endforeach
									</div>
								</div>
                            </div>
-->
                        
						
						<div class="col-md-12 mar-10">
						<div class=" col-md-4"></div>
						
						<div class="col-sm-6 col-md-2">
						<input type="submit" class="btn btn-primary btn-block btn-md sb-btn submit" value="Save">			
						</div>
						
						<div class="col-sm-6 col-md-2">
							<a class="btn btn-warning btn-block btn-md sb-btn" href="{{ route('productlist') }}">Cancel</a>
						</div>

								</div>


                        </form>
						</div>
						
						<div id="Images" class="tabcontent">
                        <form method="post" id="productimage" class="ajaxformclass" action="@if(isset($data)){{route('sellerpanel.products.store_images',$data->id)}}@endif" enctype="multipart/form-data">
								
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<div class="alert ajax_report alert-info alert-hide" role="alert" style="display:none">
							<span class="close" >&times;</span>
							<span class="ajax_message"><strong>Please wait! </strong>Your action is in proccess...</span>
							</div>
							
							<div class="form-group has-success">
							<label for="validate-text">Product Images </label>
							@if(isset($data))
							@if(count($pro_imgs)>0)
							@foreach($pro_imgs as $key=>$value)
							<div style="display:inline; position:relative;">
								<img src="{{ URL::to(App\Helpers\Thumbnail::image("/products/$value->product_id/$value->image","150","100","ff=ffffff")) }}">
								
								<span class="btn btn-danger pro_image_delete" onclick="deleteImage(this,{{$value->id}})" data-url="{{route('sellerpanel.products.delete_image',$value->id)}}">X</span>								
							</div>
							@endforeach
							@else
							No Images Found
							@endif
							@endif
							
							<div class="input-group">
								<input type="file" class="form-control" name="product_images[]" multiple>
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
							
							

							</div>

                        </form>
						</div>
						
						
						<div id="Price" class="tabcontent" @if(isset($tab) && $tab=='attrTable') style="display:block;" @endif>
						<form method="post" id="variation" class="ajaxformclass" action="@if(isset($data)){{route('seller.products.storePriceVar')}}@endif">
								
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<div class="alert ajax_report alert-info alert-hide" role="alert" style="display:none">
							<span class="close" >&times;</span>
							<span class="ajax_message"><strong>Please wait! </strong>Your action is in proccess...</span>
							</div>
							<input type="hidden" name="product_id" value="@if(isset($data)){{$data->id}}@endif" />
							
							 <div class="form-group has-success">
                                <label for="validate-text"> Attribute </label>
                                <div class="input-group">
									<select id="attr_id" name="attr_id" class="form-control">
										<option value="">--Select Attribute--</option>
										@foreach(App\Attributes::getAllAttr() as $key=>$value)
										<option value="{{$value->id}}">{{$value->attr_name}}</option>
										@endforeach
									</select>
									<span class="input-group-addon success">
										<span class="glyphicon glyphicon-ok"></span>
									</span>
                                </div>
                            </div>
                            
							<div class="form-group has-success col-sm-3">
                                <label for="validate-text">Attribute Value </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="attr_value" id="validate-text" placeholder="Attribute Value">
								</div>
							</div>
							<div class="form-group has-success col-sm-3">
                                <label for="validate-text">Extra Price </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="extra_price" id="validate-text" placeholder="Extra Price">
								</div>
							</div>
							<div class="form-group has-success col-sm-3">
                                <label for="validate-text">Display Order </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="display_order" id="validate-text" placeholder="Display Order">
								</div>
							</div>
							
							<div class="form-group has-success col-sm-3">
								<label>&nbsp;</label>
								<input type="submit" class="btn btn-primary btn-block btn-md submit" value="Save">
							</div>
							
                        </form>
                        <div class="panel-body">
						
						<table class="table table-bordered " id="attrTable">
							<thead>
							<tr>
								<th width="20px">S.No.</th>
								<th>Attr Name</th>
								<th>Attr Value</th>
								<th>Extra Price</th>
								<th>Display Order</th>
								<th>Actions</th>
							</tr>
							</thead>
							<tbody>
								@if(isset($data))
								@foreach(App\ProductAttributes::getAllVarByProId($data->id) as $key=>$value)
								<tr>
									<td>{{$key+1}}</td>
									<td>{{$value->attr_name}}</td>
									<td>{{$value->attr_value}}</td>
									<td>{{$value->extra_price}}</td>
									<td>{{$value->display_order}}</td>
									<td>
										<a data-toggle="modal" data-target="#modal-regular" href="{{URL::to('sellerpanel/product/'.$value->id.'/confirm-delete-Pri-var')}}" class="delval btn btn-xs btn-danger"  title="Delete Store Item">
										<i class="fa fa-trash"></i>
										Delete
										</a>
									</td>
								</tr>
								@endforeach
								@endif
							</tbody>
						</table>
                        
                        </div>
                        
						</div>
                    
                    
                    </div>
                </div>


        </div>
    </div>
    <!-- row-->
</section>






</div>










        </div>
      </div>
    </div>



    <!-- //Container End -->
@stop

{{-- footer scripts --}}
@section('footer_scripts')
    <!-- page level js starts-->
    
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

	<script type="text/javascript" src="{{ asset('assets/default/js/jquery-ui.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>

	<script  src="{{ asset('assets/js/bootstrap-dialog.min.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/default/tagsinput/bootstrap-tagsinput.min.js') }}"  type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular.min.js"></script>
    <script src="{{ asset('assets/default/tagsinput/bootstrap-tagsinput-angular.min.js') }}"></script>
	
	<script  src="{{ asset('assets/js/jquery.tree.min.js') }}"  type="text/javascript"></script>
	<script src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('assets/default/js/priceinventory.js') }}"></script>
	<script src="{{ asset('assets/admin/js/bootstrap-dialog.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/product.js') }}"></script>


<script>
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

    <!--page level js ends-->

@stop

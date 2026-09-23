@extends('admin/layouts/default')
@section('title')
Products Price Manager::CRM
@stop
{{-- page level styles --}}
@section('header_styles')
<link href="{{ asset('assets/css/pages/form2.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet" />
@stop
@section('content')
<section class="content-header">
  <h1>
  Products Price Manager    </h1>
  <ol class="breadcrumb">
    <li>
      <a href="{{ route('admin.dashboard') }}">
        <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
        Dashboard
      </a>
    </li>
    <li>Products Price Manager</li>
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
  
  @php($countries = App\Country::getAllCountry())

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
            Add
            @endif Product Price
          </h3>
          <div class="pull-right">

            <a href="{{ route('admin.product-price') }}" class="btn btn-sm btn-danger"><span class="btn-label">
             <i class="glyphicon glyphicon-chevron-left"></i>
           </span><span style="margin-left:8px">Back</span></a>


         </div>
        </div>
        <div class="panel-body">  
          <form method="post" id="page-form" >
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <div class="form-group has-success">
              <label for="validate-text">Product Name</label>
              <select name="product_id" class="form-control">
                <option value="">Select Product</option>
                @if(!empty($products))
                 @foreach($products as $product)\
                   @if(isset($data->product_id) && $data->product_id==$product->id)
                    @php($class='selected')
                   @else
                    @php($class='')
                   @endif
                   <option value="{{$product->id}}" {{$class}}>{{$product->product_title}}</option>
                 @endforeach
                 @else
                 <option value="">-No Product Found -</option>
                @endif
              </select>

              @if(!empty($errors->first('product_id')))
                <div class="text-danger">{{ $errors->first('product_id') }}</div>
              @endif
            </div>

            <div class="form-group has-success">
              <label for="validate-text">Country </label>
              <select name="country" class="form-control">
                <option value="">Select Country</option>
                @if(!empty($countries))
                 @foreach($countries as $country)\
                   @if(isset($data->country) && $data->country==$country->cntry_id)
                    @php($class='selected')
                   @else
                    @php($class='')
                   @endif
                   <option value="{{$country->cntry_id}}" {{$class}}>{{$country->cntry_name}}</option>
                 @endforeach
                 @else
                 <option value="">-No Country Found -</option>
                @endif
              </select>

              @if(!empty($errors->first('country')))
                <div class="text-danger">{{ $errors->first('country') }}</div>
              @endif
            </div>  



            <div class="form-group has-success">
                <label for="validate-text">Currency </label>
                <div class="input-group">
                  <input type="text" class="form-control" name="currency" value="@if(isset($data->currency)){{$data->currency}}@else{{old('currency')}}@endif" id="validate-text"
                    placeholder="Enter Country Currency ">
                  <span class="input-group-addon success">
                      <span class="glyphicon glyphicon-ok"></span>
                  </span>
                </div>
                <div class="has-error">
                  {!! $errors->first('currency', '<span class="help-block">:message</span>') !!}
              </div>
            </div>

            <div class="form-group has-success">
                <label for="validate-text">Price </label>
                <div class="input-group">
                  <input type="text" class="form-control" name="price" value="@if(isset($data->price)){{$data->price}}@else{{old('price')}}@endif" id="validate-text"
                    placeholder="Enter Product Price According to Country ">
                  <span class="input-group-addon success">
                      <span class="glyphicon glyphicon-ok"></span>
                  </span>
                </div>
                <div class="has-error">
                  {!! $errors->first('price', '<span class="help-block">:message</span>') !!}
              </div>
            </div>

            <div class="form-group has-success">
                <label for="validate-text">Alphacode </label>
                <div class="input-group">
                  <input type="text" class="form-control" name="alphacode" value="@if(isset($data->alphacode)){{$data->alphacode}}@else{{old('alphacode')}}@endif" id="validate-text"
                    placeholder="Enter Country Alphacode ">
                  <span class="input-group-addon success">
                      <span class="glyphicon glyphicon-ok"></span>
                  </span>
                </div>
                <div class="has-error">
                  {!! $errors->first('alphacode', '<span class="help-block">:message</span>') !!}
              </div>
            </div>

            <div class="form-group has-success">
                <label for="validate-text">Symbol </label>
                <div class="input-group">
                  <input type="text" class="form-control" name="symbol" value="@if(isset($data->symbol)){{$data->symbol}}@else{{old('symbol')}}@endif" id="validate-text"
                    placeholder="Enter Country Currency Symbol ">
                  <span class="input-group-addon success">
                      <span class="glyphicon glyphicon-ok"></span>
                  </span>
                </div>
                <div class="has-error">
                  {!! $errors->first('symbol', '<span class="help-block">:message</span>') !!}
              </div>
            </div>

            

            <div class="col-md-12 mar-10">
              <div class="col-xs-4 col-md-4"></div>
              <div class="col-xs-4 col-md-2">
               <button type="submit"  class="btn btn-primary btn-block btn-md submit">
                Save </button>
              </div>
              <div class="col-xs-4 col-md-2">
               <a class="btn btn-warning btn-block btn-md submit" href="{{ route('admin.product-price') }}">Cancel</a>
              </div>
            </div>
          </form>
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

@stop

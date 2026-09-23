@extends('vendor/header')

{{-- Page title --}}
@section('title')
Seller {{ $type }}
@parent
@stop

{{-- page level styles --}}
@section('header_styles')

	<link href="{{ asset('assets/vendors/bootstrap3-wysihtml5-bower/css/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" media="screen"/>
	<link href="{{ asset('assets/css/pages/editor.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/css/pages/tables.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/app1.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/css/toastr.css') }}" rel="stylesheet" type="text/css"/>
	<link href="{{ asset('assets/css/jquery.tree.min.css') }}" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/default/css/jquery-ui.css') }}">
	<link href="{{ asset('assets/default/css/autocomplete.css') }}" rel="stylesheet" type="text/css"/>


@stop
{{-- content --}}
@section('content')
	<!-- //Container Start -->

	<div class="container-fluid">
      <div class="row">

         @include('vendor/sidebar')
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

<div class="panel">

 <section class="content">
@include('notifications')
	        @if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
    <div class="row">
<!--
        <div class="col-lg-12">
            <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="wrench" data-size="16" data-loop="true" data-c="#fff"
                               data-hc="white"></i>
                       Notifications
                        </h3>
                    </div>

                    <div class="panel-body">
						<div>

                    </div>
                </div>


			</div>
		</div>
-->

	 <div class="col-lg-12" >
        <!-- page heading-->
        <h2 class="page-heading"> <span class="page-heading-title2">All {{ $type }}</span> </h2>
        <!-- Content page -->
        <div class="account clearfix">
          <div class="right-my-account-blocks-inner">
          <div class="notification">
          <ul>
            @foreach($notifications as $key => $value)
          <li><div class="noti">
          <div class="col-sm-2 text-center"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>
</div>
          <div class="col-sm-10">

<div role="alert" class="alert alert-info">   @if($type == 'Notifications' ){{  $value->notification_detail}}  @else  {{  $value->announcement_detail}}   @endif
<div class=""><a href="#" class="btn btn-link btn-sm"><u><strong>    {{ date('d-M-y',strtotime($value->created_at ))}}</strong> </u></a> <a href="#" class=" btn btn-link btn-sm"><u><strong></strong></u></a></div> </div>
          </div>
          </div></li>
            @endforeach


          </ul>
          <div class="clr"></div>
          </div>

            <div class="clearfix"></div>
            <div>


              <div class="clearfix"></div>
            </div>

            <div class="clearfix"></div>
          </div>


        </div>
        <div class="clr"></div>
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
    <script>
    var route="{{route('seller.myshop')}}";
    </script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
	<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/ckeditor.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/jquery.js') }}"  type="text/javascript" ></script>
	<script  src="{{ asset('assets/vendors/ckeditor/js/config.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/js/pages/editor.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/js/toastr.min.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/default/js/seller.js') }}"  type="text/javascript"></script>
	<script  src="{{ asset('assets/js/jquery.tree.min.js') }}"  type="text/javascript"></script>





    <!--page level js ends-->

@stop

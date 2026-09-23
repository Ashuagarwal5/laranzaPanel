@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
Newsletter View::CRM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
        <h1>Newsletter Manager</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="livicon" data-name="home" data-size="14" data-color="#000"></i>
                    Dashboard
                </a>
            </li>
            <li>Newsletter Manager</li>
            <li class="active">Newsletter</li>
        </ol>
    </section>
<section class="content paddingleft_right15">
        <div class="row">
	                     <div class="panel panel-primary">
                              <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"> <i class="livicon" data-name="users" data-size="10" data-loop="true" data-c="#fff" data-hc="white"></i>
                       <strong></strong>
                    </h4>
                    <div class="pull-right" style="margin-top: -24px">

               <a href="{{ route('newsletter') }}" class="btn btn-sm btn-danger"  style="margin-bottom:-42px;"><span class="btn-label">
                                                <i class="glyphicon glyphicon-chevron-left"></i>
                                            </span><span style="font-size:13px;margin-left:8px">Back</span></a>


                  </div>
                </div>
                       </br>

<div class="table-responsive">
<table id="users" class="table table-bordered table-striped">
<tbody>

			<tr>
			<td>Id</td>
			<td> {{ $detail->news_id }} </td>
			</tr>




			<tr>
			<td>Email</td>
			<td> {{ $detail->email}} </td>
			</tr>


			

			

			<tr>
			<td>Created Date </td>
			<td> {{ $detail->add_date }} </td>
			</tr>


</tbody>
</table>
</div>
</div>

</section>

@stop

{{-- page level scripts --}}
@section('footer_scripts')

@stop

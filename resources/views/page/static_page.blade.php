@extends('layouts/default')
@section('title') @parent @stop
@section('header_styles')

@stop
@section('meta')
@stop
@section('content')
{{-- =========== --}}
@php($page_content = App\StaticPage::get_page_content($slug))
<div class="store_policy">
    <div class="container">
        <div class="row" >
            <div class="col-md-1"></div>
            <div class="col-md-10" style="background:#f1f1f1;">
                @if($page_content == null)
                  <div class="policy_head">
                    <h4 class="text-center">Oops ! page data not found !.</h4>
                </div>
                @else
                <div class="policy_head">
                    <h2 class="text-center">{{$page_content->page_title}}</h2>
                </div>

                <div class="policy_detail">
                   {!! $page_content->page_description !!}
                </div>
                @endif
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
</div>

{{-- START FOOTER --}}
@stop
@section('footer_scripts')
<script src="{{asset('assets/default/js/custom.js')}}"></script>
@stop

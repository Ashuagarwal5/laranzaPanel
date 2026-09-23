@extends('layouts/default')
@section('title') @parent @stop
@section('header_styles')

@stop
@section('meta')
@stop
@section('content')
@php($home_content = App\HomePageContent::get_content())
@php($banner = App\Banner::get_banner('home'))

{{--     <div class="logo logo-dsp">
        <a href='index.html'><img src="{{asset('assets/default/img/logo.webp')}}" alt="Primecomfort" class="img-responsive" /></a>
      </div> --}}
      <div class="clearfix"></div>
      <div class="select-lang">
        <form>
          <div class="form-group">
            <div id="options" data-input-name="country2" data-selected-country="IN"></div>
          </div>
        </form>
      </div>



{{-- START FOOTER --}}
@stop
@section('footer_scripts')
<script src="{{asset('assets/default/js/jquery.flagstrap.min.js')}}"></script>
<script src="{{asset('assets/default/js/custom.js')}}"></script>
@stop

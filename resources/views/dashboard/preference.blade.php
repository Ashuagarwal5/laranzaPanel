@extends('dashboard.layout.default')
@section('title','Preference')
@section('content')
<div class="profile-box animate__animated animate__zoomInDown">
  <div class="row g-4 position-relative">                       
    <div class="col">
      <div class="p-2 text-center">
        <h3 class="text-white mb-1 mt-5">Preference</h3>
        <p class="text-white-75 mb-1">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. </p>
      </div>
    </div>
  </div>
</div>   
<div class="row position-relative  px-3" style="margin-top:-80px;">
  <div class="col-6">
    <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5">                        
      <div class="card-body p-5">
        <h3 class="mb-3">Newsletter Preference</h3>   

        <form  action="{{route('user.newsletterpreference.save')}}" method="POST" class="ajaxformclass" id="save_timepreference">
        @csrf
          <div class="alert" style="margin-top:10px;display:none;">
            <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="newsletterPreference" id="inlineRadio1" value="subscribe" {{ ($preference->newsletter_subscription=="subscribe")? "checked" : "" }}>
            <label class="form-check-label" for="inlineRadio1">Subscribe</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="newsletterPreference" id="inlineRadio2" value="unsubscribe" {{ ($preference->newsletter_subscription=="unsubscribe")? "checked" : "" }}>
            <label class="form-check-label" for="inlineRadio2">Unsubscribe</label>
          </div>
          <div class="form-check form-check-inline">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="card animate__animated animate__bounceInRight border-0 shadow-sm mb-3 mt-xxl-n5"> 
      <div class="card-body p-5">
        <h3 class="mb-3">Call Time Preference</h3>   
        <form  action="{{route('user.timepreference.save')}}" method="POST" class="ajaxformclass" id="save_newspreference">
        @csrf
          <div class="alert" style="margin-top:10px;display:none;">
            <a href="javascript:void()" class="close" data-dismiss="" aria-label="close">&times;</a><strong class="ajax_message"></strong>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="call_preference" id="inlineRadio3" value="day_time" {{ ($preference->call_preference=="day_time")? "checked" : "" }}>
            <label class="form-check-label" for="inlineRadio3">Only Day Time</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="call_preference" id="inlineRadio4" value="any_time" {{ ($preference->call_preference=="any_time")? "checked" : "" }}>
            <label class="form-check-label" for="inlineRadio4">Any time</label>
          </div>
          <div class="form-check form-check-inline">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
    
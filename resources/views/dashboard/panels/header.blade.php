<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
  @php
  $user_details = Sentinel::getUser();
  $siteSettingList = App\WebsiteSetting::getWebsiteSettingAdmin();
  @endphp

    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{route('salesofficer.dashboard')}}"><img src="{{URL::to(App\Helpers\Thumbnail::image("logo/$siteSettingList->logo","300","120","f"))}} "style="width: 95%;"/></a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <span class="mx-3  d-flex align-items-center rounded-pill px-3  me-lg-auto bg-dark2">
    <i class="bi bi-pen text-white"></i>
    <input class="form-control form-control-dark w-100 bg-dark2" type="text" placeholder="Welcome {{isset($user_details)? $user_details->full_name:''}}" aria-label="Welcome User Name" readonly="" style="background:#f8f9fa"> 
    </span>
     <a href="#"><i class="bi bi-bell fa-2x text-white"></i></a>
    <div class="navbar-nav">
   
      <div class="nav-item text-nowrap">
        <a class="nav-link px-3" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Sign out</a>
        <form id="frm-logout" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
      </div> 
    </div>
  </header>
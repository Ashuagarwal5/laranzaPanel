<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse animate__backInUp animate__animated">
    <div class="position-sticky pt-3">
        @php
            use Illuminate\Http\Request;
            $currentURL =Route::currentRouteName();
            // dd($currentURL);
        @endphp
        <div class="leftnav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'salesofficer.dashboard') active @endif" aria-current="page" href="{{ route('salesofficer.dashboard') }}">
                        <i class="bi bi-house-door"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'salesofficer.dealer') active @endif" href="{{ route('salesofficer.dealer') }}">
                        <i class="bi bi-bezier"></i>
                        My Dealers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'salesofficer.transection') active @endif" href="{{ route('salesofficer.transection') }}">
                        <i class="bi bi-view-list"></i>
                        Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'salesofficer.my_profile') active @endif" aria-current="page" href="{{ route('salesofficer.my_profile') }}">
                        <i class="bi bi-person"></i>
                        My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'salesofficer.edit_profile_password') active @endif" href="{{ route('salesofficer.edit_profile_password') }}">
                        <i class="bi bi-key"></i>
                        Change Password
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('auth.logout') }}">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>
                </li>

                {{-- 
                
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'user.mysales') active @endif" href="{{ route('user.mysales') }}">
                        <i class="bi bi-bezier"></i>
                        My Sales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'user.myorders') active @endif" href="{{ route('user.myorders') }}">
                        <i class="bi bi-arrow-left-right"></i>
                        My Orders
                    </a>
                </li> --}}



            </ul>

            {{-- <h4 class="p-3 fw-bold text-uppercase">My Achievements</h4>
            <ul class="nav flex-column">

                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'user.myaction_zone') active @endif" href="{{ route('user.myaction_zone') }}">
                        <i class="bi bi-bar-chart"></i>
                        My Action Zone
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'user.myrewards') active @endif" href="{{ route('user.myrewards') }}">
                        <i class="bi bi-heart"></i>
                        My Rewards
                    </a>
                </li>


            </ul> --}}
            {{-- <h4 class="p-3 fw-bold text-uppercase">Account Settings</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'user.myprofile') active @endif" aria-current="page" href="{{ route('user.myprofile') }}">
                        <i class="bi bi-person"></i>
                        My Profile
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'user.edit_profile_password') active @endif" href="{{ route('user.edit_profile_password') }}">
                        <i class="bi bi-key"></i>
                        Change Password
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($currentURL == 'user.preference') active @endif" href="{{ route('user.preference') }}">
                        <i class="bi bi-view-list"></i>
                        Preference
                    </a>
                </li>


            </ul>

            <h4 class="p-3 fw-bold text-uppercase">Other</h4>
            <ul class="nav flex-column"> --}}

                {{-- <li class="nav-item">
                    <a class="nav-link text-danger" href="dashboard.php">
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </a>
                </li> --}}

            </ul>



        </div>




    </div>
</nav>

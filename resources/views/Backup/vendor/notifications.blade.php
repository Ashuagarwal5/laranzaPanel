<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="livicon" data-name="bell" data-loop="true" data-color="#fff"
           data-hovercolor="#faeaee" data-size="28"></i>
<span class="notify notify-left" style="top:2px;">{{ count(App\SellerNotifications::getNotification(Sentinel::getUser()->id)) }}</span><i class="fa fa-bell" aria-hidden="true"></i>
    </a>
    <ul class=" notifications dropdown-menu">
        <li class="dropdown-title">You have {{ count(App\SellerNotifications::getNotification(Sentinel::getUser()->id)) }} Notifications</li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
				    @foreach(App\SellerNotifications::getNotification(Sentinel::getUser()->id) as $key => $val)
                <li>
                    <i class="fa fa-bell"  aria-hidden="true"></i>
                    <a href="#">{{ $val->notification_detail }}</a>
                    <small class="pull-right">
                        <span class="livicon paddingright_10" data-n="timer" data-s="10"></span>
                        {{ date('d-M-y',strtotime($val->created_at ))}}
                    </small>
                </li>
                  @endforeach




            </ul>
        </li>
        <li class="footer">
            <a href="#">View all</a>
        </li>
    </ul>
</li>
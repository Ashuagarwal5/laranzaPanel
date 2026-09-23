<div class="col-sm-3 col-md-3 sidebar extra dis-mob-none">

          <ul class="nav nav-sidebar">
			<h2 class="user-panel">Seller Menu</h2>
			<li {!! (Request::is('sellerpanel') ? 'class="active" id="active"' : '') !!}><a href="{!! route('sellerdashboard') !!}"> <i aria-hidden="true" class="fa fa-dashboard"></i> Dashboard</a></li>
			<li {!! (Request::is('sellerpanel/product/create') ? 'class="active" id="active"' : '') !!}><a href="{!! route('seller.product.create') !!}"> <i aria-hidden="true" class="fa fa-cart-plus"></i> Add Product</a></li>
			<li {!! (Request::is('sellerpanel/product') ? 'class="active" id="active"' : '') !!}><a href="{!! route('productlist') !!}"> <i aria-hidden="true" class="fa fa-book"></i> Manage Products</a></li>
			
		
			

            <li {!! (Request::is('sellerpanel/my-review') ? 'class="active" id="active"' : '') !!}><a href="{!! route('seller.review') !!}"> <i aria-hidden="true" class="fa fa-star"></i> Rating & Reviews</a></li>

<li {!! (Request::is('sellerpanel/orders') || Request::is('sellerpanel/orders/*') || Request::is('sellerpanel/orders-inquiry/*') || Request::is('sellerpanel/orders/show/*') ? 'class="active" id="active"' : '') !!}> <a href="#orders" data-toggle="collapse"><i class="fa fa-list-ol" aria-hidden="true"></i>
				Orders</a>
				<ul id="orders" class="category-level-2 dropdown-menu-tree panel-collapse collapse{!! (Request::is('sellerpanel/orders') || Request::is('sellerpanel/orders/*')  || Request::is('sellerpanel/orders/show/*')? 'in' : '') !!}">
				
<!--
					<li {!! ( Request::is('sellerpanel/orders/all-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','all-orders') }}">All Orders</a>
					</li>
-->
					
					<li {!! ( Request::is('sellerpanel/orders/pending-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','pending-orders') }}">Pending Orders</a>
					</li>
					
						<li {!! ( Request::is('sellerpanel/orders/awaiting-shipmment-order') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','awaiting-shipmment-order') }}">Awaiting Delivery Orders</a>
					</li>
					
					
					
					<li {!! ( Request::is('sellerpanel/orders/completed-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','completed-orders') }}">Completed Orders</a>
					</li>
					
					<li {!! ( Request::is('sellerpanel/orders/failed-orders') || Request::is('sellerpanel/orders/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.orders','failed-orders') }}">Failed Orders</a>
					</li>
					
					
					
				</ul>
			</li>
			

<li {!! (Request::is('sellerpanel/report') || Request::is('sellerpanel/report/*') || Request::is('sellerpanel/report/show/*') ? 'class="active" id="active"' : '') !!}> <a href="#reports" data-toggle="collapse"><i class="fa fa-file" aria-hidden="true"></i>
				Report</a>
				<ul id="reports" class="category-level-2 dropdown-menu-tree panel-collapse collapse{!! (Request::is('sellerpanel/orders') || Request::is('sellerpanel/report/*')  || Request::is('sellerpanel/report/show/*')? 'in' : '') !!}">
				
					<li {!! ( Request::is('sellerpanel/report/sales') || Request::is('sellerpanel/report/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.report.sales') }}">Sales Report</a>
					</li>
					
					<li {!! ( Request::is('sellerpanel/report/inventory') || Request::is('sellerpanel/inventory/show/*')? 'class="active" id="active"' : '') !!}>
						<a href="{{ route('seller.report.inventory') }}">Inventory Report</a>
					</li>
					
					
					
				</ul>
			</li>





			<li {!! (Request::is('sellerpanel/profile') || Request::is('sellerpanel/company-profile')  || Request::is('sellerpanel/account-setting') ||Request::is('sellerpanel/bank-setting') ? 'class="active" id="active"' : '') !!}> <a href="#dasmenu2" data-toggle="collapse"><i class="fa fa-cogs" aria-hidden="true"></i>
				GENERAL SETTING</a>
				<ul id="dasmenu2" class="category-level-2 dropdown-menu-tree panel-collapse collapse{!! (Request::is('sellerpanel/profile') || Request::is('sellerpanel/company-profile') || Request::is('sellerpanel/account-setting') || Request::is('sellerpanel/bank-setting') ? 'in' : '') !!}">
					<li {!! (Request::is('sellerpanel/company-profile') ? 'class="active" id="active"' : '') !!}><a href="{{ route('seller.company') }}">Company Profile Settings</a></li>
					<li {!! (Request::is('sellerpanel/profile') ? 'class="active" id="active"' : '') !!}><a href="{{ route('seller.profile') }}">Profile Settings</a></li>
					<li {!! (Request::is('sellerpanel/account-setting') ? 'class="active" id="active"' : '') !!}><a href="{{ route('seller.accountsetting') }}">Account Settings </a></li>
				</ul>
			</li>
			<li><a href="{{ route('seller_logout') }}"> <i aria-hidden="true" class="fa fa-sign-out"></i> Logout</a></li>

			
		
			



          </ul>
<center><img alt="Comapny Logo"  class="img-responsive text-center footer-logo-1" src="http://www.sakhtlaunde.in/dolovery/uploads/300/75/f/logo/xWDbNVYio2.png" ></center>
<br>
<br>	
<center><p><span class="footer-text" style="color:#fff;">Powered by:</span> <a href="{{route('home')}}" style="color:#000;">Dolovery</a></p></center>
        </div>



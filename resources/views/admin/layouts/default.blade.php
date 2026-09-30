<?php

use Cartalyst\Sentinel\Native\Facades\Sentinel;

$user = App\RoleUser::manager(Sentinel::getUser()->id);
$privileges = json_decode($user->privileges, true);
$page_link = \Request::route()->getName();
$page = App\Manager::getId($page_link);

if (empty($page)) {
	$page_link = Request::segment(2);
	$page = App\Manager::getId($page_link);
}
?>
@if (!empty($page) && !empty($privileges))
<? $count = 0; ?>
@foreach ($privileges as $key => $value)
@if (in_array($page->mng_id, $value) || $key == $page->mng_id)
<? $count = $count + 1; ?>
@endif
@endforeach
@if ($count == 0)
<? echo "<h3>You can't access requested page. To go Back <a href='" . route('admin.dashboard') . "'>CLICK HERE</a></h3>";
die; ?>
@endif
@endif
@php($siteSettingList = App\WebsiteSetting::getWebsiteSettingAdmin())

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>
		@section('title')
		{!! $siteSettingList->site_title !!}
		@show
	</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="{{ asset('assets/default/img/favicon.ico') }}" rel="icon" />
	<link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/admin/css/temp.css') }}" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css"
		href="{{ asset('assets/admin/vendors/bootstrap-datepicker/css/bootstrap-datepicker.css') }}">
	<script src="{{ asset('assets/admin/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"
		type="text/javascript"></script>

	<script src="{{ asset('assets/admin/vendors/bootstrap-datetimepicker-master/build/js/moment.js') }}"
		type="text/javascript"></script>

	<link rel="stylesheet" type="text/css"
		href="{{ asset('assets/admin/vendors/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.css') }}">
	<link rel="stylesheet" type="text/css"
		href="{{ asset('assets/admin/vendors/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css') }}">
	<link rel="stylesheet" type="text/css"
		href="{{ asset('assets/admin/vendors/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker-standalone.css') }}">
	<style>
		#overlay {
			position: fixed;
			z-index: 99999;
			top: 0;
			left: 0;
			bottom: 0;
			right: 0;
			background: rgba(0, 0, 0, 0.9);
			transition: 1s 0.4s;
		}

		.circle-loader {
			width: 100px;
			height: 100px;
			border-radius: 50%;
			border: 4px solid #fff;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			text-align: center;
			line-height: 100px;
			color: #fff;
			font-size: 20px;
			font-weight: bold;
		}

		.circle-progress {
			height: 100px;
			border-radius: 50%;
			background: #fff;
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 0;
			transition: 1s;
		}
	</style>
	@yield('header_styles')

<body class="skin-josh">
	{{-- <div id="overlay">
        <div id="progstat" class="circle-loader">
            <span class="inner-percent">0%</span>
        </div>
        <div id="progress" class="circle-progress"></div>
    </div> --}}

	<header class="header">
		<a href="{{ route('admin.dashboard') }}" class="logo" style="background-color:#f0f0f0; color: #0055ff">
			<center>
				<img src="{{ URL::to("uploads/logo/$siteSettingList->logo") }}"
					alt="Admin Panel" style="max-width: 100%; max-height: 50px; width: auto; height: auto;" />

			</center>
			<!--
        <span style="font-size: 16px;font-weight: 800;"> {{ strtoupper($siteSettingList->site_name) }}</span>
-->
		</a>
		<nav class="navbar navbar-static-top" role="navigation">
			<!-- Sidebar toggle button-->
			<div>
				<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
					<div class="responsive_nav"><i class="fa fa-list"></i></div>
				</a>
			</div>

			<div class="navbar-right">
				<ul class="nav navbar-nav">
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							@if (Sentinel::getUser()->pic)
							<img src="{!! url('/') . '/uploads/users/' . Sentinel::getUser()->pic !!}" alt="img"
								class="img-circle img-responsive pull-left" height="35px" width="35px" />
							@else
							<img src="{!! asset('assets/admin/img/avatar-man.jpg') !!} " width="27px"
								class="img-circle img-responsive pull-left" height="27px" alt="riot">
							@endif
							<div class="riot">
								<div>
									{{ Sentinel::getUser()->first_name }} {{ Sentinel::getUser()->last_name }}
									<span>
										<i class="caret"></i>
									</span>
								</div>
							</div>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header bg-light-blue">
								@if (Sentinel::getUser()->pic)
								<img src="{!! url('/') . '/uploads/users/' . Sentinel::getUser()->pic !!}" alt="img" class="img-circle img-bor" />
								@else
								<img src="{!! asset('assets/admin/img/avatar-man.jpg') !!}" class="img-responsive img-circle"
									alt="User Image">
								@endif
								<p class="topprofiletext">{{ Sentinel::getUser()->first_name }}
									{{ Sentinel::getUser()->last_name }}
								</p>
							</li>
							<!-- Menu Body -->

							<li role="presentation"></li>
							<li>

								<a href="{{ route('admin.edit.profile') }}">
									<i class="livicon" data-name="user" data-s="18"></i>
									Profile Settings
								</a>

							</li>
							<li role="presentation"></li>
							<li>

								<a href="{{ route('admin.logout') }}">
									<i class="livicon" data-name="sign-out" data-s="18"></i>
									Logout
								</a>

							</li>
							<!-- Menu Footer-->

						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		<!-- Left side column. contains the logo and sidebar -->
		<aside class="left-side sidebar-offcanvas">
			<section class="sidebar ">
				<div class="page-sidebar  sidebar-nav">

					<div class="clearfix"></div>
					<!-- BEGIN SIDEBAR MENU -->
					@include('admin.layouts._left_menu')
					<!-- END SIDEBAR MENU -->
				</div>
			</section>
		</aside>
		<aside class="right-side">
			@yield('content')

		</aside>
		<!-- right-side -->
	</div>
	<div id="modal-regular" class="modal fade" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div style="text-align:center;"> <img style="float:center;" src="" alt=""
						class="loading"></div>
			</div>
		</div>
	</div>

	<div id="modal-large" class="modal fade" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div style="text-align:center;"> <img style="float:center;" src="" alt=""
						class="loading"></div>
			</div>
		</div>
	</div>

	<div id="modal-email" class="modal fade" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div style="text-align:center;"> <img style="float:center;" src="" alt=""
						class="loading"></div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-message" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="{{ route('validate_otp') }}" class="ajaxformclass" method="get">
					<div class="alert p-1" style="margin-top:10px;display:none;">
						<a href="javascript:void()" class="close" data-dismiss=""
							aria-label="close">&times;</a>&nbsp;<strong class="ajax_message"></strong>
					</div>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="modalLabel">Enter Your OTP</h4>
					</div>
					<div class="modal-body">
						<input type="text" class="form-control" name="message_otp" id="message_otp">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-danger">Confirm</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog"
		aria-labelledby="user_delete_confirm_title" aria-hidden="true">

		<div class="modal-dialog">

			<div class="modal-content">
				<div class="modal-header">
					<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
					<h4 id="user_delete_confirm_title" class="modal-title">Delete Record</h4>
				</div>
				<div class="modal-body"> Are you sure to delete this Record? </div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
					<a class="btn btn-danger deletepage" type="button" href="javascript:">Delete</a>
				</div>


			</div>


		</div>
	</div>

	<!-- Resolved PopUp-->
	<div class="modal fade" id="resolved_confirm" tabindex="-1" role="dialog"
		aria-labelledby="user_delete_confirm_title" aria-hidden="true">

		<div class="modal-dialog">

			<div class="modal-content">
				<div class="modal-header">
					<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
					<h4 id="user_delete_confirm_title" class="modal-title">Resolved Record</h4>
				</div>
				<div class="modal-body"> Are you sure to resolved this Record? </div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
					<a class="btn btn-danger confirmResolveUrl" type="button" href="">Resolved</a>
				</div>


			</div>


		</div>
	</div>



	<div class="modal fade" id="delete-confirm-for-all" tabindex="-1" role="dialog"
		aria-labelledby="user_delete_confirm_title" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" aria-hidden="true" data-dismiss="modal" type="button">×</button>
					<h4 id="user_delete_confirm_title" class="modal-title"></h4>
				</div>
				<div class="modal-body"> Please Wait... </div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal" type="button">Cancel</button>
				</div>
			</div>
		</div>
	</div>

	<!-- <a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button"
        title="Return to top" data-toggle="tooltip" data-placement="left">
        <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff"
            data-hc="white"></i>
    </a> -->
	<!-- global js -->




	@if (Request::is('admin/media'))
	<script src="{{ asset('assets/admin/js/jquery-1.8.js') }}?v=4" type="text/javascript"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js" type="text/javascript"></script>

	<script src="{{ asset('assets/admin/js/app2.js') }}?v=4" type="text/javascript"></script>
	@else
	<script src="{{ asset('assets/admin/js/app.js') }}?v=4" type="text/javascript"></script>
	@endif


	<script
		src="{{ asset('assets/admin/vendors/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js') }}"
		type="text/javascript"></script>
	<script>
		$('body').on('hidden.bs.modal', '.modal', function() {
			$(this).removeData('bs.modal');
		});
	</script>

	<!-- end of global js -->
	<!-- begin page level js -->
	@yield('footer_scripts')
	<!-- toastr notification script when page reload -->

	<!-- Add fancyBox -->
	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="{{ asset('assets/admin/fancybox/jquery.mousewheel-3.0.6.pack.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.form.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/admin/js/formClass.js') }}?v=2"></script>

	<link rel="stylesheet" href="{{ asset('assets/admin/fancybox/jquery.fancybox.css') }}" type="text/css"
		media="screen" />
	<script type="text/javascript" src="{{ asset('assets/admin/fancybox/jquery.fancybox.pack.js?v=2.1.5') }}"></script>

	<!-- Optionally add helpers - button, thumbnail and/or media -->
	<link rel="stylesheet" href="{{ asset('assets/admin/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5') }}"
		type="text/css" media="screen" />
	<script type="text/javascript" src="{{ asset('assets/admin/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5') }}">
	</script>
	<script type="text/javascript" src="{{ asset('assets/admin/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6') }}">
	</script>

	<link rel="stylesheet" href="{{ asset('assets/admin/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7') }}"
		type="text/css" media="screen" />
	<script type="text/javascript" src="{{ asset('assets/admin/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7') }}">
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title: {
						type: 'outside'
					},
					overlay: {
						speedOut: 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect: 'none',
				closeEffect: 'none',

				helpers: {
					title: {
						type: 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS: 'fancybox-custom',
				closeClick: true,

				openEffect: 'none',

				helpers: {
					title: {
						type: 'inside'
					},
					overlay: {
						css: {
							'background': 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect: 'elastic',
				openSpeed: 150,

				closeEffect: 'elastic',
				closeSpeed: 150,

				closeClick: true,

				helpers: {
					overlay: null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect: 'none',
				closeEffect: 'none',

				prevEffect: 'none',
				nextEffect: 'none',

				closeBtn: false,

				helpers: {
					title: {
						type: 'inside'
					},
					buttons: {}
				},

				afterLoad: function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this
						.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect: 'none',
				nextEffect: 'none',

				closeBtn: false,
				arrows: false,
				nextClick: true,

				helpers: {
					thumbs: {
						width: 50,
						height: 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			 */
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect: 'none',
					closeEffect: 'none',
					prevEffect: 'none',
					nextEffect: 'none',

					arrows: false,
					helpers: {
						media: {},
						buttons: {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href: 'iframe.html',
					type: 'iframe',
					padding: 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([{
					href: '1_b.jpg',
					title: 'My title'
				}, {
					href: '2_b.jpg',
					title: '2nd title'
				}, {
					href: '3_b.jpg'
				}], {
					helpers: {
						thumbs: {
							width: 75,
							height: 50
						}
					}
				});
			});


		});
	</script>


	<script>
		@if(Session::has('message'))
		var type = "{{ Session::get('alert-type', 'info') }}";
		switch (type) {
			case 'info':
				toastr.info("{{ Session::get('message') }}");
				break;

			case 'warning':
				toastr.warning("{{ Session::get('message') }}");
				break;

			case 'success':
				toastr.success("{{ Session::get('message') }}");
				break;

			case 'error':
				toastr.error("{{ Session::get('message') }}");
				break;
		}
		@endif
	</script>
	<script>
		// (function() {
		//     function id(v) {
		//         return document.getElementById(v);
		//     }

		//     function loadbar() {
		//         var ovrl = id("overlay"),
		//             prog = id("progress"),
		//             stat = id("progstat"),
		//             img = document.images,
		//             c = 0,
		//             tot = img.length;
		//         if (tot == 0) return doneLoading();

		//         function imgLoaded() {
		//             c += 1;
		//             var perc = ((100 / tot * c) << 0) + "%";
		//             //   prog.style.width = perc;
		//             stat.textContent = perc;
		//             if (c === tot) return doneLoading();
		//         }

		//         function doneLoading() {
		//             ovrl.style.opacity = 0;
		//             setTimeout(function() {
		//                 ovrl.style.display = "none";
		//             }, 1200);
		//         }

		//         for (var i = 0; i < tot; i++) {
		//             var tImg = new Image();
		//             tImg.onload = imgLoaded;
		//             tImg.onerror = imgLoaded;
		//             tImg.src = img[i].src;
		//         }
		//     }
		//     document.addEventListener("DOMContentLoaded", loadbar, false);
		// })();
	</script>


</body>

</html>
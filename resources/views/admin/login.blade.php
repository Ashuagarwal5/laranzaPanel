@php($siteSettingList=App\WebsiteSetting::getWebsiteSettingAdmin())
<!DOCTYPE html>
<html>

<head>
	<title>Login</title>
	<link href="{{asset('assets/default/img/favicon.ico')}}" rel="icon" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- global level css -->
	<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
	<!-- end of global level css -->
	<!-- page level css -->
	<link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/login.css')}}" />
	<!-- end of page level css -->
	<style>
		.white_bg {
			background: #fff !important;
			font-size: 30px;
			margin: 20px -36px 20px -35px;
			padding: 10px 0;
			text-align: center;
		}

		.black_bg {
			background: #5cb85c !important;
			font-size: 30px;
			margin: -30px -36px 20px -35px;
			padding: 10px 0;
			text-align: center;
		}

		<blade media|%20(min-width%3A786px)%20%7B>#login {
			margin-top: 50px;
		}


		}

		<blade media|%20(min-width%3A986px)%7B>#adm_login {
			margin-right: -35px;
		}
		}

		#wrapper label {
			color: #fff !important;
		}

		body {
			height: 100vh;
			display: flex;
			align-items: center;
		}

		#wrapper input:not([type="checkbox"]) {
			background: transparent;
			color: #fff;
			width: 100%;
			box-sizing: border-box;
			padding: 10px 12px 10px 36px;
		}

		.input-icon-wrap,
		.password-field-wrap {
			position: relative;
		}

		.input-icon-wrap .input-icon,
		.password-field-wrap .input-icon {
			position: absolute;
			left: 10px;
			top: 50%;
			transform: translateY(-50%);
			pointer-events: none;
			z-index: 2;
		}

		.password-field-wrap input {
			/* login.css sets box-sizing: content-box on "#wrapper input" with an ID
       selector, which otherwise beats this rule and makes the icon padding
       add to the 91% width instead of eating into it - !important keeps the
       password field the same width as the e-mail field. */
			box-sizing: border-box !important;
			padding-right: 34px !important;
		}

		#adm_login .form-group {
			width: 100%;
		}

		#adm_login input[type="submit"] {
			width: 100%;
			box-sizing: border-box;
		}

		.password-toggle {
			position: absolute;
			right: 6px;
			top: 50%;
			transform: translateY(-50%);
			background: none;
			border: none;
			padding: 4px;
			line-height: 0;
			cursor: pointer;
			color: #bbb;
			outline: none;
		}

		.password-toggle:hover {
			color: #fff;
		}

		.password-toggle svg {
			width: 18px;
			height: 18px;
			display: block;
		}

		.password-toggle .eye-closed {
			display: none;
		}

		.password-toggle.is-visible .eye-open {
			display: none;
		}

		.password-toggle.is-visible .eye-closed {
			display: block;
		}
	</style>

</head>

<body style="background:url({{asset('assets/admin/background.jpg')}}) 0 0 no-repeat; background-size:cover;">
	<div class="container">
		<div class="row vertical-offset-100 ">
			<!-- Notifications -->
			<div class="d-flex col-sm-6 col-sm-offset-3  col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
				<div id="container_demo">
					<a class="hiddenanchor" id="toregister"></a>
					<a class="hiddenanchor" id="tologin"></a>
					<a class="hiddenanchor" id="toforgot"></a>

					<div id="wrapper">
						<div id="login" class="animate form" style="background:#262626c4; border-radius:5px;">
							<h3 class="bg-dark">
								<div style="padding-top:15px">
									<img src="{{asset('img/laranzaWhiteLogo.png')}}" alt="Laranza" style="max-height:120px;">
								</div>
							</h3>
							<form id="adm_login" class="ajax_form" action="{{route('admin.signin')}}" autocomplete="on" method="post" role="form">



								<!-- CSRF Token -->
								<div class="alert " role="alert" style="display:none; width:92%;">
									<span class="close">×</span>
									<span class="ajax_message"> </span>
								</div>


								<input type="hidden" name="_token" value="{{csrf_token()}}" />

								<div class="form-group {{$errors->first('email', 'has-error')}}">
									<label style="margin-bottom:0px;  color:#fff;" for="email" class="uname control-label">
										E-mail
									</label>
									<div class="input-icon-wrap">
										<i class="livicon input-icon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="#fff"></i>
										<input id="email" name="email" required type="email" placeholder="E-mail" value="{!! old('email') !!}" />
									</div>
									<div class="col-sm-12">
										{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
									</div>
								</div>
								<div class="form-group {{$errors->first('password', 'has-error')}}">
									<label style="margin-bottom:0px;color:#fff;" for="password" class="youpasswd">
										Password
									</label>
									<div class="password-field-wrap">
										<i class="livicon input-icon" data-name="key" data-size="16" data-loop="true" data-c="#fff" data-hc="#fff"></i>
										<input id="password" name="password" required type="password" placeholder="eg. X8df!90EO" />
										<button type="button" class="password-toggle" id="toggle_password" tabindex="-1" aria-label="Show password">
											<svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
												<circle cx="12" cy="12" r="3" />
											</svg>
											<svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a20.3 20.3 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a20.3 20.3 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
												<path d="M1 1l22 22" />
											</svg>
										</button>
									</div>
									<div class="col-sm-12">
										{!! $errors->first('password', '<span class="help-block">:message</span>') !!}
									</div>
								</div>
								<div class="form-group" style="margin-top:50px;">
									<input type="submit" value="LOGIN NOW!" class="btn btn-danger btn-lg" />
								</div>


								<!-- <p class="change_link">
									<a href="#toforgot">
										<button type="button" class="btn btn-responsive botton-alignment btn-warning btn-sm">Forgot password</button>
									</a>
									<a href="#toregister">

									</a>
								</p> -->

							</form>
						</div>


					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- global js -->
	<script src="{{asset('assets/admin/js/jquery-1.11.1.min.js')}}" type="text/javascript"></script>
	<!-- Bootstrap -->
	<script src="{{asset('assets/admin/js/bootstrap.min.js')}}" type="text/javascript"></script>
	<!--livicons-->
	<script src="{{asset('assets/admin/js/raphael-min.js')}}"></script>
	<script src="{{asset('assets/admin/js/livicons-1.4.min.js')}}"></script>
	<script src="{{asset('assets/admin/js/pages/login.js')}}" type="text/javascript"></script>
	<!-- end of global js -->
	<script src="{{asset('assets/admin/js/jquery.form.js')}}" type="text/javascript"></script>



	<script>
		$(document).ready(function() {
			$('.close').click(function() {
				$(".alert").hide();
			});
		});
		// Bound outside document.ready so an error in another page-ready
		// handler (jQuery 1.x stops the rest) can't disable the eye button.
		$(document).on('click', '#toggle_password', function() {
			var $input = $('#password');
			var isHidden = $input.attr('type') === 'password';
			$input.attr('type', isHidden ? 'text' : 'password');
			$(this).toggleClass('is-visible', isHidden);
			$(this).attr('aria-label', isHidden ? 'Hide password' : 'Show password');
		});
		$(document).on("submit", ".ajax_form", function(event) {
			var posturl = $(this).attr('action');
			var callbackFunction = $(this).attr('data-callback_function');
			if (callbackFunction) {
				if (callbackForm() == false) {
					return false;
				}
			}
			var formid = '#' + $(this).attr('id');
			$(this).ajaxSubmit({
				url: posturl,
				dataType: 'json',
				type: "POST",
				beforeSend: function() {
					$(".submit").attr("disabled", 'disabled');
					$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeIn(200);
					$(formid).find('.alert').addClass('alert-info');
					$(formid).find('.alert').show();
					$(formid).find('.ajax_message').html('<strong>Please Wait! </strong>Your action is in proccess...');
				},
				success: function(response) {
					$(".submit").removeAttr("disabled", 'disabled');
					$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
					$(formid).find('.form-group').removeClass('has-error');

					if (response.messageNot) {
						$(formid).find('.alert').removeClass('alert-success').removeClass('alert-danger').fadeOut(100);
					} else {
						$(formid).find('.alert').removeClass('alert-info').removeClass('alert-success').removeClass('alert-danger').fadeOut(200);
						if (response.status == "success") {
							$(formid).find('.alert').fadeIn();
							$(formid).find('.alert').addClass('alert-success').children('.ajax_message').html(response.success_msg);
						} else {

							$(formid).find('.alert').fadeIn();
							$(formid).find('.alert').addClass('alert-danger').children('.ajax_message').html(response.error_msg);
							$.each(response.errorArray, function(key, value) {
								console.log(key + " => " + value);
								var msg = '<label class="error formmessage ' + key + '" for="' + key + '"  style="color:red">' + value + '</label>';

								$(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').closest('.form-group').addClass('has-error');

								$(formid).find('input[name="' + key + '"], select[name="' + key + '"],textarea[name="' + key + '"]').addClass('inputTxtError').after(msg);
							});


						}
					}

					if (response.loginstatus == 'success') {
						window.location.href = response.url;
					}

					if (response.slideToTop) {
						$('html, body').animate({
							scrollTop: $(formid).offset().top - 290
						}, 800);
					}
					if (response.url)
						window.location.href = response.url;
					if (response.selfReload)
						window.location.reload();
					if (response.status == 'success') {
						$(formid)[0].reset();
					}
					if (response.redirect == 'yes') {
						window.location.href = response.redirectUrl;
					}
				},
				error: function(response) {
					alert('Server Error !');
				}
			});
			return false;
		});
	</script>
</body>

</html>
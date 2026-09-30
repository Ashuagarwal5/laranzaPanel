<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
 */

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
	//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

//change currency
//  Route::get('change-currency/{locale_crrency}', function ($locale_crrency)
//  {

// });

Route::get('login', array('as' => 'auth', 'uses' => 'HomeController@login'));
Route::post('login', array('as' => 'auth.login', 'uses' => 'HomeController@postlogin'));
Route::any('logout', array('as' => 'auth.logout', 'uses' => 'HomeController@logout'));

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));
Route::get('downloadpdf/{slug}', array('as' => 'downloadfil', 'uses' => 'LoginController@Download'));
Route::get('change-currency/{country_code}', array('as' => 'change-currency', 'uses' => 'HomeController@change_currency'));
Route::get('download-code/{name}', array('as' => 'products.download.images', 'uses' => 'Admin\ProductsController@download_image'));
Route::get('view-image/{name}', array('as' => 'products.view.images', 'uses' => 'Admin\ProductsController@view_image'));

/*
 * Fallback for files on the `uploads` disk (storage/app/uploads).
 *
 * On the live server the .htaccess rewrite hands /uploads/... to CImage before
 * Laravel ever sees the request, so this route never fires there. It only takes
 * effect where mod_rewrite is not in play - `php artisan serve`, or an install
 * served from a subdirectory - where the rewrite target /img/webroot/img.php
 * does not resolve and every image 404s.
 *
 * Honours the CImage sizing prefix so an oversized original is scaled down the
 * way the calling view asked for. Without this a 2000px logo requested at
 * 200x50 was served at its natural size and covered the page. Resized copies
 * are cached under uploads/.thumbs so the scaling happens once per size.
 */
Route::get('uploads/{path}', function ($path) {
	// Pull the CImage sizing prefix off the front: uploads/{w}/{h}/{opts}/real/path.png
	$width = $height = null;
	if (preg_match('#^(\d+)/(\d+)/(?:[A-Za-z0-9_=]+/)?#', $path, $m)) {
		$width = (int) $m[1];
		$height = (int) $m[2];
		$path = substr($path, strlen($m[0]));
	}

	$root = realpath(storage_path('app/uploads'));
	$full = realpath($root.DIRECTORY_SEPARATOR.$path);

	// Keep the request inside the uploads root.
	if ($root === false || $full === false || strpos($full, $root) !== 0 || !is_file($full)) {
		abort(404);
	}

	$headers = array('Cache-Control' => 'public, max-age=86400');

	if (!$width || !$height || !function_exists('imagecreatetruecolor')) {
		return response()->file($full, $headers);
	}

	$info = @getimagesize($full);
	if ($info === false) {
		return response()->file($full, $headers);
	}

	// Already within the requested box - no point rewriting it.
	if ($info[0] <= $width && $info[1] <= $height) {
		return response()->file($full, $headers);
	}

	$cached = storage_path('app/uploads/.thumbs/'.$width.'x'.$height.'/'.$path);
	if (is_file($cached) && filemtime($cached) >= filemtime($full)) {
		return response()->file($cached, $headers);
	}

	switch ($info[2]) {
		case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($full); break;
		case IMAGETYPE_PNG:  $src = @imagecreatefrompng($full); break;
		case IMAGETYPE_GIF:  $src = @imagecreatefromgif($full); break;
		default: $src = false;
	}
	if ($src === false) {
		return response()->file($full, $headers);
	}

	// Fit inside the box, keeping the aspect ratio.
	$ratio = min($width / $info[0], $height / $info[1]);
	$newWidth = max(1, (int) round($info[0] * $ratio));
	$newHeight = max(1, (int) round($info[1] * $ratio));

	$dst = imagecreatetruecolor($newWidth, $newHeight);
	if ($info[2] === IMAGETYPE_PNG || $info[2] === IMAGETYPE_GIF) {
		imagealphablending($dst, false);
		imagesavealpha($dst, true);
		imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
	}
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $info[0], $info[1]);

	@mkdir(dirname($cached), 0755, true);
	switch ($info[2]) {
		case IMAGETYPE_JPEG: imagejpeg($dst, $cached, 90); break;
		case IMAGETYPE_PNG:  imagepng($dst, $cached); break;
		case IMAGETYPE_GIF:  imagegif($dst, $cached); break;
	}
	imagedestroy($src);
	imagedestroy($dst);

	return response()->file(is_file($cached) ? $cached : $full, $headers);
})->where('path', '.*');

Route::get('store/{slug}', array('as' => 'prduct.info',
	'uses' => 'HomeController@product_info'));

Route::post('get-states/', array('as' => 'country.get-states', 'uses' => 'HomeController@get_all_states'));

Route::get('socially-conscious', array('as' => 'page.socially-conscious',
	'uses' => 'HomeController@socially_conscious'));
Route::get('hempstrolassistance', array('as' => 'page.hempstrolassistance',
	'uses' => 'HomeController@hempstrolassistance'));

Route::get('page-detail/{slug}', array('as' => 'socially-conscious.page-detail',
	'uses' => 'HomeController@socially_conscious_page_detail'));

Route::get('about-us', array('as' => 'page.about-us', 'uses' => 'HomeController@about_us'));
Route::get('faq', array('as' => 'faq', 'uses' => 'HomeController@faq'));
Route::get('store-policy', array('as' => 'page.store-policy', 'uses' => 'HomeController@store_policy'));
Route::get('contact-us', array('as' => 'page.contact-us', 'uses' => 'HomeController@contact_us'));
Route::post('post-contactus', array('as' => 'post-contactus', 'uses' => 'HomeController@store_contactus'));

Route::get('coming-soon', function () {
	return view('coming-soon');
});

Route::get('be-a-good-woman', array('as' => 'page.be_a_good_woman', 'uses' => 'HomeController@be_a_good_woman'));

Route::post('email-subscriber', array('as' => 'email-subscriber',
	'uses' => 'HomeController@email_subscriber'));

Route::post('/contact-enq', array('as' => 'home.contact-enq', 'uses' => 'HomeController@contact_us_enq'));

Route::get('blog', array('as' => 'page.blog', 'uses' => 'HomeController@blog'));
Route::get('uk', array('as' => 'page.blog', 'uses' => 'UkController@index'));

Route::get('blog/{slug}', array('as' => 'page.blog.description', 'uses' => 'HomeController@blog_description'));
Route::post('post/blog-reply', array('as' => 'post.blog-reply', 'uses' => 'HomeController@store_blog_reply'));

Route::get('clear-cache', array('as' => 'clear-cache', 'uses' => 'HomeController@clearCache'));
Route::post('/api', array('as' => 'api.index', 'uses' => 'ApiController@index'));
Route::get('/api', array('as' => 'api.index', 'uses' => 'ApiController@index'));
Route::post('/api/checkresp', array('as' => 'api.checkresp', 'uses' => 'ApiController@checkresp'));

Route::post('/add_to_cart', array('as' => 'product.add-to-cart', 'uses' => 'HomeController@add_to_cart'));
Route::get('view-cart', array('as' => 'page.view-cart', 'uses' => 'HomeController@view_cart'));
Route::post('update-cart', array('as' => 'update-cart', 'uses' => 'HomeController@update_cart'));
Route::post('update-cart-ajax', array('as' => 'update-cart-ajax', 'uses' => 'HomeController@update_cart_ajax'));

Route::get('delete-cart-item/{rowId}', array('as' => 'product.delete_cart', 'uses' => 'HomeController@delete_from_cart'));

Route::group(array('prefix' => 'checkout'), function () {
	Route::get('/', array('as' => 'checkout-cart', 'uses' => 'CheckoutController@index'));
	Route::match(array('GET', 'POST'), '/signin', array('as' => 'checkout.signin', 'uses' => 'CheckoutController@signin'));
	Route::match(array('POST'), '/biling', array('as' => 'checkout.biling.address', 'uses' => 'CheckoutController@biling'));
	Route::match(array('GET', 'POST'), '/payment', array('as' => 'checkout.payment.page', 'uses' => 'CheckoutController@payment_page'));

	Route::match(array('GET', 'POST'), '/address', array('as' => 'checkout.user.address', 'uses' => 'CheckoutController@user_address'));

});

Route::get('indipay/response', array('as' => 'indipay/response', 'uses' => 'CheckoutController@responsePayU'));
Route::post('indipay/response', array('as' => 'indipay/responses', 'uses' => 'CheckoutController@responsePayU'));



Route::post('coupon-code', array('as' => 'coupon-code', 'uses' => 'CheckoutController@checkCouponCode'));

Route::post('/razorpay-return', array('as' => 'payment.razorpay.payment_return', 'uses' => 'CheckoutController@payment_return'));

Route::post('/cash-on-delivery', array('as' => 'cashon_delivery', 'uses' => 'CheckoutController@cash_on_delivery'));

Route::get('/checkout-info', array('as' => 'checkout.info', 'uses' => 'CheckoutController@checkout_info'));
Route::get('/checkout-cod-info', array('as' => 'checkout.cod-info', 'uses' => 'CheckoutController@checkout_cod_info'));

Route::get('/page/{slug}', array('as' => 'front.page.show', 'uses' => 'HomeController@static_page'));

//====================== User Controller Routes==============================
Route::get('sign-up', array('as' => 'page.sign-up', 'uses' => 'UserController@register'));
Route::post('/sign-up', array('as' => 'store.user.register', 'uses' => 'UserController@storeRegister'));

Route::get('/enter-otp', array('as' => 'enter-otp', 'uses' => 'UserController@otp_view'));
Route::post('/resend-otp', array('as' => 'resend-otp', 'uses' => 'UserController@resend_otp'));
Route::post('/match-otp', array('as' => 'post.match-otp', 'uses' => 'UserController@match_otp'));
Route::get('/success-info', array('as' => 'success-info', 'uses' => 'UserController@success_info'));

Route::get('signin', array('as' => 'page.sign-in', 'uses' => 'UserController@login'));
Route::post('/sign-in', array('as' => 'page.sign-in', 'uses' => 'UserController@postLogin'));

//for review
Route::post('/user-review', array('as' => 'post.userreview',
	'uses' => 'UserController@user_review'));

Route::get('forgot-password', array('as' => 'forgot.password', 'uses' => 'UserController@getForgotPassword'));
Route::post('/forgot-password', array('as' => 'forgot.password.confirm', 'uses' => 'UserController@postForgotPassword'));

//====================== Middleware User Controller Routes==============================
Route::group(array('prefix' => 'account', 'middleware' => 'SentinelUser'), function () {
	Route::get('/', array('as' => 'dashboard', 'uses' => 'UserController@dashboard'));
	Route::post('/account/user-review', array('as' => 'post.user-review',
		'uses' => 'UserController@user_review'));

	Route::get('edit-profile', array('as' => 'account.edit-profile', 'uses' => 'UserController@accountSetting'));
	Route::get('add-address/{id?}', array('as' => 'account.add-address', 'uses' => 'UserController@add_address'));

	Route::post('update-profile/{id}', array('as' => 'post.update-profile', 'uses' => 'UserController@updateProfile'));

	Route::get('my-orders', array('as' => 'account.my-orders', 'uses' => 'UserController@my_orders'));
	Route::get('my-address', array('as' => 'account.my-address', 'uses' => 'UserController@my_address'));
	Route::get('delete-address/{address_id}', array('as' => 'account.delete.address', 'uses' => 'UserController@delete_address'));
	Route::post('save-new-address/{id?}', array('as' => 'store.new-address',
		'uses' => 'UserController@store_address'));
	Route::get('invoice', array('as' => 'product.invoice', 'uses' => 'UserController@invoice'));

	Route::post('update-password/{id}', array('as' => 'account.update-password', 'uses' => 'UserController@updatePassword'));

	Route::post('set-default-address', array('as' => 'account.set-default-address',
		'uses' => 'UserController@set_default_address'));
	Route::get('/print-invoice/{order_id}', array('as' => 'print-invoice',
		'uses' => 'UserController@print_invoice'));

	Route::get('/logout', array('as' => 'logout', 'uses' => 'UserController@getLogout'));
});
//==============================  Admin Path Start Here ====================================//

//~ Route::get('admin/', array('as' => 'adminn.signin', 'uses' => 'Admin\AdminAuthController@getSignin'));

Route::get('/cpmin/logout', array('as' => 'admin.logout', 'uses' => 'Admin\AdminAuthController@getLogout'));
Route::get('/cpmin/signin', array('as' => 'admin.signin', 'uses' => 'Admin\AdminAuthController@getSignin'));
Route::post('/cpmin/signin', array('as' => 'admin.signin', 'uses' => 'Admin\AdminAuthController@postSignin'));
Route::post('/cpmin/forgot-password', array('as' => 'admin.forgot.password', 'uses' => 'Admin\AdminAuthController@postForgotPassword'));

Route::group(array('prefix' => 'task'), function () {
	Route::post('create', 'Admin\TaskController@store');
	Route::get('data', 'Admin\TaskController@data');
	Route::post('{task}/edit', 'Admin\TaskController@update');
	Route::post('{task}/delete', 'Admin\TaskController@delete');

});

Route::get('cpmin/products/main_sub_cat_data', array('as' => 'admin.products.mainsubCatData', 'uses' => 'Admin\ProductsController@mainsubCatData'));
Route::get('cpmin/products/sub_cat_data', array('as' => 'admin.products.subCatData', 'uses' => 'Admin\ProductsController@subCatData'));
Route::group(array('prefix' => 'salesofficer', 'middleware' => 'SentinelSalesOfficer'), function () {
	Route::get('/', array('as' => 'salesofficer.dashboard', 'uses' => 'SalesOfficer\HomeController@showHome'));
	Route::get('my-dealers', array('as' => 'salesofficer.dealer', 'uses' => 'SalesOfficer\HomeController@dealers'));
	Route::get('my-profile', array('as' => 'salesofficer.my_profile', 'uses' => 'SalesOfficer\HomeController@myProfile'));
	Route::get('my-transection/{id?}', array('as' => 'salesofficer.transection', 'uses' => 'SalesOfficer\HomeController@transection'));
	Route::get('edit-profile', array('as' => 'salesofficer.edit_profile_password', 'uses' => 'SalesOfficer\HomeController@editProfile'));
	Route::post('save-password', array('as' => 'salesofficer.save_password', 'uses' => 'SalesOfficer\HomeController@savePassword'));
});
Route::group(array('prefix' => 'cpmin', 'middleware' => 'SentinelAdmin'), function () {

	Route::get('/', array('as' => 'admin.dashboard', 'uses' => 'Admin\CodespurController@showHome'));

	Route::group(array('prefix' => 'reward-catalog-categories'), function () {
		Route::get('/', array('as' => 'admin.reward-catalog-categories', 'uses' => 'Admin\RewardCategoryController@index'));
		Route::get('data', array('as' => 'admin.reward-catalog-categories.data', 'uses' => 'Admin\RewardCategoryController@data'));
		Route::get('form-modal/{id?}', array('as' => 'admin.reward-catalog-categories.form-modal', 'uses' => 'Admin\RewardCategoryController@formModal'));
		Route::get('create', array('as' => 'admin.reward-catalog-categories.create', 'uses' => 'Admin\RewardCategoryController@create'));
		Route::post('create', array('as' => 'admin.reward-catalog-categories.store', 'uses' => 'Admin\RewardCategoryController@store'));
		Route::get('edit/{id}', array('as' => 'admin.reward-catalog-categories.edit', 'uses' => 'Admin\RewardCategoryController@edit'));
		Route::post('edit/{id}', array('as' => 'admin.reward-catalog-categories.update', 'uses' => 'Admin\RewardCategoryController@update'));
		Route::get('{id}/confirm-delete', array('as' => 'admin.reward-catalog-categories.confirm-delete', 'uses' => 'Admin\RewardCategoryController@getModalDelete'));
		Route::get('{id}/destroy', array('as' => 'admin.reward-catalog-categories.destroy', 'uses' => 'Admin\RewardCategoryController@destroy'));
	});

	Route::group(array('prefix' => 'reward-products'), function () {
		Route::get('/', array('as' => 'admin.reward-products', 'uses' => 'Admin\RewardProductController@index'));
		Route::get('data', array('as' => 'admin.reward-products.data', 'uses' => 'Admin\RewardProductController@data'));
		Route::get('create', array('as' => 'admin.reward-products.create', 'uses' => 'Admin\RewardProductController@create'));
		Route::post('create', array('as' => 'admin.reward-products.store', 'uses' => 'Admin\RewardProductController@store'));
		Route::get('edit/{id}', array('as' => 'admin.reward-products.edit', 'uses' => 'Admin\RewardProductController@edit'));
		Route::post('edit/{id}', array('as' => 'admin.reward-products.update', 'uses' => 'Admin\RewardProductController@update'));
		Route::get('{id}/confirm-status', array('as' => 'admin.reward-products.confirm-status', 'uses' => 'Admin\RewardProductController@getModalStatus'));
		Route::get('{id}/change-status', array('as' => 'admin.reward-products.status', 'uses' => 'Admin\RewardProductController@changeStatus'));
		Route::get('{id}/confirm-delete', array('as' => 'admin.reward-products.confirm-delete', 'uses' => 'Admin\RewardProductController@getModalDelete'));
		Route::get('{id}/destroy', array('as' => 'admin.reward-products.destroy', 'uses' => 'Admin\RewardProductController@destroy'));
	});
	Route::post('add-activity', array('as' => 'add-activity', 'uses' => 'Admin\CodespurController@addActivity'));
	Route::get('loadCalendarJson', array('as' => 'load.calendar.data', 'uses' => 'Admin\CodespurController@loadCalendarData'));

	Route::get('edit_profile', array('as' => 'admin.edit.profile', 'uses' => 'Admin\CodespurController@editProfile'));
	Route::post('editprofile/{slug}', array('as' => 'admin.update.profile', 'uses' => 'Admin\CodespurController@storeProfile'));
	Route::get('user-chart', array('as' => 'user-chart', 'uses' => 'Admin\CodespurController@userChart'));

	Route::post('edit_profile/{slug}', array('as' => 'admin.user.update.profile', 'uses' => 'Admin\UserController@updateProfile'));

	Route::get('/point-settings', array('as' => 'admin.point.settings', 'uses' => 'Admin\WebsitesettingController@point_settings'));
	Route::post('/point-settings', array('as' => 'admin.point.settings', 'uses' => 'Admin\WebsitesettingController@point_settings_post'));

	Route::get('/customer-point-history/{userid?}', array('as' => 'admin.customer.point.history', 'uses' => 'Admin\CustomerPointsController@customer_point_history'));
	Route::get('/customer-point-history-data', array('as' => 'admin.customer.point.history.data', 'uses' => 'Admin\CustomerPointsController@customer_point_history_data'));
	
	
	Route::get('customer-points/exportExcel', array('as' => 'admin.customer.point.export', 'uses' => 'Admin\CustomerPointsController@exportExcel'));	
	
	Route::get('/customer-rewards', array('as' => 'admin.customer.rewards', 'uses' => 'Admin\CustomerPointsController@customer_rewards'));
	Route::get('/customer-rewards/add', array('as' => 'admin.customer.rewards.add', 'uses' => 'Admin\CustomerPointsController@customer_rewards_add'));
	Route::get('/customer-rewards-edit/{id}', array('as' => 'admin.customer.rewards.edit', 'uses' => 'Admin\CustomerPointsController@customer_rewards_add'));
	Route::post('/customer-rewards/add', array('as' => 'admin.customer.rewards.add', 'uses' => 'Admin\CustomerPointsController@customer_rewards_add_post'));
	Route::post('/customer-rewards-edit/{id}', array('as' => 'admin.customer.rewards.edit', 'uses' => 'Admin\CustomerPointsController@customer_rewards_add_post'));
	Route::get('/customer-rewards/{mng_id}/confirm-delete', array('as' => 'confirm-delete/reward_manager', 'uses' => 'Admin\CustomerPointsController@getModalDelete'));
	Route::get('/customer-rewards/delete/{mng_id}', array('as' => 'final-delete/admin_manager', 'uses' => 'Admin\CustomerPointsController@deleteRecord'));

	Route::get('info', array('as' => 'info', function () {
		return view('info');
	}));

	# Admin Manager Route
	Route::group(array('prefix' => 'reward-redemption-history'), function () {

		Route::get('/', array('as' => 'admin.reward-redemption-history', 'uses' => 'Admin\CustomerPointsController@reward_redemption_history'));
		Route::get('data', array('as' => 'reward-redemption-history.data', 'uses' => 'Admin\CustomerPointsController@reward_redemption_history_data'));

	});

	# Admin Manager Route
	Route::group(array('prefix' => 'admin_manager'), function () {
		Route::get('/', array('as' => 'admin_manager', 'uses' => 'Admin\ManagerController@index'));
		Route::get('data', array('as' => 'admin_manager.data', 'uses' => 'Admin\ManagerController@data'));
		Route::get('create', array('as' => 'admin.admin_manager.create', 'uses' => 'Admin\ManagerController@create'));
		Route::post('create', 'Admin\ManagerController@store');
		Route::get('edit/{mng_id}', array('as' => 'admin.admin_manager.edit', 'uses' => 'Admin\ManagerController@create'));
		Route::post('edit/{bcat_id}', array('as' => 'admin.admin_manager.edit', 'uses' => 'Admin\ManagerController@store'));
		Route::get('deletedmanager/{brand_id}', array('as' => 'delete/admin_manager', 'uses' => 'Admin\ManagerController@destroy'));
		Route::get('{mng_id}/confirm-delete', array('as' => 'confirm-delete/admin_manager', 'uses' => 'Admin\ManagerController@getModalDelete'));
	});

	//Routes
	//=================== User==================================//
	Route::group(array('prefix' => 'user'), function () {
		Route::get('/', array('as' => 'admin.user', 'uses' => 'Admin\UserController@index'));
		
		Route::get('/approved-customers', array('as' => 'admin.approve_customer', 'uses' => 'Admin\UserController@approve_customer'));
		Route::get('approved-customers-data', array('as' => 'admin.user.approvecustomerdata', 'uses' => 'Admin\UserController@approvecustomerdata'));
		Route::get('view-approved-customers/{id}', array('as' => 'user.approve_customer_view', 'uses' => 'Admin\UserController@approve_customer_view'));
		// Route::get('{id}/confirm-delete-approved-customers', array('as' => 'confirm-delete/approve_customer', 'uses' => 'Admin\UserController@approveCustomerDelete'));
		// Route::get('deletedapprovedcustomers/{id}', array('as' => 'delete/approve_customer', 'uses' => 'Admin\UserController@destroyapprovecustomer'));

		Route::get('/pending-customers', array('as' => 'admin.pending_customer', 'uses' => 'Admin\UserController@pending_customer'));
		Route::get('pending-customers-data', array('as' => 'admin.user.pendingcustomerdata', 'uses' => 'Admin\UserController@pendingcustomerdata'));
		Route::get('view-pending-customers/{id}', array('as' => 'user.pending_customer_view', 'uses' => 'Admin\UserController@pending_customer_view'));

		Route::get('/reject-customers', array('as' => 'admin.reject_customer', 'uses' => 'Admin\UserController@reject_customer'));
		Route::get('reject-customers-data', array('as' => 'admin.user.rejectcustomerdata', 'uses' => 'Admin\UserController@rejectcustomerdata'));
		Route::get('view-reject-customers/{id}', array('as' => 'user.reject_customer_view', 'uses' => 'Admin\UserController@reject_customer_view'));

		Route::get('/status-customers', array('as' => 'admin.status_customer', 'uses' => 'Admin\UserController@status_customer'));
		Route::get('status-customers-data', array('as' => 'admin.user.statuscustomerdata', 'uses' => 'Admin\UserController@statuscustomerdata'));
		Route::get('view-status-customers/{id}', array('as' => 'user.status_customer_view', 'uses' => 'Admin\UserController@status_customer_view'));

		Route::get('data', array('as' => 'admin.user.data', 'uses' => 'Admin\UserController@data'));
		Route::get('create', array('as' => 'create.user', 'uses' => 'Admin\UserController@create'));
		Route::post('create', array('as' => 'user.store', 'uses' => 'Admin\UserController@store'));
		Route::get('/users-trash', array('as' => 'users-trash', 'uses' => 'Admin\UserController@trashedUsers'));
		Route::get('edit/{mng_id}', array('as' => 'admin.user.edit', 'uses' => 'Admin\UserController@create'));
		Route::post('edit/{bcat_id}', array('as' => 'admin.user.edit', 'uses' => 'Admin\UserController@store'));
		Route::get('show/{id}', array('as' => 'user.show', 'uses' => 'Admin\UserController@show'));
		Route::get('deleteduser/{id}', array('as' => 'delete/admin_user', 'uses' => 'Admin\UserController@destroy'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/admin_user', 'uses' => 'Admin\UserController@getModalDelete'));
		Route::get('deleteduserdocument/{id}', array('as' => 'delete-document/admin_user', 'uses' => 'Admin\UserController@destroydocument'));
		Route::get('{id}/confirm-delete-document', array('as' => 'confirm-delete-document/admin_user', 'uses' => 'Admin\UserController@getModalDeleteDocument'));
		Route::get('deleteduserbankdetails/{id}', array('as' => 'delete-bank_details/admin_user', 'uses' => 'Admin\UserController@destroybankdetails'));
		Route::get('{id}/confirm-delete-bank-detail', array('as' => 'confirm-delete-bank-detail/admin_user', 'uses' => 'Admin\UserController@getModalDeleteBankdetail'));
		Route::get('deleted/{id}', array('as' => 'deleted-permanent/user', 'uses' => 'Admin\UserController@destroyPermanent'));
		Route::get('/permanent-delete/{id}', array('as' => 'permanent-delete/user', 'uses' => 'Admin\UserController@getModalPermanentDelete'));
		Route::get('/restore/{id}', array('as' => 'confirm-restore/user', 'uses' => 'Admin\UserController@getModalRestore'));
		Route::get('restored/{id}', array('as' => 'restore/user', 'uses' => 'Admin\UserController@restore'));
		Route::get('export-data', array('as' => 'export_user', 'uses' => 'Admin\UserController@export_user'));
		
		Route::get('rewards/{id}/{type}', array('as' => 'user.rewards', 'uses' => 'Admin\UserController@rewards'));
		Route::get('rewardedit/{id}', array('as' => 'admin.user.reward.edit', 'uses' => 'Admin\UserController@rewardEdit'));
		Route::post('rewardedit/{id}', array('as' => 'admin.user.reward.edit', 'uses' => 'Admin\UserController@rewardEditSave'));



		Route::get('rewardadd/{userid}', array('as' => 'admin.user.reward.add', 'uses' => 'Admin\UserController@rewardAdd'));
		Route::post('rewardadd/{userid}', array('as' => 'admin.user.reward.add', 'uses' => 'Admin\UserController@rewardAddSave'));	
		Route::post('couponcheck', array('as' => 'admin.user.coupon.check', 'uses' => 'Admin\UserController@couponCheck'));
		
							
		Route::get('rewardconfirm-delete/{id}', array('as' => 'admin.reward.confirm-delete', 'uses' => 'Admin\UserController@getRewardModalDelete'));
		Route::get('rewarddelete/{id}', array('as' => 'admin.reward.delete', 'uses' => 'Admin\UserController@getRewardDelete'));

		


		Route::get('agent/district/ajax/{countryCode}', array('as' => 'agent.district.ajaxx', 'uses' => 'Admin\UserController@getDistrictList'));
		Route::get('edit/agent/district/ajax/{countryCode}', array('as' => 'agent.district.ajaxx', 'uses' => 'Admin\UserController@getDistrictList'));
		Route::get('/confirm-active-inactive/{id}', array('as' => 'confirm.active.inactive.user', 'uses' => 'Admin\UserController@getModalActive_Inactive'));

		Route::get('/confirm-approve-pending/{id}', array('as' => 'confirm.approve.pending', 'uses' => 'Admin\UserController@getModalApprovePending'));
		Route::get('approve-pending/{id}', array('as' => 'approve-pending', 'uses' => 'Admin\UserController@approve_pending'));

		Route::get('/confirm-approve-reject/{id}', array('as' => 'confirm.approve.reject', 'uses' => 'Admin\UserController@getModalApprovereject'));
		Route::get('approve-reject/{id}', array('as' => 'approve-reject', 'uses' => 'Admin\UserController@approve_reject'));

		Route::get('/block/{id}', array('as' => 'confirm.approve.status', 'uses' => 'Admin\UserController@getModalApprovestatus'));
		Route::get('block-confirmed/{id}', array('as' => 'approve-status', 'uses' => 'Admin\UserController@approve_status'));
		
		Route::get('active-inactive/{id}', array('as' => 'active-inactive.user', 'uses' => 'Admin\UserController@Active_inactive'));
		Route::get('/exportExcel', array('as' => 'export.user', 'uses' => 'Admin\UserController@exportExcel'));
		Route::get('get-user', array('as' => 'user.get-user', 'uses' => 'Admin\UserController@getUser'));
		Route::get('step-update/{id}', array('as' => 'user.step-update', 'uses' => 'Admin\UserController@stepUpdate'));
		Route::post('step-update-success', array('as' => 'user.step-update-success', 'uses' => 'Admin\UserController@stepUpdateSuccess'));

	});
	
	//=================== Dealer==================================//
	// Route::group(array('prefix' => 'dealer'), function () {
	// 	Route::get('/', array('as' => 'admin.dealer', 'uses' => 'Admin\DealerController@index'));
	// 	Route::get('data', array('as' => 'admin.dealer.data', 'uses' => 'Admin\DealerController@data'));
	// 	Route::get('create', array('as' => 'create.dealer', 'uses' => 'Admin\DealerController@create'));
	// 	Route::post('create', array('as' => 'dealer.store', 'uses' => 'Admin\DealerController@store'));
		
		
	// 	Route::get('/dealers-trash', array('as' => 'dealers-trash', 'uses' => 'Admin\DealerController@trasheddealers'));
		
	// 	Route::get('edit/{mng_id}', array('as' => 'admin.dealer.edit', 'uses' => 'Admin\DealerController@create'));
	// 	Route::post('edit/{bcat_id}', array('as' => 'admin.dealer.edit', 'uses' => 'Admin\DealerController@store'));
	// 	Route::get('show/{id}', array('as' => 'dealer.show', 'uses' => 'Admin\DealerController@show'));
		
		
		
	// 	Route::get('deleted-dealer/{id}', array('as' => 'delete/dealer', 'uses' => 'Admin\DealerController@destroy'));
	// 	Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/dealer', 'uses' => 'Admin\DealerController@getModalDelete'));
	// 	Route::get('assign-report/{id}', array('as' => 'admin.dealer.assign-report', 'uses' => 'Admin\DealerController@assignReport'));
		

	// });	
	
		//===================================== Notifications Manager Route Path Start ===========//
	Route::group(array('prefix' => 'notifications'), function () {
		Route::get('/', array('as' => 'admin.notifications', 'uses' => 'Admin\NotificationController@index'));
		Route::get('data', array('as' => 'admin.notifications.data', 'uses' => 'Admin\NotificationController@data'));
		Route::get('create', array('as' => 'create.notifications', 'uses' => 'Admin\NotificationController@create'));
		Route::post('create', array('as' => 'store.notifications', 'uses' => 'Admin\NotificationController@store'));
		Route::get('show/{Id}', 'Admin\NotificationController@show');
		Route::get('edit/{ID}', array('as' => 'update.notifications', 'uses' => 'Admin\NotificationController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.notifications', 'uses' => 'Admin\NotificationController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.notifications', 'uses' => 'Admin\NotificationController@getModalDelete'));
		Route::get('destroy/{id}', array('as' => 'delete.notifications', 'uses' => 'Admin\NotificationController@destroy'));
	});


	//===================================== Message Route Path Start ===========//
	Route::group(array('prefix' => 'message'), function () {
		Route::get('/', array('as' => 'admin.message', 'uses' => 'Admin\MessageController@index'));
		Route::get('data', array('as' => 'admin.message.data', 'uses' => 'Admin\MessageController@data'));
		Route::get('create', array('as' => 'create.message', 'uses' => 'Admin\MessageController@create'));
		Route::post('create', array('as' => 'store.message', 'uses' => 'Admin\MessageController@store'));
		Route::get('show/{Id}', array('as' => 'show.message', 'uses' => 'Admin\MessageController@show'));
		Route::post('test-send/{id}', array('as' => 'message.test-send', 'uses' => 'Admin\MessageController@testSend'));
		Route::get('whatsapp/templates', array('as' => 'message.whatsapp.templates', 'uses' => 'Admin\MessageController@whatsappTemplates'));
		Route::post('whatsapp/preview', array('as' => 'message.whatsapp.preview', 'uses' => 'Admin\MessageController@whatsappPreview'));
		Route::get('whatsapp/sync', array('as' => 'message.whatsapp.sync', 'uses' => 'Admin\MessageController@whatsappSync'));
		Route::get('whatsapp-logs', array('as' => 'admin.whatsapp.logs', 'uses' => 'Admin\WhatsappLogController@index'));
		Route::post('whatsapp-logs/clear', array('as' => 'admin.whatsapp.logs.clear', 'uses' => 'Admin\WhatsappLogController@clear'));
		Route::get('edit/{ID}', array('as' => 'update.message', 'uses' => 'Admin\MessageController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.message', 'uses' => 'Admin\MessageController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.message', 'uses' => 'Admin\MessageController@getModalDelete'));
		Route::get('destroy/{id}', array('as' => 'delete.message', 'uses' => 'Admin\MessageController@destroy'));
		Route::get('validate-otp', array('as' => 'validate_otp', 'uses' => 'Admin\MessageController@validateotp'));
	});	
	
	//===================================== Dealers Route Path Start ===========//
	Route::group(array('prefix' => 'dealer'), function () {
		Route::get('/', array('as' => 'admin.dealer', 'uses' => 'Admin\DealerController@index'));
		Route::get('data', array('as' => 'admin.dealer.data', 'uses' => 'Admin\DealerController@data'));
		Route::get('create', array('as' => 'create.dealer', 'uses' => 'Admin\DealerController@create'));
		Route::post('create/{id?}', array('as' => 'store.dealer', 'uses' => 'Admin\DealerController@store'));
		Route::get('show/{Id}', 'Admin\DealerController@show');
		Route::get('edit/{ID}', array('as' => 'update.dealer', 'uses' => 'Admin\DealerController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.dealer', 'uses' => 'Admin\DealerController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.dealer', 'uses' => 'Admin\DealerController@getModalDelete'));
		Route::get('destroy/{id}', array('as' => 'delete.dealer', 'uses' => 'Admin\DealerController@destroy'));
		Route::post('get-district', array('as' => 'dealer.get_district', 'uses' => 'Admin\DealerController@get_district'));
	});	

	//===================================== SalesOfficer Route Path Start ===========//
	Route::group(array('prefix' => 'sales_officer'), function () {
		Route::get('/', array('as' => 'admin.sales_officer', 'uses' => 'Admin\SalesOfficerController@index'));
		Route::get('data', array('as' => 'admin.sales_officer.data', 'uses' => 'Admin\SalesOfficerController@data'));
		Route::get('create', array('as' => 'create.sales_officer', 'uses' => 'Admin\SalesOfficerController@create'));
		Route::post('create/{id?}', array('as' => 'store.sales_officer', 'uses' => 'Admin\SalesOfficerController@store'));
		Route::get('show/{Id}', 'Admin\SalesOfficerController@show');
		Route::get('edit/{ID}', array('as' => 'update.sales_officer', 'uses' => 'Admin\SalesOfficerController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.sales_officer', 'uses' => 'Admin\SalesOfficerController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.sales_officer', 'uses' => 'Admin\SalesOfficerController@getModalDelete'));
		Route::get('destroy/{id}', array('as' => 'delete.sales_officer', 'uses' => 'Admin\SalesOfficerController@destroy'));
		Route::get('all-dealers/{id}', array('as' => 'all_dealers', 'uses' => 'Admin\SalesOfficerController@all_dealers'));
	});	
	
		//=====================================Wallet Transactions Manager Route Path Start =======================================//
	Route::group(array('prefix' => 'wallet-transaction'), function () {
		Route::get('/', array('as' => 'admin.wallet-transaction', 'uses' => 'Admin\WalletTransactionController@index'));
		Route::get('data', array('as' => 'admin.wallet-transaction.data', 'uses' => 'Admin\WalletTransactionController@data'));
		Route::get('show/{id}', array('as' => 'show.wallet-transaction', 'uses' => 'Admin\WalletTransactionController@show'));
		Route::get('/exportExcel', array('as' => 'export.wallet-transaction', 'uses' => 'Admin\WalletTransactionController@exportExcel'));
	});



	//================================== Tour Location Route ======================================//
	Route::group(array('prefix' => 'state'), function () {
		Route::get('/', array('as' => 'admin.tourlocation', 'uses' => 'Admin\TourLocationController@index'));
		Route::get('data', array('as' => 'admin.tourlocation.data', 'uses' => 'Admin\TourLocationController@data'));
		Route::get('create', array('as' => 'create.tourlocation', 'uses' => 'Admin\TourLocationController@create'));
		Route::post('create', array('as' => 'store.tourlocation', 'uses' => 'Admin\TourLocationController@store'));
		Route::get('show/{id}', 'Admin\TourLocationController@show');
		Route::get('edit/{id}', array('as' => 'update.tourlocation', 'uses' => 'Admin\TourLocationController@create'));
		Route::post('edit/{id}', array('as' => 'updated.tourlocation', 'uses' => 'Admin\TourLocationController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.tourlocation', 'uses' => 'Admin\TourLocationController@getModalDelete'));
		Route::get('destroy/{id}', array('as' => 'delete.tourlocation', 'uses' => 'Admin\TourLocationController@destroy'));
		
	});
	//================================== Tour Location/District Route ======================================//
	Route::group(array('prefix' => 'district'), function () {
		Route::get('/', array('as' => 'admin.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@index'));
		Route::get('data', array('as' => 'admin.tourlocationdistrict.data', 'uses' => 'Admin\TourLocationDistrictController@data'));
		Route::get('create', array('as' => 'create.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@create'));
		Route::post('create', array('as' => 'store.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@store'));
		Route::get('show/{id}', 'Admin\TourLocationDistrictController@show');
		Route::get('edit/{id}', array('as' => 'update.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@create'));
		Route::post('edit/{id}', array('as' => 'updated.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@getModalDelete'));
		Route::get('destroy/{id}', array('as' => 'delete.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@destroy'));
		Route::get('confirm-status/{id}', array('as' => 'confirm-status', 'uses' => 'Admin\TourLocationDistrictController@chage_status_model'));
		Route::get('change-status/{id}', array('as' => 'change-status.tourlocationdistrict', 'uses' => 'Admin\TourLocationDistrictController@chage_status'));
	});

	//================================== Pincode Route ======================================//
	Route::group(array('prefix' => 'pincode'), function () {
		Route::get('/', array('as' => 'admin.pincode', 'uses' => 'Admin\PincodeController@index'));
		Route::get('data', array('as' => 'admin.pincode.data', 'uses' => 'Admin\PincodeController@data'));
		Route::get('create', array('as' => 'create.pincode', 'uses' => 'Admin\PincodeController@create'));
		Route::post('create', array('as' => 'store.pincode', 'uses' => 'Admin\PincodeController@store'));
		Route::get('show/{id}', 'Admin\PincodeController@show');
		Route::get('edit/{id}', array('as' => 'update.pincode', 'uses' => 'Admin\PincodeController@create'));
		Route::post('edit/{id}', array('as' => 'updated.pincode', 'uses' => 'Admin\PincodeController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.pincode', 'uses' => 'Admin\PincodeController@getModalDelete'));
		Route::get('destroy/{id}', array('as' => 'delete.pincode', 'uses' => 'Admin\PincodeController@destroy'));
		
	});
	
	//=======================Orders route ==================================
	Route::group(array('prefix' => 'orders'), function () {

		Route::get('/confirm-order/{id}', array('as' => 'admin.order.confirm', 'uses' => 'Admin\OrdersController@orderConfirmModal'));
		Route::get('/confirm-order-action/{id}', array('as' => 'admin.order.confirm.action', 'uses' => 'Admin\OrdersController@orderConfirmAction'));

		Route::get('/update-shipment/{id}', array('as' => 'admin.order.shipment', 'uses' => 'Admin\OrdersController@shipment_update_page'));
		Route::post('/update-shipment/{id}', array('as' => 'admin.order.shipment.post', 'uses' => 'Admin\OrdersController@shipment_update_post'));

		Route::get('completed-order', array('as' => 'admin.completed-order', 'uses' => 'Admin\OrdersController@completedIndex'));
		Route::get('/completeddata', array('as' => 'admin.completed-order.data', 'uses' => 'Admin\OrdersController@Completeddata'));

		Route::get('/shipment-detail/{id}', array('as' => 'shipment-detail',
			'uses' => 'Admin\OrdersController@getModalShipment'));
		Route::post('/shipment-detail/{id}', array('as' => 'shipment-detail-add',
			'uses' => 'Admin\OrdersController@shipmentDetailAdd'));
		Route::get('/shipment-view/{id}', array('as' => 'shipment-detail-view',
			'uses' => 'Admin\OrdersController@shipmentDetailView'));

		//make cod orders to paid
		Route::get('/cod-to-paid/{order_id}', array('as' => 'cod-to-paid',
			'uses' => 'Admin\OrdersController@confirm_cod_paid'));
		Route::get('/make-cod-to-paid/{order_id}', array('as' => 'order.make-cod-to-paid',
			'uses' => 'Admin\OrdersController@make_cod_paid'));
		//new
		Route::get('all-orders', array('as' => 'orders.all-orders', 'uses' => 'Admin\OrdersController@show_all_orders'));
		Route::get('all-orders/data', array('as' => 'orders.all-orders.data', 'uses' => 'Admin\OrdersController@all_orders_data'));
		Route::get('/show/{id}', array('as' => 'order.show', 'uses' => 'Admin\OrdersController@view'));

		Route::get('/invoice-print/{id}', array('as' => 'invoice-print', 'uses' => 'Admin\OrdersController@InvoicePrint'));

		Route::get('buyerinfo/{id}', array('as' => 'view-user', 'uses' => 'Admin\OrdersController@buyerinfo'));

	});

	//===================================== Chat =================//
	Route::group(array('prefix' => 'chat'), function () {
		Route::get('/', array('as' => 'admin.chat', 'uses' => 'Admin\ChatController@index'));
		Route::get('/data', array('as' => 'admin.chat.data', 'uses' => 'Admin\ChatController@data'));
		Route::get('add', array('as' => 'reply.add', 'uses' => 'Admin\ChatController@store'));
		Route::post('add', array('as' => 'reply.store', 'uses' => 'Admin\ChatController@store'));
		Route::get('/show/{id}', array('as' => 'admin.chat.show', 'uses' => 'Admin\ChatController@view'));
		Route::get('deleted-chat/{id}', array('as' => 'delete/chat', 'uses' => 'Admin\ChatController@destroy'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/chat', 'uses' => 'Admin\ChatController@getModalDelete'));
	});

	//=================== Website Setting==================================//

	Route::group(array('prefix' => 'site-setting'), function () {
		Route::get('/', array('as' => 'site-setting', 'uses' => 'Admin\WebsitesettingController@siteSetting'));
		Route::post('/', array('as' => 'site-setting', 'uses' => 'Admin\WebsitesettingController@storeSiteSetting'));
	});
	Route::group(array('prefix' => 'message-setting'), function () {
		Route::get('/', array('as' => 'message-setting', 'uses' => 'Admin\WebsitesettingController@messageSetting'));
		Route::post('/', array('as' => 'message-setting', 'uses' => 'Admin\WebsitesettingController@storeMessageSetting'));
	});
	// WhatsApp configuration moved into the WhatsApp tab of Messages.
	Route::get('whatsapp-configuration/{any?}', function () {
		return redirect()->route('admin.message');
	})->where('any', '.*');
	Route::group(array('prefix' => 'bonus-points-setting'), function () {
		Route::get('/', array('as' => 'bonus-points-setting', 'uses' => 'Admin\WebsitesettingController@bonusPointSetting'));
		Route::get('data', array('as' => 'admin.bonus-points-setting.data', 'uses' => 'Admin\WebsitesettingController@bonusPointSettingdata'));
		// Route::post('/', array('as' => 'message-setting', 'uses' => 'Admin\WebsitesettingController@storeMessageSetting'));
	});

	Route::group(array('prefix' => 'social-setting'), function () {
		Route::get('/', array('as' => 'social-setting',
			'uses' => 'Admin\WebsitesettingController@social_setting'));
		Route::post('/', array('as' => 'social-setting',
			'uses' => 'Admin\WebsitesettingController@social_setting'));
	});
	Route::group(array('prefix' => 'store-setting'), function () {
		Route::get('/', array('as' => 'store-setting',
			'uses' => 'Admin\WebsitesettingController@store_setting'));
		Route::post('/', array('as' => 'store-setting',
			'uses' => 'Admin\WebsitesettingController@store_setting'));
	});

	Route::group(array('prefix' => 'email-setting'), function () {
		Route::get('/', array('as' => 'email-setting', 'uses' => 'Admin\WebsitesettingController@emailSetting'));
		Route::POST('/', array('as' => 'email-setting', 'uses' => 'Admin\WebsitesettingController@storeEmailSetting'));
	});

	Route::group(array('prefix' => 'payment_setting'), function () {
		Route::get('/', array('as' => 'payment_setting', 'uses' => 'Admin\WebsitesettingController@paymentSetting'));
		Route::POST('/', array('as' => 'store.payment_setting', 'uses' => 'Admin\WebsitesettingController@storePaymentSetting'));
	});
	Route::match(array('GET', 'POST'), "/seo-setting", array('uses' => 'Admin\WebsitesettingController@seo_setting', 'as' => 'seo_setting'));

	Route::match(array('GET', 'POST'), "/social-setting", array('uses' => 'Admin\WebsitesettingController@social_setting', 'as' => 'social_setting'));
	//seo setting
	Route::match(array('GET', 'POST'), "/seo-setting", array('uses' => 'Admin\WebsitesettingController@seo_setting',
		'as' => 'seo_setting'));

	//========================= Utilities ==================================//
	#Admin Logs
	Route::group(array('prefix' => 'admin_logs'), function () {
		Route::get('/', array('as' => 'admin_logs', 'uses' => 'Admin\LogsController@adminLogIndex'));
		Route::get('data', array('as' => 'admin_logs.data', 'uses' => 'Admin\LogsController@adminLogData'));
		Route::get('admin_log_confirm_delete/{slug}', array('as' => 'admin_logs.delete.comfirm', 'uses' => 'Admin\LogsController@getModalDelete'));
		Route::get('clear_admin_log/{slug}', array('as' => 'admin_logs.delete', 'uses' => 'Admin\LogsController@deleteRecord'));
	});

	#User Logs
	Route::group(array('prefix' => 'user_logs'), function () {
		Route::get('/', array('as' => 'user_logs', 'uses' => 'Admin\LogsController@userLogIndex'));
		Route::get('data', array('as' => 'user_logs.data', 'uses' => 'Admin\LogsController@userLogData'));
		Route::get('user_log_confirm_delete/{slug}', array('as' => 'user_logs.delete.comfirm', 'uses' => 'Admin\LogsController@getModalDelete'));
		Route::get('clear_user_log/{slug}', array('as' => 'user_logs.delete', 'uses' => 'Admin\LogsController@deleteRecord'));
	});

	//======================== Static Page Manager Route ===========================//
	Route::group(array('prefix' => 'static_pages'), function () {
		Route::get('/', array('as' => 'admin.page', 'uses' => 'Admin\PageController@index'));
		Route::get('/data', array('as' => 'admin.page.data', 'uses' => 'Admin\PageController@data'));
		Route::get('/create', array('as' => 'page.create', 'uses' => 'Admin\PageController@create'));
		Route::post('/create', array('as' => 'admin.page.store', 'uses' => 'Admin\PageController@store'));
		Route::get('edit/{pg_id}', array('as' => 'admin.page.edit', 'uses' => 'Admin\PageController@create'));
		Route::post('edit/{pg_id}', array('as' => 'admin.page.edit.store', 'uses' => 'Admin\PageController@store'));
		Route::get('show/{id}', array('as' => 'admin.page.show', 'uses' => 'Admin\PageController@view'));
		Route::get('deletedpage/{page_id}', array('as' => 'delete/page', 'uses' => 'Admin\PageController@destroy'));
		Route::get('confirm-delete/{page_id}', array('as' => 'confirm-delete/page', 'uses' => 'Admin\PageController@getModalDelete'));
	});

	//====================Generate report=========================//
	Route::group(array('prefix' => 'generate_report'), function () {
		Route::get('/', array('as' => 'generate_report', 'uses' => 'Admin\GenerateReportController@index'));
		Route::post('/', array('as' => 'generate_report', 'uses' => 'Admin\GenerateReportController@filter'));
		Route::get('/emp_per_company', array('as' => 'generate_report/emp_per_company', 'uses' => 'Admin\GenerateReportController@employeePerCompany'));
		Route::get('/months_per_year', array('as' => 'generate_report/months_per_year', 'uses' => 'Admin\GenerateReportController@monthsPerYear'));
		Route::get('/months_per_year1', array('as' => 'generate_report/months_per_year1', 'uses' => 'Admin\GenerateReportController@monthsPerYear1'));
		Route::get('print_report/{year}/{month}', array('as' => 'print_report', 'uses' => 'Admin\GenerateReportController@PrintIt'));

	});

	//===================================== Category Manager Route Path Start =======================================//
	Route::group(array('prefix' => 'category'), function () {
		Route::get('/', array('as' => 'admin.category', 'uses' => 'Admin\CategoryController@index'));

		Route::get('create', array('as' => 'create.category', 'uses' => 'Admin\CategoryController@create'));
		Route::post('create', array('as' => 'store.category', 'uses' => 'Admin\CategoryController@store'));

		Route::get('sub-list/{parent_menu}', array('as' => 'data.category', 'uses' => 'Admin\CategoryController@subindex'));
		Route::get('sub-data/{parent_menu}', array('as' => 'admin.category.sub-data', 'uses' => 'Admin\CategoryController@subdata'));

		Route::get('create_submenu/{parent_menu}', array('as' => 'create_submenus.header', 'uses' => 'Admin\CategoryController@createsubmenu'));
		Route::post('create_submenu/{parent_menu}', array('as' => 'store.submenu', 'uses' => 'Admin\CategoryController@storeSubMenu'));

		Route::get('data', array('as' => 'admin.category.data', 'uses' => 'Admin\CategoryController@data'));
		Route::get('set-order', array('as' => 'setorder.category', 'uses' => 'Admin\CategoryController@setorderlist'));
		Route::post('setorderlist', array('as' => 'setorderlist/category', 'uses' => 'CategoryController@setOrder'));

		Route::get('edit/{ID}', array('as' => 'update.category', 'uses' => 'Admin\CategoryController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.category', 'uses' => 'Admin\CategoryController@store'));

		Route::get('edit/sub/{parent_menu}/{ID}', array('as' => 'update.submenus', 'uses' => 'Admin\CategoryController@createsubmenu'));
		Route::post('edit/sub/{parent_menu}/{ID}', array('as' => 'edit.submenus', 'uses' => 'Admin\CategoryController@storeSubMenu'));

		Route::get('deletedcategory/{banner_id}', array('as' => 'delete/category', 'uses' => 'Admin\CategoryController@destroy'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/category', 'uses' => 'Admin\CategoryController@getModalDelete'));
	});

	//===================================== Header Manager Route Path Start =======================================//
	Route::group(array('prefix' => 'header'), function () {
		Route::get('/', array('as' => 'admin.header', 'uses' => 'Admin\HeaderAdminController@index'));
		Route::get('create', array('as' => 'create.header', 'uses' => 'Admin\HeaderAdminController@create'));
		Route::get('createmain/{ID}', array('as' => 'createmain.header', 'uses' => 'Admin\HeaderAdminController@createmain'));
		Route::post('createmain', array('as' => 'store.header', 'uses' => 'Admin\HeaderAdminController@store'));
		Route::get('sublist', array('as' => 'create.sublist', 'uses' => 'Admin\HeaderAdminController@submenu'));
		Route::post('create', array('as' => 'store.header', 'uses' => 'Admin\HeaderAdminController@store'));
		Route::get('create_submenu/{parent_menu}', array('as' => 'create_submenus.header', 'uses' => 'Admin\HeaderAdminController@createsubmenu'));
		Route::post('create_submenu/{parent_menu}', array('as' => 'store.submenu', 'uses' => 'Admin\HeaderAdminController@storeSubMenu'));
		Route::get('data', array('as' => 'admin.header.data', 'uses' => 'Admin\HeaderAdminController@data'));
		Route::get('subdata', array('as' => 'admin.header.subdata', 'uses' => 'Admin\HeaderAdminController@subdata'));
		Route::get('set-order', array('as' => 'setorder.header', 'uses' => 'Admin\HeaderAdminController@setorderlist'));
		Route::post('setorderlist', array('as' => 'setorderlist/headermenu', 'uses' => 'HeaderAdminController@setOrder'));
		Route::get('edit/{ID}', array('as' => 'update.header', 'uses' => 'Admin\HeaderAdminController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.header', 'uses' => 'Admin\HeaderAdminController@store'));
		Route::get('edit/sub/{parent_menu}/{ID}', array('as' => 'update.submenus', 'uses' => 'Admin\HeaderAdminController@createsubmenu'));
		Route::post('edit/sub/{parent_menu}/{ID}', array('as' => 'edit.submenus', 'uses' => 'Admin\HeaderAdminController@storeSubMenu'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/header', 'uses' => 'Admin\HeaderAdminController@getModalFinalDelete'));
		Route::get('finaldeleted/{id}', array('as' => 'finaldelete.header', 'uses' => 'Admin\HeaderAdminController@permanentDelete'));
	});
	//===================================== Footer Manager Route Path Start =======================================//
	Route::group(array('prefix' => 'footer'), function () {
		Route::get('/', array('as' => 'admin.footer', 'uses' => 'Admin\FooterAdminController@index'));
		Route::get('create', array('as' => 'create.footer', 'uses' => 'Admin\FooterAdminController@create'));
		Route::get('data', array('as' => 'admin.footer.data', 'uses' => 'Admin\FooterAdminController@data'));
		Route::get('fff/1', array('as' => 'admin.footer.data', 'uses' => 'Admin\FooterAdminController@data'));
		Route::post('create', array('as' => 'store.footer', 'uses' => 'Admin\FooterAdminController@store'));
		Route::get('edit/{ID}', array('as' => 'update.footer', 'uses' => 'Admin\FooterAdminController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.footer', 'uses' => 'Admin\FooterAdminController@store'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/footer', 'uses' => 'Admin\FooterAdminController@getModalFinalDelete'));
		Route::get('finaldeleted/{id}', array('as' => 'finaldelete.footer', 'uses' => 'Admin\FooterAdminController@permanentDelete'));
	});

	//===================================== Email Template Manager Route Path Start =======================================//
	Route::group(array('prefix' => 'emailtemplate'), function () {
		Route::get('/', array('as' => 'admin/emailtemplate', 'uses' => 'Admin\EmailTemplateController@index'));
		Route::get('create', array('as' => 'admin/create/emailtemplate', 'uses' => 'Admin\EmailTemplateController@edit'));
		Route::post('create', array('as' => 'store.create', 'uses' => 'Admin\EmailTemplateController@editPost'));
		Route::get('data', array('as' => 'admin.emailtemplate.data', 'uses' => 'Admin\EmailTemplateController@data'));
		Route::get('view/{em_tm_id}', array('as' => 'emailtemplate/view', 'uses' => 'Admin\EmailTemplateController@viewRecord'));
		Route::get('edit/{em_tm_id}', array('as' => 'update/emailtemplate', 'uses' => 'Admin\EmailTemplateController@edit'));
		Route::post('edit/{em_tm_id}', array('as' => 'updated/emailtemplate', 'uses' => 'Admin\EmailTemplateController@editPost'));
	});

	//==================== BAN IP Routes Path ===========================//
	Route::group(array('prefix' => 'banip'), function () {
		Route::get('/', array('as' => 'banip', 'uses' => 'Admin\BanipController@index'));
		Route::get('data', array('as' => 'admin.banip.data', 'uses' => 'Admin\BanipController@data'));
		Route::get('create', array('as' => 'admin.banip.create', 'uses' => 'Admin\BanipController@create'));
		Route::post('create', 'Admin\BanipController@store');
		Route::get('deletedbanip', array('as' => 'banip/deletedbanip', 'uses' => 'Admin\BanipController@listDeletedBanip'));
		Route::get('deletedbanipdata', array('as' => 'admin.banip.deleteddata', 'uses' => 'Admin\BanipController@listDeletedData'));
		Route::get('{Id}/confirm-delete', array('as' => 'confirm-delete/banip', 'uses' => 'Admin\BanipController@getModalDelete'));
		Route::get('deletedbanip/{Id}', array('as' => 'delete/banip', 'uses' => 'Admin\BanipController@destory'));
		Route::get('{Id}/confirm-restore', array('as' => 'confirm-restore/banip', 'uses' => 'Admin\BanipController@getModalRestore'));
		Route::get('restoresubs/{Id}', array('as' => 'restore/banip', 'uses' => 'Admin\BanipController@restoreDeletedBanip'));
		Route::get('{pro_id}/final-delete', array('as' => 'final-delete/banip', 'uses' => 'Admin\BanipController@getModalFinalDelete'));
		Route::get('finaldeleted/{pro_id}', array('as' => 'finaldelete/banip', 'uses' => 'Admin\BanipController@permanentDelete'));

	});

	//====================================== FAQ ===================//
	Route::group(array('prefix' => 'faq'), function () {
		Route::get('/', array('as' => 'admin.faq', 'uses' => 'Admin\FAQController@index'));
		Route::get('/data', array('as' => 'admin.faq.data', 'uses' => 'Admin\FAQController@data'));
		Route::get('/create', array('as' => 'faq.create', 'uses' => 'Admin\FAQController@create'));
		Route::post('/create', array('as' => 'admin.faq.store', 'uses' => 'Admin\FAQController@store'));
		Route::get('/show/{id}', array('as' => 'admin.faq.show', 'uses' => 'Admin\FAQController@view'));
		Route::get('edit/{id}', array('as' => 'admin.faq.edit', 'uses' => 'Admin\FAQController@create'));
		Route::post('edit/{id}', array('as' => 'admin.faq.edit.store', 'uses' => 'Admin\FAQController@store'));
		Route::get('deleted-faq/{id}', array('as' => 'delete/faq', 'uses' => 'Admin\FAQController@destroy'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/faq', 'uses' => 'Admin\FAQController@getModalDelete'));
	});

	//====================================== Product Prices ===================//
	// Route::group(array('prefix' => 'product-price'), function () {
	// 	Route::get('/', array('as' => 'admin.product-price', 'uses' => 'Admin\ProductPriceController@index'));
	// 	Route::get('/data', array('as' => 'admin.product-price.data', 'uses' => 'Admin\ProductPriceController@data'));
	// 	Route::get('/create', array('as' => 'product-price.create', 'uses' => 'Admin\ProductPriceController@create'));
	// 	Route::post('/create', array('as' => 'admin.product-price.store',
	// 		'uses' => 'Admin\ProductPriceController@store'));
	// 	Route::get('/show/{id}', array('as' => 'admin.product-price.show', 'uses' => 'Admin\ProductPriceController@view'));
	// 	Route::get('edit/{id}', array('as' => 'admin.product-price.edit', 'uses' => 'Admin\ProductPriceController@create'));
	// 	Route::post('edit/{id}', array('as' => 'admin.product-price.edit.store',
	// 		'uses' => 'Admin\ProductPriceController@store'));
	// 	Route::get('deleted-product-price/{id}', array('as' => 'delete/product-price',
	// 		'uses' => 'Admin\ProductPriceController@destroy'));
	// 	Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/product-price',
	// 		'uses' => 'Admin\ProductPriceController@getModalDelete'));
	// });

	//====================================== home-page-content ===================//
	// Route::group(array('prefix' => 'home-page-content'), function () {
	// 	Route::get('/', array('as' => 'admin.home-page-content', 'uses' => 'Admin\HomePageContentController@index'));
	// 	Route::get('/data', array('as' => 'admin.home-page-content.data', 'uses' => 'Admin\HomePageContentController@data'));
	// 	Route::get('/create', array('as' => 'home-page-content.create', 'uses' => 'Admin\HomePageContentController@create'));
	// 	Route::post('/update/{id}', array('as' => 'updated.home-page-content', 'uses' => 'Admin\HomePageContentController@store'));
	// 	Route::post('/create', array('as' => 'store.home-page-content', 'uses' => 'Admin\HomePageContentController@store'));

	// 	Route::get('/show/{id}', array('as' => 'admin.home-page-content.show', 'uses' => 'Admin\HomePageContentController@view'));
	// 	Route::get('edit/{id}', array('as' => 'admin.home-page-content.edit', 'uses' => 'Admin\HomePageContentController@create'));

	// 	Route::get('deleted/{id}', array('as' => 'deleted.home-page-content',
	// 		'uses' => 'Admin\HomePageContentController@destroy'));
	// 	Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/home-page-content', 'uses' => 'Admin\HomePageContentController@getModalDelete'));
	// });

	//===================================== Socially Conscious ========================//
	// Route::group(array('prefix' => 'socially-conscious'), function () {
	// 	Route::get('/', array('as' => 'admin.socially-conscious', 'uses' => 'Admin\SociallyConciousController@index'));
	// 	Route::get('/data', array('as' => 'admin.socially-conscious.data', 'uses' => 'Admin\SociallyConciousController@data'));
	// 	Route::get('/create', array('as' => 'socially-conscious.create', 'uses' => 'Admin\SociallyConciousController@create'));
	// 	Route::post('/create', array('as' => 'admin.socially-conscious.store', 'uses' => 'Admin\SociallyConciousController@store'));

	// 	Route::get('edit/{id}', array('as' => 'admin.socially-conscious.edit', 'uses' => 'Admin\SociallyConciousController@create'));
	// 	Route::post('edit/{id}', array('as' => 'admin.socially-conscious.edit.store', 'uses' => 'Admin\SociallyConciousController@store'));
	// 	Route::get('deleted/{id}', array('as' => 'delete/socially-conscious', 'uses' => 'Admin\SociallyConciousController@destroy'));
	// 	Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/socially-conscious',
	// 										'uses' => 'Admin\SociallyConciousController@getModalDelete'));
	// 	Route::get('show/{id}', array('as' => 'show/socially-conscious', 'uses' => 'Admin\SociallyConciousController@view'));
	// });

	//===================================== About Us ========================//
	// Route::group(array('prefix' => 'about-us'), function () {
	// 	Route::get('/', array('as' => 'admin.about-us', 'uses' => 'Admin\AboutUsController@index'));
	// 	Route::get('/data', array('as' => 'admin.about-us.data', 'uses' => 'Admin\AboutUsController@data'));
	// 	Route::get('/create', array('as' => 'about-us.create', 'uses' => 'Admin\AboutUsController@create'));
	// 	Route::post('/create', array('as' => 'admin.about-us.store', 'uses' => 'Admin\AboutUsController@store'));

	// 	Route::get('edit/{id}', array('as' => 'admin.about-us.edit', 'uses' => 'Admin\AboutUsController@create'));
	// 	Route::post('edit/{id}', array('as' => 'admin.about-us.edit.store', 'uses' => 'Admin\AboutUsController@store'));
	// 	Route::get('deleted/{id}', array('as' => 'delete/about-us', 'uses' => 'Admin\AboutUsController@destroy'));
	// 	Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/about-us',
	// 		'uses' => 'Admin\AboutUsController@getModalDelete'));
	// 	Route::get('show/{id}', array('as' => 'show/about-us', 'uses' => 'Admin\AboutUsController@view'));
	// });

	//===================================== banners ========================//
	Route::group(array('prefix' => 'page-banner'), function () {
		Route::get('/', array('as' => 'admin.page-banner', 'uses' => 'Admin\BannerController@index'));
		Route::get('/data', array('as' => 'admin.page-banner.data', 'uses' => 'Admin\BannerController@data'));
		Route::get('/create', array('as' => 'page-banner.create', 'uses' => 'Admin\BannerController@create'));
		Route::post('/create', array('as' => 'admin.page-banner.store', 'uses' => 'Admin\BannerController@store'));

		Route::get('edit/{id}', array('as' => 'admin.page-banner.edit', 'uses' => 'Admin\BannerController@create'));
		Route::post('edit/{id}', array('as' => 'admin.page-banner.edit.store', 'uses' => 'Admin\BannerController@store'));
		Route::get('deleted/{id}', array('as' => 'delete/page-banner', 'uses' => 'Admin\BannerController@destroy'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/page-banner',
			'uses' => 'Admin\BannerController@getModalDelete'));
		Route::get('show/{id}', array('as' => 'show/page-banner', 'uses' => 'Admin\BannerController@view'));
	});
	
	//===================================== Infbox ========================//
	Route::group(array('prefix' => 'infobox'), function () {
		Route::get('/', array('as' => 'admin.infobox', 'uses' => 'Admin\InfoboxController@index'));
		Route::get('/data', array('as' => 'admin.infobox.data', 'uses' => 'Admin\InfoboxController@data'));
		Route::get('/create', array('as' => 'infobox.create', 'uses' => 'Admin\InfoboxController@create'));
		Route::post('/create', array('as' => 'admin.infobox.store', 'uses' => 'Admin\InfoboxController@store'));

		Route::get('edit/{id}', array('as' => 'admin.infobox.edit', 'uses' => 'Admin\InfoboxController@create'));
		Route::post('edit/{id}', array('as' => 'admin.infobox.edit.store', 'uses' => 'Admin\InfoboxController@store'));
		Route::get('deleted/{id}', array('as' => 'delete/infobox', 'uses' => 'Admin\InfoboxController@destroy'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/infobox',
			'uses' => 'Admin\InfoboxController@getModalDelete'));
		Route::get('show/{id}', array('as' => 'show/infobox', 'uses' => 'Admin\InfoboxController@view'));
	});

	//Media Routes

	Route::group(array('prefix' => 'media'), function () {

		Route::get('/', array('as' => 'admin.media', 'uses' => 'Admin\MediaUploadController@index'));
		Route::post('/uploadhandlerPhoto', array('as' => 'admin.media.uploadhandlerPhoto', 'uses' => 'Admin\MediaUploadController@uploadhandlerPhoto'));
		Route::post('/upload-new', array('as' => 'admin.media.post', 'uses' => 'Admin\MediaUploadController@upload_store'));
		Route::get('/data', array('as' => 'admin.media.data', 'uses' => 'Admin\MediaUploadController@data'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/media', 'uses' => 'Admin\MediaUploadController@getModalDelete'));
		Route::get('deleted-data/{id}', array('as' => 'delete/media', 'uses' => 'Admin\MediaUploadController@destroyrecord'));

	});

	//=============================== Qr Code Routes =================================//
	Route::group(array('prefix' => 'qrcodes'), function () {
		Route::get('/', array('as' => 'admin.qrcodes', 'uses' => 'Admin\QrCodeController@index'));
	});

	//===================================== Products Routes ========================//
	Route::group(array('prefix' => 'products'), function () {
		Route::get('/', array('as' => 'admin.products', 'uses' => 'Admin\ProductsController@index'));
		Route::get('/data', array('as' => 'admin.products.data', 'uses' => 'Admin\ProductsController@data'));

		Route::get('/history-bulk', array('as' => 'admin.products.history_bulk_qr', 'uses' => 'Admin\ProductsController@history_bulk_qr'));
		Route::get('/history-bulk-download/{id}', array('as' => 'products.history_bulk_download', 'uses' => 'Admin\ProductsController@history_bulk_download'));
		Route::get('history-bulk/exportExcel/{id}', array('as' => 'admin.products.exportExcel', 'uses' => 'Admin\ProductsController@exportExcel'));

		Route::get('/create', array('as' => 'products.create', 'uses' => 'Admin\ProductsController@create'));
		Route::get('/bulk-create/{id?}', array('as' => 'products.bulk_create', 'uses' => 'Admin\ProductsController@bulk_create'));
		Route::post('/create', array('as' => 'admin.products.store', 'uses' => 'Admin\ProductsController@store'));
		Route::post('/bulk-create', array('as' => 'admin.products.bulk_store', 'uses' => 'Admin\ProductsController@bulk_store'));

		Route::get('/import', array('as' => 'products.import', 'uses' => 'Admin\ProductsController@import'));
		Route::post('/import', array('as' => 'admin.products.saveimport', 'uses' => 'Admin\ProductsController@saveimport'));


		Route::post('/price-variation', array('as' => 'admin.products.storePriceVar', 'uses' => 'Admin\ProductsController@storePriceVar'));
		Route::get('/price-variation/data', array('as' => 'admin.price-variation.data', 'uses' => 'Admin\ProductsController@priVarData'));
		Route::get('deletedPriVar/{id}', array('as' => 'delete/price-variation', 'uses' => 'Admin\ProductsController@destroyPriVar'));
		Route::get('{id}/confirm-delete-Pri-var', array('as' => 'confirm-delete/price-variation', 'uses' => 'Admin\ProductsController@getModalDeletePriVar'));

		Route::get('/show/{id}', array('as' => 'admin.products.show', 'uses' => 'Admin\ProductsController@view'));

		Route::post('/add_images/{id}', array('as' => 'admin.products.store_images', 'uses' => 'Admin\ProductsController@storeImages'));
		Route::get('/delete_image/{id}', array('as' => 'admin.products.delete_image', 'uses' => 'Admin\ProductsController@delete_image'));

		Route::get('edit/{id}', array('as' => 'admin.products.edit', 'uses' => 'Admin\ProductsController@create'));
		Route::post('edit/{id}', array('as' => 'admin.products.edit.store', 'uses' => 'Admin\ProductsController@store'));
		Route::get('deleted/{id}', array('as' => 'delete/products', 'uses' => 'Admin\ProductsController@destroy'));
		Route::get('bulk-deleted/{id}', array('as' => 'delete/bulk_history', 'uses' => 'Admin\ProductsController@delete_bulk_history'));
		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/products', 'uses' => 'Admin\ProductsController@getModalDelete'));
		Route::get('{id}/confirm-bulk-delete', array('as' => 'confirm-delete/bulk', 'uses' => 'Admin\ProductsController@getModalDeleteBulk'));

		Route::get('{id}/print-all-bulk/{size?}', array('as' => 'print-qrcode/bulk', 'uses' => 'Admin\ProductsController@printqrcode'));
		Route::get('getSellerCat', array('as' => 'get.seller.cate', 'uses' => 'Admin\ProductsController@get_seller_cate'));

		Route::get('product-images/{id}', array('as' => 'products.images', 'uses' => 'Admin\ProductsController@product_image'));
		
	});

	//================================Promo Code==============================//
	Route::group(array('prefix' => 'promocode'), function () {
		Route::get('/', array('as' => 'promocode', 'uses' => 'Admin\PromoCodeController@index'));
		Route::get('data', array('as' => 'admin.promocode.data', 'uses' => 'Admin\PromoCodeController@data'));
		Route::get('create', array('as' => 'admin.promocode.create', 'uses' => 'Admin\PromoCodeController@create'));
		Route::get('edit/{promo_id}', array('as' => 'admin.promoode.edit', 'uses' => 'Admin\PromoCodeController@create'));
		Route::post('edit/{promo_id}', array('as' => 'admin.promocode.edit', 'uses' => 'Admin\PromoCodeController@store'));
		Route::post('create', array('as' => 'admin.promocode.create', 'uses' => 'Admin\PromoCodeController@store'));
		Route::get('deletedData', array('as' => 'admin.promocode.deletedData', 'uses' => 'Admin\PromoCodeController@deletedList'));
		Route::get('view/{id}', array('as' => 'admin.promocode.view', 'uses' => 'Admin\PromoCodeController@view'));

		//Delete
		Route::get('{promo_id}/confirm-delete', array('as' => 'confirm-delete/promocode', 'uses' => 'Admin\PromoCodeController@getModalDelete'));
		Route::get('deletedpromocode/{promo_id}', array('as' => 'delete/promocode', 'uses' => 'Admin\PromoCodeController@destroy'));
		//Restore
		Route::get('{promo_id}/confirm-restore', array('as' => 'confirm-restore/promocode', 'uses' => 'Admin\PromoCodeController@getModalRestore'));
		Route::get('restorepromocode/{promo_id}', array('as' => 'restore/promocode', 'uses' => 'Admin\PromoCodeController@restoreDeletedPromoCode'));
		//Permanent Delete
		Route::get('{promo_id}/final-delete', array('as' => 'final-delete/promocode', 'uses' => 'Admin\PromoCodeController@getModalFinalDelete'));
		Route::get('finaldeleted/{promo_id}', array('as' => 'finaldelete/promocode', 'uses' => 'Admin\PromoCodeController@permanentDelete'));

	});
#===================================Reward Product Claim Routes===========
	Route::group(array('prefix' => 'reward-claims'), function () {
		Route::get('/', array('as' => 'admin.reward-claims', 'uses' => 'Admin\RewardClaimController@index'));
		Route::get('approved-list', array('as' => 'admin.reward-claims.approved.list', 'uses' => 'Admin\RewardClaimController@approvedList'));
		Route::get('dispatched-list', array('as' => 'admin.reward-claims.dispatched.list', 'uses' => 'Admin\RewardClaimController@dispatchedList'));
		Route::get('delivered-list', array('as' => 'admin.reward-claims.delivered.list', 'uses' => 'Admin\RewardClaimController@deliveredList'));
		Route::get('rejected-list', array('as' => 'admin.reward-claims.rejected.list', 'uses' => 'Admin\RewardClaimController@rejectedList'));
		Route::get('data/{status?}', array('as' => 'admin.reward-claims.data', 'uses' => 'Admin\RewardClaimController@data'));
		Route::get('{id}/view', array('as' => 'admin.reward-claims.view', 'uses' => 'Admin\RewardClaimController@view'));
		Route::get('{id}/approve-modal', array('as' => 'admin.reward-claims.approve.modal', 'uses' => 'Admin\RewardClaimController@approveModal'));
		Route::get('{id}/approve', array('as' => 'admin.reward-claims.approve', 'uses' => 'Admin\RewardClaimController@approve'));
		Route::get('{id}/dispatch-modal', array('as' => 'admin.reward-claims.dispatch.modal', 'uses' => 'Admin\RewardClaimController@dispatchModal'));
		Route::post('{id}/dispatch', array('as' => 'admin.reward-claims.dispatch', 'uses' => 'Admin\RewardClaimController@dispatchToDealer'));
		Route::get('{id}/reject-modal', array('as' => 'admin.reward-claims.reject.modal', 'uses' => 'Admin\RewardClaimController@rejectModal'));
		Route::post('{id}/reject', array('as' => 'admin.reward-claims.reject', 'uses' => 'Admin\RewardClaimController@reject'));
	});
#===================================Redemption Routes=====================
	Route::group(array('prefix'=>'redeem'), function(){
		Route::get('/',array('as'=>'admin.redeem','uses'=>'Admin\RedeemController@index'));
		Route::get('data',array('as'=>'admin.redeem.data','uses'=>'Admin\RedeemController@data'));
		Route::get('redeem-approved-modal/{id}',array('as'=>'admin.redeem.approved.modal','uses'=>'Admin\RedeemController@approved_modal'));
		Route::get('redeem-processed-modal/{id}',array('as'=>'admin.redeem.processed.modal','uses'=>'Admin\RedeemController@processed_modal'));
		Route::post('redeem-approved/{id}/',array('as'=>'admin.redeem.approved','uses'=>'Admin\RedeemController@approved'));
		Route::get('redeem-approved-view/{id}/',array('as'=>'admin.redeem.approved_view','uses'=>'Admin\RedeemController@approved_view'));

		// Route::get('payment-screenshot-view',array('as'=>'admin.redeem.payment_screenshot_view','uses'=>'Admin\RedeemController@payment_screenshot_view'));

		Route::get('redeem-processed/{id}/',array('as'=>'admin.redeem.processed','uses'=>'Admin\RedeemController@processed'));
		Route::get('redeem-rejected/{id}/{task}',array('as'=>'admin.redeem.rejected','uses'=>'Admin\RedeemController@rejected'));
		Route::get('approved-list',array('as'=>'admin.redeem.approved.list','uses'=>'Admin\RedeemController@approved_list'));
		Route::get('approved-list/data',array('as'=>'admin.redeem.approved.list.data','uses'=>'Admin\RedeemController@approved_list_data'));
		Route::get('processing-list',array('as'=>'admin.redeem.processing.list','uses'=>'Admin\RedeemController@processing_list'));
		Route::get('processing-list/data',array('as'=>'admin.redeem.processing.list.data','uses'=>'Admin\RedeemController@processing_list_data'));
		Route::get('rejected-list',array('as'=>'admin.redeem.rejected.list','uses'=>'Admin\RedeemController@rejected_list'));
		Route::get('rejected-list/data',array('as'=>'admin.redeem.rejected.list.data','uses'=>'Admin\RedeemController@rejected_list_data'));
		Route::get('export',array('as'=>'export.redeem.data','uses'=>'Admin\RedeemController@exportExcel'));		
	});
	//===================== newsletter Controller=========================
	Route::group(array('prefix' => 'newsletter'), function () {
		Route::get('/', array('as' => 'admin.newsletter', 'uses' => 'NewsletterController@index'));
		Route::get('data', array('as' => 'admin.newsletter.data', 'uses' => 'NewsletterController@data'));
		Route::post('deletednews/{nsId}', array('as' => 'deleted/newsletter', 'uses' => 'NewsletterController@deleteRecord'));
		Route::get('deletednewsletter', array('as' => 'newsletter/deletednewslist', 'uses' => 'NewsletterController@listDeletedNewsletter'));
		Route::get('deletednewsubdata', array('as' => 'admin.newsletter.listnews', 'uses' => 'NewsletterController@deletedNewsData'));

		//Delete
		Route::get('{nsId}/confirm-delete', array('as' => 'confirm-delete/newsletter', 'uses' => 'NewsletterController@getModalDelete'));
		Route::get('deletednessub/{nsId}', array('as' => 'delete/newsletter', 'uses' => 'NewsletterController@destroy'));
		//Restore
		Route::get('{nsId}/confirm-restore', array('as' => 'confirm-restore/newsletter', 'uses' => 'NewsletterController@getModalRestore'));
		Route::get('restorenessub/{nsId}', array('as' => 'restore/newsletter', 'uses' => 'NewsletterController@restoreDeletedBanner'));
		//Permanent Delete
		Route::get('{nsId}/final-delete', array('as' => 'final-delete/newsletter', 'uses' => 'NewsletterController@getModalFinalDelete'));
		Route::get('finaldeleted/{nsId}', array('as' => 'finaldelete/newsletter', 'uses' => 'NewsletterController@permanentDelete'));
		Route::get('download', array('as' => 'download/newsletter', 'uses' => 'NewsletterController@downloadCSVFile'));

	});
	// blogs controller

	Route::group(array('prefix' => 'blogs'), function () {
		Route::get('/', array('as' => 'admin.blogs', 'uses' => 'Admin\BlogsController@index'));
		Route::get('/data', array('as' => 'admin.blogs.data', 'uses' => 'Admin\BlogsController@data'));
		Route::get('/create', array('as' => 'blogs.create', 'uses' => 'Admin\BlogsController@create'));
		Route::post('/create', array('as' => 'admin.blogs.store', 'uses' => 'Admin\BlogsController@store'));
		Route::get('/show/{id}', array('as' => 'admin.blogs.show', 'uses' => 'Admin\BlogsController@view'));
		Route::get('edit/{id}', array('as' => 'admin.blogs.edit', 'uses' => 'Admin\BlogsController@create'));

		Route::post('edit/{id}', array('as' => 'admin.blogs.edit.store', 'uses' => 'Admin\BlogsController@store'));

		Route::get('deletedblog/{id}', array('as' => 'delete/blogs', 'uses' => 'Admin\BlogsController@destroy'));

		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/blogs', 'uses' => 'Admin\BlogsController@getModalDelete'));

		//blog comment
		Route::get('blogs-comments', array('as' => 'blogs/blogs-comments',
			'uses' => 'Admin\BlogsController@blogs_comments'));
		Route::get('blogscommentsdata', array('as' => 'admin.blogs.blogscomments',
			'uses' => 'Admin\BlogsController@blogs_comments_data'));

		Route::get('/show-comment/{id}', array('as' => 'admin.blogs.show-comment', 'uses' => 'Admin\BlogsController@view_comment'));

		Route::get('edit-comment/{id}', array('as' => 'blogs.edit-comment', 'uses' => 'Admin\BlogsController@edit_comment'));

		Route::post('edit-comment', array('as' => 'blogs-comment.edit.store', 'uses' => 'Admin\BlogsController@store_comment'));

		Route::get('{id}/confirm-delete-comment', array('as' => 'confirm-delete/blogs-comment', 'uses' => 'Admin\BlogsController@getCommentModalDelete'));

		Route::get('deletedblog-comment/{id}', array('as' => 'delete/blogs-comment', 'uses' => 'Admin\BlogsController@destroy_comment'));

	});

	//user review
	Route::group(array('prefix' => 'user-review'), function () {
		Route::get('/', array('as' => 'admin.user-review',
			'uses' => 'Admin\UserReviewController@index'));
		Route::get('user-reviews-data', array('as' => 'admin.review-data',
			'uses' => 'Admin\UserReviewController@data'));

		Route::get('/show/{id}', array('as' => 'admin.show-review',
			'uses' => 'Admin\UserReviewController@view'));

		Route::get('edit-comment/{id?}', array('as' => 'user-review.edit',
			'uses' => 'Admin\UserReviewController@create'));

		Route::post('edit-comment/{ID?}', array('as' => 'admin.user-review.store',
			'uses' => 'Admin\UserReviewController@store'));

		Route::get('{id}/confirm-delete-review', array('as' => 'confirm-delete/review',
			'uses' => 'Admin\UserReviewController@getModalDelete'));

		Route::get('delete-review/{id}', array('as' => 'delete/user-review',
			'uses' => 'Admin\UserReviewController@destroy'));

	});

	//================================== Trash records ====================================

	Route::group(array('prefix' => 'products_trash'), function () {
		Route::get('qr-trash', array('as' => 'products_trash/qr-trash',
			'uses' => 'Admin\ProductsController@listDeletedtrash'));
		Route::get('deletedtrash', array('as' => 'admin.products_trash.deleteddata',
			'uses' => 'Admin\ProductsController@listDeletedData'));
		Route::get('{Id}/confirm-delete', array('as' => 'confirm-delete/products_trash',
			'uses' => 'Admin\ProductsController@deleteconfirm'));
		Route::get('{Id}/confirm-restore', array('as' => 'confirm-restore/products_trash',
			'uses' => 'Admin\ProductsController@getModalRestore'));
		Route::get('restoresubs/{enqId}', array('as' => 'restore/products_trash',
			'uses' => 'Admin\ProductsController@restoreDeletedtrash'));
		Route::get('{enqId}/final-delete', array('as' => 'final-delete/products_trash',
			'uses' => 'Admin\ProductsController@getModalFinalDelete'));
		Route::get('finaldeleted/{enqId}', array('as' => 'finaldelete/products_trash',
			'uses' => 'Admin\ProductsController@permanentDelete'));

	});

	//product images
	Route::group(array('prefix' => 'products-images'), function () {
		Route::get('/', 'Admin\ProductImageController@index');
		Route::get('/image', array('as' => 'admin.productimg', 'uses' => 'Admin\ProductImageController@index'));
		Route::get('create/{product_id}', array('as' => 'create.productimg', 'uses' => 'Admin\ProductImageController@create'));
		Route::post('create', array('as' => 'store.productimg', 'uses' => 'Admin\ProductImageController@store'));
		Route::get('show/{ID}', 'Admin\ProductImageController@show');
		/* get all images
		Route::get('/data', array('as' => 'admin.productimg.data', 'uses' => 'Admin\ProductImageController@data')); */

		Route::get('data/{product_id}', array('as' => 'admin.productimg.data', 'uses' => 'Admin\ProductImageController@data'));

		Route::get('edit/{ID}', array('as' => 'edit.productimg', 'uses' => 'Admin\ProductImageController@edit'));
		Route::post('edit/{ID}', array('as' => 'updated.productimg', 'uses' => 'Admin\ProductImageController@store'));

		Route::get('{id}/confirm-delete', array('as' => 'confirm-delete.productimg', 'uses' => 'Admin\ProductImageController@getModalDelete'));
		Route::get('deletedgallery/{id}', array('as' => 'deleted.productimg', 'uses' => 'Admin\ProductImageController@destroy'));
	});

	//===================================== Enquiry Page Manager Route Path Start =================================//
	Route::group(array('prefix' => 'enquiry'), function () {
		Route::get('/', array('as' => 'admin.enquiry', 'uses' => 'Admin\EnquiryAdminController@index'));
		Route::get('data', array('as' => 'admin.enquiry.data', 'uses' => 'Admin\EnquiryAdminController@data'));
		Route::get('reply/{enqId}', array('as' => 'admin.enquiry.reply', 'uses' => 'Admin\EnquiryAdminController@enquiryReply'));
		Route::post('reply/{enqId}', array('as' => 'admin.enquiry.replypost', 'uses' => 'Admin\EnquiryAdminController@enquiryReplyPost'));
		Route::get('allreply/{enqId}', array('as' => 'admin.enquiry.allreply', 'uses' => 'Admin\EnquiryAdminController@enquiryAllReply'));
		Route::get('view/{enqId}', array('as' => 'enquiry/view', 'uses' => 'Admin\EnquiryAdminController@viewRecord'));
		Route::get('deleteenquiry/{enqId}', array('as' => 'enquiry/deleted', 'uses' => 'Admin\EnquiryAdminController@deleteconfirm'));
		Route::get('deletedestroy/{enqId}', array('as' => 'admin/delete/enquiry', 'uses' => 'Admin\EnquiryAdminController@destroy'));
		Route::get('listdeletedenquiry', array('as' => 'enquiry/deletedfeedback', 'uses' => 'Admin\EnquiryAdminController@listDeletedenquiry'));
		Route::get('deletedenquiry', array('as' => 'admin.enquiry.deleteddata', 'uses' => 'Admin\EnquiryAdminController@listDeletedData'));
		Route::get('{Id}/confirm-delete', array('as' => 'confirm-delete/enquiry', 'uses' => 'Admin\EnquiryAdminController@deleteconfirm'));
		Route::get('{Id}/confirm-restore', array('as' => 'confirm-restore/enquiry', 'uses' => 'Admin\EnquiryAdminController@getModalRestore'));
		Route::get('restoresubs/{enqId}', array('as' => 'restore/enquiry', 'uses' => 'Admin\EnquiryAdminController@restoreDeletedenuiry'));
		Route::get('{enqId}/final-delete', array('as' => 'final-delete/enquiry', 'uses' => 'Admin\EnquiryAdminController@getModalFinalDelete'));
		Route::get('finaldeleted/{enqId}', array('as' => 'finaldelete/enquiry', 'uses' => 'Admin\EnquiryAdminController@permanentDelete'));
	});

	//============================================Testimonial==================================================
	Route::group(array('prefix' => 'testimonial'), function () {
		Route::get('/', array('as' => 'admin.testimonial', 'uses' => 'Admin\TestimonialAdminController@index'));
		Route::get('data', array('as' => 'admin.testimonial.data', 'uses' => 'Admin\TestimonialAdminController@data'));
		Route::get('create', array('as' => 'create.testimonial', 'uses' => 'Admin\TestimonialAdminController@create'));
		Route::post('create', array('as' => 'store.testimonial', 'uses' => 'Admin\TestimonialAdminController@store'));
		Route::get('show/{Id}', 'Admin\TestimonialAdminController@show');
		Route::get('edit/{ID}', array('as' => 'update.testimonial', 'uses' => 'Admin\TestimonialAdminController@create'));
		Route::post('edit/{ID}', array('as' => 'updated.testimonial', 'uses' => 'Admin\TestimonialAdminController@store'));
		Route::get('{page_id}/confirm-delete', array('as' => 'confirm-delete.testimonial', 'uses' => 'Admin\TestimonialAdminController@getModalDelete'));
		Route::get('destroy/{page_id}', array('as' => 'delete.testimonial', 'uses' => 'Admin\TestimonialAdminController@destroy'));
		Route::match(array('GET', 'POST'), 'doTask/{task}/{id}', array('as' => 'testimonial.doTask', 'uses' => 'Admin\TestimonialAdminController@doTask'));
	});
		//============================================Sub-admin==================================================

	Route::group(array('prefix' => 'sub_admin'), function () {
    	Route::get('/', array('as' => 'admin.sub_admin', 'uses' => 'Admin\SubAdminController@index'));
    	Route::get('/data', array('as' => 'admin.sub_admin.data', 'uses' => 'Admin\SubAdminController@data'));
    	Route::get('/create', array('as' => 'sub_admin.create', 'uses' => 'Admin\SubAdminController@create'));
    	Route::post('/create', array('as' => 'admin.sub_admin.store', 'uses' => 'Admin\SubAdminController@store'));
    	Route::get('/show/{id}', array('as' => 'admin.sub_admin.show', 'uses' => 'Admin\SubAdminController@view'));
    	Route::get('/change-password/{id}', array('as' => 'admin.sub_admin.change_password', 'uses' => 'Admin\SubAdminController@change_password'));
    	Route::post('/change-password-post/{id}', array('as' => 'admin.sub_admin.change_password_post', 'uses' => 'Admin\SubAdminController@change_password_post'));
    	Route::get('edit/{id}', array('as' => 'admin.sub_admin.edit', 'uses' => 'Admin\SubAdminController@create'));
    	Route::get('/getsub_categorydata', array('as' => 'admin.sub_admin.getsub_categorydata', 'uses' => 'Admin\SubAdminController@getSubCategory'));
    	Route::post('edit/{id}', array('as' => 'admin.sub_admin.edit.store', 'uses' => 'Admin\SubAdminController@store'));
    	Route::get('deleted/{id}', array('as' => 'delete/sub_admin', 'uses' => 'Admin\SubAdminController@destroy'));
    	Route::get('{id}/confirm-delete', array('as' => 'confirm-delete/sub_admin', 'uses' => 'Admin\SubAdminController@getModalDelete'));
    	Route::match(array('GET', 'POST'), 'doTask/{task}/{id}/{s_id}', array('as' => 'assign-khagent.doTask', 'uses' => 'Admin\SubAdminController@doTask'));
    });
});

//=================Api route=====================//

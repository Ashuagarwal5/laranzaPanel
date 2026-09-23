<?php
return [
	'siteinfo' => [
		'site_id' => "1",
		'group_id' => "1",

	],

	'frontend' => [

		'title' => "Hempstrol",

		'DefaultTitle' => "Hempstrol",
		'currency' => "₹ ",

		'DefaultDescription' => "Hempstrol",

		'DefaultKeyword' => "Hempstrol",

		'logo' => "assets/default/img/logo.png",

	],
	'admin' => [

		'title' => "Hempstrol",
		'logo' => "assets/default/logo.png",
		'default_user' => "/assets/admin/default_user.png",
		'no_image_found' => "/assets/admin/noImageFound.jpg",

	],

	'invoicecss' => [
		'processing' => "bg-warning",
		'completed' => 'bg-success',
		'pending' => 'bg-default',
		'awaiting shipment' => 'bg-primary',
		'item not available' => 'bg-info',
		'cod verififcation failed' => 'bg-info',
		'convert to another order' => 'bg-info',
		'goods shortage' => 'bg-info',
		'customer hold' => 'bg-info',
		'pending' => 'bg-danger',
		'refund_payment' => 'bg-info',
		'payment authentication failed' => 'bg-danger',
	],

	'Ordertab' => [
		'processing' => 'Processing',
		'awaiting_shipment' => 'Awaiting Shipment',
		'dispatched' => 'Dispatched',
		'replacement' => 'Replacement Submited',
		'refund' => 'Refunds',

	],

	'Orderstate' => [
		'pending' => 'Pending',
		'processing' => "Processing",
		'awaiting payment' => 'Awaiting Payment',
		'awaiting shipment' => 'Awaiting Shipment',
		'complete' => 'Complete',
		'completed' => 'Complete',

		'pending payment' => 'Pending Payment',
		'dispatched' => 'Dispatched',
		'received' => 'Received',
		'broken' => 'Broken',
		'replacement' => 'Replacement Submited',
		'refund' => 'Refund Payment',
		'wrong_address' => 'Refund Submited',

		'closed' => 'Closed',
		'holded' => 'Holded',
		'item not available' => 'Item not Available',
		'cod verififcation failed' => 'COD Verififcation Failed',
		'convert to another order' => 'Convert to Another Order',
		'payment authentication failed' => 'Payment Authentication Failed',
		'goods shortage' => 'Goods Shortage',
		'customer hold' => 'Customer Hold',

		'refund_payment' => 'Refund Payment',
		'canceled' => 'Canceled',

	],

	'Orderstatus' => [

		'new' => ['pending', 'pending payment', 'awaiting payment'],

		'processing' => ['processing', 'awaiting shipment'],
		'complete' => ['dispatched', 'completed'],
		'closed' => ['item not available', 'cod verififcation failed', 'convert to another order'],
		'canceled' => ['payment authentication failed'],
		'on hold' => ['goods shortage', 'customer hold'],

	],

	'paymenticon' => [
		'msp_cashondelivery' => 'cod.png',
		'Pay u' => 'payu.png',
		'payucheckout_shared' => 'payu.png',
		'Paytm' => 'paytm.png',
	],

	'Delivery' => [
		'day' => '7',
	],

];
?>

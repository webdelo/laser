<?php
define('DIR_ADMIN',str_replace('includes','',dirname(__FILE__)));

include(dirname(DIR_ADMIN).'/includes/config.php');

$configAdmin = array(
	'authorization' => array(
		'errors' => array(
			'authError' => 'Wrong username or password',
			'captcha' => 'Wrong text from the picture',
			'ban' => 'Ban. You can make other try in 5 minutes',
			'authorized' => 'Authorized another user!',
			'ok' => 'User has successfully authorized.',
		),
		'timeout' => 300,
	),
	// page by page navigation settings
	'pagesInfo' => array(
		'pages_view_back' => 3,		//pages showing before current
		'pages_view_forward' => 3,	//pages showing after current
	),
	'fileExtensions' => array(
		'jpg',
		'jpeg',
		'gif',
		'png'
	),
	'mimeTypes' => array(
		'image/gif',
		'image/jpeg',
		'image/png',
		'image/pjpeg',
	),
);

$operator_actions = array(
	'defaultAction','addAddon','editAddon','addAddonAction','editAddonAction','deleteAddon','products','addProduct','editProduct',
	'addProductAction','editProductAction','deleteProduct','calendar','orders','viewOrder','saveOrderChanges',
	'editOrderPoduct','deleteOrderPoduct','editOrderAddon','confirmOrder','showCNumber','saveOrderShipping',
	'addBilling','changeStatusOrder','deleteOrder','uploadBilling','phoneNumbers','addPhoneNumber','editPhoneNumber',
	'addPhoneNumberAction','editPhoneNumberAction','deletePhoneNumberAction','editOrderPhone','resendEmail','addOrderProduct',
	'addOrderAddon', 'makePayment'
);

\core\Configurator::getInstance()->load($configAdmin);
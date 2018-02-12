<?php
define('TEST_MODE', true);
date_default_timezone_set('Europe/Moscow');
define('AMERICAN_DATE', false);
if (AMERICAN_DATE) {
	define('SIMPLE_DATE_PATTERN', 'm-d-Y');
} else {
	define('SIMPLE_DATE_PATTERN', 'd-m-Y');
}
if (TEST_MODE) {
	ini_set("display_errors",        "1");
	ini_set("display_startup_errors","1");
	ini_set('error_reporting',       E_ALL);
}
else {
	ini_set('display_errors', 'Off');
	error_reporting(0);
}
ini_set('magic_quotes_gpc', 0);
define('DIR', str_replace('includes','',dirname(__FILE__)));
define('DEFAULT_PROTOCOL','http');
define('DIR_HTTP',        'http://'.$_SERVER['HTTP_HOST'].'/');
define('DIR_ADMIN_HTTP',  DEFAULT_PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/admin/');
define('DIR_HTTPS',       'https://'.$_SERVER['HTTP_HOST'].'/');
define('SEND_FROM',       'run-laser');
define('TEMPLATES',       DIR.'templates/');
define('TEMPLATES_ADMIN', DIR.'admin/templates/');
define('FILES_DIR',       '/files/');
define('DATE_FORMAT',     'mm-dd-yy');
define('TABLE_PREFIX', 'tbl_');
define('NO_IMAGE_PATH', '/images/noimage/');
define('NO_IMAGE_FILE_PATH', DIR.'/images/noimage/noimage.png');
// All config
$config = array(
	'url'=>
		array(
			'settings'=>
				array(
					'lang'       => 'ByDomain',      // 'SubDomain', 'Element', 'None', ByDomain
					'part'       => 'SubDomain', // 'SubDomain', 'Element', 'None'
					'controller' => 'Element',   // 'Element', 'None'
					'domainLevel' => 1,
				),
			'default'=>
				array(
					'lang'=>'ru',
					'part'=>'article',
					'domain'=>'run-laser.com',
					'domainAlias'=>'run-laser.com',
				),
			'extensions'=>
				array(
					'.html',
					'.php',
					'.asp',
					'.aspx',
					'.xml'
				),
			'parts'=>
				array(
				),
			'langs'=>
				array(
					'ru' => array('run-laser.com'),
					'en' => array('run-laser.com')
				),
			'domains'=> array(
				'run-laser.com' => array(
					'run-lasers.webdelo.org',
					'run-laser.com',
				),
			),
			'developerDomains'=> array(
				'run-lasers.webdelo.org',
			),
			'domainsDevelopersAliases' => array(
				'run-laser.com' => 'RunLaser',
			),
		),
	'controllers'=>
		array(
			'defaultFrontController' => 'article',
			'defaultAdminController' => 'index',
		),
	'redirect'=>
		array(
			'csvPath'        => DIR.'redirect/',
			'csvFile'        => 'redirect.csv',
			'www'            => 'without',    // 'with', 'without', 'none_redorect'
			'protocol'       => 'http',      // 'https' OR 'http'
			'http2https'     => array(),      // all redirect patterns in array. May be URI or path pattern with '*' in end
			'https2http'     => false,        // true - redirect enable, false - redirect disadle
			'wwwSubdomains'  => 'without',    // 'with', 'without', 'none_redorect'
			'subdomainLevel' => 2,            // level sub-domain on which the partition PART begin from second-level domain
			'endSlash'       => true,         // true - redirect enable, false - redirect disadle (when last URL-simbol is not "/")
		),
	'developerShell'=>array(
			'debugMode'		  => false, // Show shell when fixed debug points
			'errorMode'		  => false,  // Show shell when fixed errors
			'dumpMode'		  => false,  // Show shell when fixed dumps
			'IP'			  => array( '92.115.183.153', '37.26.138.240', '89.28.112.179', '80.94.245.133', '185.50.5.168'), // Developers IP
	),
	'cache' =>
		array(
			'config'  => array(
				'globalCacheUsing'  => true, // On/Off cache mode for all types of queries
				'pagesCacheUsing'   => true, // On/Off cache mode for pages caching by URI key
				'objectsCacheUsing' => true, // On/Off cache mode for moduleObjects
				'dbCacheUsing'      => true, // On/Off cache mode for data base queries
			),
			'engines' => array( // default engine selected by minimum array key
				0 => array(
					'name' => 'Memory',
					'defaultLifeTime' => 86400,
				),
				1 => array(
					'name' => 'File',
					'defaultLifeTime' => 86400,
				),
				2 => array(
					'name' => 'MySQL',
					'defaultLifeTime' => 86400,
				),
			),
		),
	'images' =>
		array(
			'resize'  => array( // defualt resize settings
				'bgColor' => array(255,255,255), // background for resized images
				'sharpen' => false, // sharpen effect
				'transparency' => 127,
				'isConstrant' => true,
				'copyWhenNoNeedResize' => true,
				'jpegQuality' => 100
			),
		),
	'debug' =>
		array(
			'log'            => array('Screen'),
			'projectName'    => 'Lasers',
			'round_timer'     => 4,
			'max_point_timer' => 5,
			'http_root'       => '/plugins/debug/',
			'document_root'   => DIR.'plugins/debug/',
			'mailFrom'        => 'support@webdelo.org',
			'mailTo'          => array(	'dmitriy.cercel@gmail.com', 'd.cercel@webdelo.org', 'a.popov@webdelo.org', 'support@webdelo.org', 'a.grinceac@webdelo.org', 'd.godiac@webdelo.org'),
			'mailSubject'     => 'Warning! Exceeding the time limit for processing a script.'
		),
	'errorHandler' => array(
			'log'           => array('Screen', 'Email'), // array('DB', 'File', 'Email', 'Screen')
			'projectName'   => 'Lasers',
			'title'         => 'Fixed error.',
			'mailTo'          => array('d.godiac@webdelo.org'),
			'message'       => '<strong style="color: red;">Sorry, the system detected an error. Our programmers will take its correction in the near future.</strong>',
		),
	'authorization' => array(
		'errors' => array(
			'authError'  => 'Wrong username or password',
			'captcha'    => 'Wrong text from the picture',
			'ban'        => 'Ban. You can make other try in 5 minutes',
			'authorized' => 'Authorized another user!',
			'ok'         => 'User has successfully authorized.',
		),
		'timeout' => 500,
	),
	// page by page navigation settings
	'pagesInfo' => array(
		'pages_view_back' => 1000,		//pages showing before current
		'pages_view_forward' => 1000,	//pages showing after current
	),
	'db'=>
		array(
			'settings' =>
				array(
					'host'       => 'db03.hostline.ru',
					'login'      => 'vh88807_new',
					'pass'       => 'wUKooL9G',
					'database'   => 'vh88807_release',
					'error_mode' => (TEST_MODE) ? 2 : 1  // 1 - short error ("MySQL Error"); 2 - full error
				),
			'tables' =>
				array(
					'settings' => 'settings',
				),
			),
	'mail_subjects'=>
		array(
			'remind_password'=>'Вы запросили код для изменения пароля на сайте '.DIR_HTTP,
			'new_password'=>'Ваш пароль на сайте '.DIR_HTTP.' был изменен'
		),
);
function __autoload($className)
{
	$path = DIR.  str_replace('\\', '/', $className).'.php';
	if (file_exists($path)) {
		require($path);
		return true;
	}
	return false;
}
//set_error_handler(array('ErrorHandler', 'systemErrorHandler'));
\core\Configurator::getInstance()->load($config);
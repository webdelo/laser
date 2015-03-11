<?php
namespace core;

//ini_set('xdebug.max_nesting_level', 300);

try {
	define('TYPE','front');

	session_start();
	include('includes/config.php');
	$configurator = Configurator::getInstance();

	utils\Protector::getInstance()->xss($_REQUEST)->xss($_POST)->xss($_GET);
	$config = $configurator->getArrayByKey('debug');
	debug\Debug::getInstance()->setConfig($config);
	debug\Debug::getInstance(__FILE__, __LINE__,__FUNCTION__,__CLASS__,__METHOD__)->start();

	$urlDecodeConfig = $configurator->getArrayByKey('url');
	url\UrlDecoder::getInstance()
		->setConfig($urlDecodeConfig)
		->requestRebuild();

	$urlRedirectorConfig = $configurator->getArrayByKey('redirect');
	url\UrlRedirector::getInstance()
		->setConfig($urlRedirectorConfig)
		->loadCsvData()
		->redirectCurrentPage();

	$controllerFactoryConfig = $configurator->getArrayByKey('controllers');
	$controller = \controllers\base\ControllerFactory::getInstance()
		->setConfig($controllerFactoryConfig)
		->$_REQUEST['controller'];
	$controller->$_REQUEST['action']();

	\core\debug\Debug::getInstance(__FILE__, __LINE__,__FUNCTION__,__CLASS__,__METHOD__)->setResult();
} catch (\Exception $e) {
	$shell = new debug\DeveloperShell(new debug\ErrorHandler());
	$shell->exceptionHandler($e);
}

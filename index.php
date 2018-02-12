<?php
namespace core;
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

    if (!empty($_SERVER['HTTP_HOST'])) {
        if ($_SERVER['HTTP_HOST'] == 'en.run-laser.com')
            \core\i18n\LangHandler::getInstance()->setLang('en');
        else
            \core\i18n\LangHandler::getInstance()->setLang('ru');
    }

if ($_SERVER['REQUEST_URI'] == "/google2851581e40f353d8.html" && $_SERVER['HTTP_HOST'] == 'en.run-laser.com') {
include 'google2851581e40f353d8.html';
return true;
}

	$controllerFactoryConfig = $configurator->getArrayByKey('controllers');
	$controller = \controllers\base\ControllerFactory::getInstance()
		->setConfig($controllerFactoryConfig)
		->$_REQUEST['controller'];

	$controller->$_REQUEST['action']();
} catch (\Exception $e) {
	$shell = new debug\DeveloperShell(new debug\ErrorHandler());
	$shell->exceptionHandler($e);
}

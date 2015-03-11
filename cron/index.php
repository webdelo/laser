<?php
namespace core;
try {
	define('TYPE','front');

	session_start();

	include('/var/www/webdelo/data/www/neagani-a.webdelo.org/includes/config.php');

	$configurator = Configurator::getInstance();

	$urlRedirectorConfig = $configurator->getArrayByKey('redirect');
	url\UrlRedirector::getInstance()
		->setConfig($urlRedirectorConfig)
		->loadCsvData()
		->redirectCurrentPage();

	$urlDecodeConfig = $configurator->getArrayByKey('url');
	url\UrlDecoder::getInstance()
		->setConfig($urlDecodeConfig)
		->requestRebuild();

	$controllerFactoryConfig = $configurator->getArrayByKey('controllers');
	$controller = \controllers\base\ControllerFactory::getInstance()
		->setConfig($controllerFactoryConfig)
		->cron;

	if(isset($_REQUEST['PHPSESSID']))
		$controller->defaultAction();
	else
		$controller->transferEventsToArchive();

} catch (\Exception $e) {
	$shell = new debug\DeveloperShell(new debug\ErrorHandler());
	$shell->exceptionHandler($e);
}

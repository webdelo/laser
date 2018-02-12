<?php
namespace core;
try {
	define('TYPE','admin');

	session_start();
	include('includes/config.php');
	$configurator = Configurator::getInstance();

	$urlDecodeConfig = $configurator->getArrayByKey('url');
	url\UrlDecoder::getInstance()->setConfig($urlDecodeConfig)->requestRebuild();

	if (\controllers\base\ControllerFactory::getInstance()->authorization->checkAuthorization()){
		$action = (empty($_REQUEST['action'])) ? '' : $_REQUEST['action'];

		if ( \controllers\base\ControllerFactory::getInstance()->authorization->authorizatedUser()->category_id == 2 && !in_array($action, $operator_actions) )
			$action = 'forbiddenAction';

		\controllers\base\ControllerFactory::getInstance()
			->setConfig($configurator->getArrayByKey('controllers'))
			->$_REQUEST['controller']
			->$action();
	}

} catch (\exceptions\ExceptionAccess $e) {

} catch (\Exception $e) {
	$shell = new debug\DeveloperShell(new debug\ErrorHandler());
	$shell->exceptionHandler($e);
}
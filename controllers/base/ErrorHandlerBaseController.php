<?php
namespace controllers\base;
abstract class ErrorHandlerBaseController extends Controller
{
	public function jsErrorHandler() {
		$errorHandler = new \core\debug\ErrorHandler;
		$errorHandler->jsErrorHandler($this->getREQUEST());
		exit();
	}
}
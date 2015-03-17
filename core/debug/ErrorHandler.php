<?php
namespace core\debug;
class ErrorHandler
{
	use \core\traits\RequestHandler;

	static public $errors = array();

	private $config = array(
		'title'         => 'Fixed error.',
		'projectName'   => 'Vput.ru',
		'mailTo'        => array('d.cercel@webdelo.org', 'a.popov@webdelo.org', 'a.grinceac@webdelo.org', 'd.godiac@webdelo.org'),
		'log'           => array('Screen'), // array('DB', 'File', 'Email', 'Screen')
		'message'       => '<strong style="color: red;">Sorry, the system detected an error. Our programmers will take its correction in the near future.</strong>',
		'exceptionView' => true
	);

	public $data = array(
		'exceptionTrace' => array(),
		'backtrace'      => array(),
		'request'        => array(),
		'server'         => array(),
		'session'        => array(),
		'coockies'       => array()
	);

	private $errorsList = array(
		'2' => array(
			'name'=>'E_WARNING',
			'description'=>'Warnings (non-fatal errors).
			Running this script is not interrupted.',
			'color' => '#FF0000'
		),
		'8' => array(
			'name'=>'E_NOTICE',
			'description'=>'Remarks (less serious errors than
			warnings). Indicate a situation that could become
			cause more serious errors, but can occur in
			During normal operation the script.',
			'color' => '#EE799F'
		),
		'256' => array(
			'name'=>'E_USER_ERROR',
			'description'=>'User error.',
			'color' => '#CD6889'
		),
		'512' => array(
			'name'=>'E_USER_WARNING',
			'description'=>'User warning.',
			'color' => '#8B475D'
		),
		'1024' => array(
			'name'=>'E_USER_NOTICE',
			'description'=>'User notice.',
			'color' => '#FF3E96'
		),
		'2048' => array(
			'name'=>'E_STRICT',
			'description'=>'Notification run-time script to change the code
			to improve its interpretation, for example warn you about
			using unwanted (old) functions',
			'color' => '#FF3E96'
		),
	);

	public $type           = '';
	public $message        = '';
	public $file           = '';
	public $line           = '';
	public $code           = '';
	public $exceptionClass = '';
	public $phpVersion     = '';
	public $content        = '';


	public function __construct()
	{
		$this->setConfig();
		$this->setData();
	}

	public function __set($name, $value) {
		if ( isset($this->data[$name]) && $value )
			$this->data[$name] = $value;
	}

	public function __get($name) {
		if (isset($this->data[$name]))
			return $this->data[$name];
	}

	private function setConfig()
	{
		$data = \core\Configurator::getInstance()->getArrayByKey('errorHandler');
		if(!empty($data))
			$this->config = $data;
	}

	public function setConfigByKey($key, $value)
	{
		$this->config[$key] = $value;
	}

	private function setData()
	{
		$this->request  = $this->getREQUEST();
		$this->server   = $this->getSERVER();
		$this->session  = $this->getSESSION();
		$this->coockies = $_COOKIE;

		$this->phpVersion = phpversion();
	}

	public function setBacktrace($var)
	{
		$this->backtrace = $var;
		return $this;
	}

	public function setErrorData($msg, $code, $file, $line)
	{
		$this->type = 'Error';
		$this->message = $msg;
		$this->code    = $code;
		$this->file    = $file;
		$this->line    = $line;

		return $this;
	}

	public function setExceptionData($e)
	{
		if (is_object($e)) {
			$this->type = 'Exception';
			$this->exceptionTrace = $e->getTrace();
			$this->message = $e->getMessage();
			$this->file = $e->getFile();
			$this->line = $e->getLine();
			$this->code = $e->getCode();
			$this->exceptionClass= get_class($e);
		}
		return $this;
	}

	public function logErrors() {
		foreach($this->config['log'] as $method){
			$method = 'log'.$method;
			if ($this->isMethod($method))
				$this->$method();
		}
		return $this;
	}

	private function logEmail()
	{
		ob_start();
		include(TEMPLATES_ADMIN.'errorHandlerMail.tpl');
		$content = ob_get_contents();
		ob_end_clean();

		$headers= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=win-1251\r\n";

		mail(implode(', ', $this->config['mailTo']), $this->config['title'].' '.$this->config['projectName'], $content, $headers);
	}

	private function logScreen()
	{
		$showMessage = 'show'.$this->type.'Message';
		if ($this->isMethod($showMessage)) {
			$this->$showMessage();
		}
	}

	private function isMethod($var)
	{
		return in_array($var, get_class_methods($this));
	}

	private function showExceptionMessage()
	{
		ob_start();
		include(TEMPLATES_ADMIN.'errorHandler.tpl');
		$content = ob_get_contents();
		ob_end_clean();
		echo $content;
	}

	private function showErrorMessage()
	{
		self::$errors[] = array(
			'message' => $this->message,
			'type'    => $this->type,
			'details' => $this->getErrorDetails(),
			'line'    => $this->line,
			'file'    => $this->file,
 		);
	}

	private function logDB()
	{
		// Log in DB
	}

	private function logFile()
	{
		// Log in File
	}

	private function getErrorDetails()
	{
		return ($this->isErrorCode($this->code)) ? $this->errorsList[$this->code] : '' ;
	}

	private function isErrorCode($key)
	{
		return array_key_exists($key, $this->errorsList);
	}

	public function systemErrorHandler($code, $msg, $file, $line) {
		$errorHandler = new ErrorHandler();
		$errorHandler->setErrorData($msg, $code, $file, $line)
					->setBacktrace(debug_backtrace(0))
					->logErrors();
	}

	public function exceptionErrorHandler($e) {
		$this->setExceptionData($e)
			->setBacktrace(debug_backtrace(0))
			->logErrors();
	}

	public function jsErrorHandlerMail($data) {
		$this->setErrorData($data['message'], 1, $data['url'], $data['line']);
		$this->setData();

		$this->type = 'JS error';
		$this->data['navigator'] = $data['navigator'];
		$this->data['data'] = $data;

		ob_start();
		include(TEMPLATES_ADMIN.'jsErrorHandlerMail.tpl');
		$content = ob_get_contents();
		ob_end_clean();

		$headers= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=win-1251\r\n";

		mail(implode(', ', $this->config['mailTo']), $this->config['title'].' '.$this->config['projectName'], $content, $headers);
	}

	public function jsErrorHandler($data)
	{
		$this->setErrorData($data['message'], 1, $data['url'], $data['line']);
		$this->setData();

		$this->type = 'JS error';
		$this->data['navigator'] = $data['navigator'];
		$this->data['data'] = $data;

		ob_start();
		include(TEMPLATES_ADMIN.'jsErrorHandlerMail.tpl');
		$this->content = ob_get_contents();
		ob_end_clean();
//var_dump($data['message'], $data['url'], $data['line'] );die();
		$errors = new Errors();
		$errors->addError($this);
	}

}
?>

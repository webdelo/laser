<?php
namespace core\debug;
class DeveloperShell 
{
	use \core\traits\RequestHandler;
	
	private $config = array();
	private $object = '';
	
	public function __construct($object)
	{
		$this->setObject($object);
		$this->setConfig();
	}
	
	public function setConfig(){
		$config = \core\Configurator::getInstance()->getArrayByKey('developerShell');
		if (is_array($config))
			$this->config = $config;
	}
	
	public function setObject($object)
	{
		$this->object = $object;
	}
	
	public function show() {
		if ($this->checkShowDebugger()) {
			$this->object->showContent('debug.tpl');
		}
		
	}
	public function exceptionHandler($e)
	{
		if ($this->checkAccess())
			$this->object->setConfigByKey('exceptionView', true);
		$this->object->exceptionErrorHandler($e);
	}
	
	private function checkShowDebugger () {
		try {
			$this->checkDebugMode();
			$this->checkErrorMode();
			$this->checkDumpMode();
		} catch (Exception $e) {
			return $this->checkAccess();
		}
	}
	
	private function checkDebugMode () {
		if ( $this->config['debugMode'] )
			throw new \Exception('Debug mode enabled', 1);
	}
	
	private function checkErrorMode () {
		if ( $this->config['errorMode'] && sizeof($this->object->errorsList) > 0 )
			throw new \Exception('Error mode enabled', 1);
	}
	
	private function checkDumpMode () {
		$object = $this->object;
		if ( $this->config['dumpMode'] && sizeof($object::$varDumpData) > 0)
			throw new \Exception('Dump mode enabled', 1);
	}
	
	private function checkAccess () {
		return (in_array($this->getSERVER()['REMOTE_ADDR'], $this->config['IP']));
	}
	
}
?>

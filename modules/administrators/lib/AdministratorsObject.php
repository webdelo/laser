<?php
namespace modules\administrators\lib;
class AdministratorsObject extends \core\authorization\UserRegistrator
{
	protected $configClass     = '\modules\administrators\lib\AdministratorConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
	
	public function addAdmin($data)
	{
		$this->setGroup('\modules\administrators\lib\Administrator');
		$this->_beforeChangeWithoutAdapt($data, $this->_moduleConfig->getObjectFields());
		if ($this->getError()) {
			return false;
		} else {
			$data->id = $this->addLogin();
			$this->add($data);
			return $data->id;
		}
	}
}

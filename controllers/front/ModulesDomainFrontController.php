<?php
namespace controllers\front;
class ModulesDomainFrontController extends \controllers\base\ModulesDomainBaseController
{
	protected $permissibleActions = array(
		'getValidDomain'
	);

	public function  __construct()
	{
		parent::__construct();
	}

	protected function getValidDomain($domain)
	{
		$domain = $_SERVER['HTTP_HOST'];
		$domainsArray = $this->getAllDomains();

		foreach ($domainsArray as $key=>$value)
			if($domain == $key   ||   in_array($domain, $value))
				return $key;
			
		return false;
	}
}

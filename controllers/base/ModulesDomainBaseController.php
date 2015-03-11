<?php
namespace controllers\base;
class ModulesDomainBaseController extends \controllers\base\Controller
{
	protected $permissibleActions = array(
	);

	public function  __construct()
	{
		parent::__construct();
	}

	protected function getAllDomains()
	{
		return \core\Configurator::getInstance()->url->domains->getArray();
	}
}

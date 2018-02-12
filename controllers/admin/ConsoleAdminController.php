<?php
namespace controllers\admin;
class ConsoleAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Rights,
		\core\traits\RequestHandler,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\core\traits\controllers\Authorization,
		\core\traits\controllers\Breadcrumbs;

	protected $permissibleActions = array(
		'console',
		'viewItem',
		'justViewItem',
		'viewItemAndHideNotice'
	);

	public function __construct()
	{
		parent::__construct();
		$this->_config = new \modules\console\lib\ConsoleItemConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		$this->console();
	}
	
	protected function console()
	{
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);
		
		$objects = $this->getObject($this->objectsClass);
		$objects->excludeArchive()
				->setOrderBy('`date` DESC')
				->setPager($itemsOnPage);
			
		$this->setContent('objects', $objects)
			 ->setContent('pager', $objects->getPager())
			 ->setContent('pagesList', $objects->getQuantityItemsOnSubpageListArray())
			 ->includeTemplate('console');
	}
	
	protected function viewItem()
	{
		if(  $this->setAction($this->getREQUEST()[1])->isPermissibleAction() ) {
			$this->callAction(array());
		}
		
	}
	
	protected function justViewItem()
	{
		$object = $this->getObject($this->objectClass, $this->getREQUEST()[0]);
		$object->setAsViewed();
		header('Location: '.$object->url);
	}
	
	protected function viewItemAndHideNotice()
	{
		$object = $this->getObject($this->objectClass, $this->getREQUEST()[0]);
		$object->setAsArchived();
		header('Location: '.$object->url);
	}


}
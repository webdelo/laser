<?php
namespace controllers\admin;
class PromoCodesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Categories,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const STATUS_DELETED = 3;

	protected $permissibleActions = array(
		'promoCodes',
		'add',
		'edit',
		'promoCode',
		'remove',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

		/* Start: Categories Trait Methods*/
		'categories',
		'categoryAdd',
		'categoryEdit',
		'category',
		'removeCategory',
		'getMainCategories',
		'changeCategoriesPriority',
		/*   End: Categories Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\promoCodes\lib\PromoCodeConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->promoCodes();
	}

	protected function promoCodes ()
	{
		$this->checkUserRightAndBlock('promoCodes');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);

		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		$this->modelObject->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'promoCodes');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('promoCode_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->setObject($this->_config->getObjectClass(), $objectId);
		}
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('promoCode_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function promoCode()
	{
		$this->checkUserRightAndBlock('promoCode');
		$this->useRememberPastPageList();

		$promoCode = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$promoCode = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editPromoCode' => 'Параметры');

		$promoCodes = new $this->objectsClass;
		$this->setContent('promoCode', $promoCode)
			 ->setContent('object', $promoCode) // Need for images template
			 ->setContent('objects', $promoCodes) // Need for images template
			 ->setContent('tabs', $tabs)
			 ->setContent('promoCodes', $promoCodes)
			 ->setContent('statuses', $promoCodes->getStatuses())
			 ->setContent('mainCategories', $promoCodes->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'promoCode');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('promoCode_delete');
		if (isset($this->getREQUEST()[0]))
			$promoCodesId = (int)$this->getREQUEST()[0];

		if (!empty($promoCodesId)) {
			$promoCodes = $this->getObject($this->objectClass, $promoCodesId);
			$this->ajaxResponse($promoCodes->remove());
		}
	}

}
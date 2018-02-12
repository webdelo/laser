<?php
namespace controllers\admin;
class CitiesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Categories,
		\core\traits\controllers\Images,
		\core\traits\controllers\Files,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'cities',
		'add',
		'edit',
		'city',
		'remove',
		'parameterEdit',
		'getParameterBlocks',
		'ajaxGetParameterBlocks',

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

		/* Start: Images Trait Methods*/
		'uploadImage',
		'addImagesFromEditPage',
		'removeImage',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editImage',
		'getTemplateToEditImage',
		'ajaxGetImagesBlock',
		'ajaxGetImagesListBlock',
		/*   End: Images Trait Methods*/

		/* Start: Files Trait Methods*/
		'uploadFile',
		'addFilesFromEditPage',
		'removeFile',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editFile',
		'getTemplateToEditFile',
		'ajaxGetFilesBlock',
		'ajaxGetFilesListBlock',
		'getFileIcon',
		'download'
		/*   End: Files Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\cities\lib\CityConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->cities();
	}

	protected function cities ()
	{
		$this->checkUserRightAndBlock('cities');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$category = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$text = (empty($this->getGET()['text'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['text']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($category))
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $category);

		if (!empty($status))
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'cities');
	}

	public function add()
	{
		$this->checkUserRightAndBlock('city_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->setObject($this->_config->getObjectClass(), $objectId)
				 ->addImages();
		}
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('city_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function city()
	{
		$this->checkUserRightAndBlock('city');
		$this->useRememberPastPageList();

		$city = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$city = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editCity' => 'Параметры и фото');
		if ($city->id)
			$tabs = array_merge($tabs, array('parameters' => 'Параметры', 'files' => 'Файлы'));

		$cities = new $this->objectsClass;
		$this->setContent('city', $city)
			 ->setContent('object', $city) // Need for images template
			 ->setContent('objects', $cities) // Need for images template
			 ->setContent('tabs', $tabs)
			 ->setContent('cities', $cities)
			 ->setContent('statuses', $cities->getStatuses())
			 ->setContent('mainCategories', $cities->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'city');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('city_delete');
		if (isset($this->getREQUEST()[0]))
			$cityId = (int)$this->getREQUEST()[0];

		if (!empty($cityId)) {
			$city = $this->getObject($this->objectClass, $cityId);
			$this->ajaxResponse($city->remove());
		}
	}

	protected function parameterEdit()
	{
		$object = $this->getObject($this->objectClass, $this->getPOST()->id);
		return $this->ajaxResponse($object->getParameters()->edit($this->getPOST()->parametersValues));
	}

	protected function ajaxGetParameterBlocks()
	{
		echo $this->getParameterBlocks($this->getGET()->cityId);
	}

	protected function getParameterBlocks($cityId)
	{
		$city = $this->getObject('\modules\cities\lib\City', $cityId);
		$this->setContent('object', $city)
			 ->setContent('parameters', $this->getParameters())
			 ->includeTemplate('parameterBlocks');

	}

	private function getParameters() {
		return $this->getObject('\modules\parameters\lib\Parameters')->getParametersByCategoryAlias('cities');
	}
}
?>

<?php
namespace controllers\admin;
class RealtiesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\TranslateCategories,
		\core\traits\controllers\Images,
		\core\traits\controllers\Files,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\AddingRealtyTrait,
		\controllers\admin\traits\ClientDecoratorTrait,
		\controllers\admin\traits\AddressesDecoratorTrait,
		\controllers\admin\traits\PropertiesRelationsTrait,
		\controllers\admin\traits\ParametersRelationsTrait,
		\controllers\admin\traits\PricesDecoratorTrait,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const STATUS_DELETED = 3;

	protected $permissibleActions = array(
		'realties',
		'add',
		'edit',
		'editField',
		'realty',
		'newRealty',
		'parameterEdit',
		'remove',
		'ajaxSaveCoordinates',

		'saveClientInRealty',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

		/* Start: Parameters Trait Methods*/
		'getParameterBlocks',
		'ajaxGetParameterBlocks',
		/* End: Parameters Trait Methods*/

		/* Start: Prices Trait Methods*/
		'getPricesBlocks',
		'ajaxGetPricesBlocks',
		'addPrice',
		'editPrice',
		'editPeriods',
		'deletePrice',
		'getDaysByStartMonth',
		'getDaysByEndMonth',
		/* End: Prices Trait Methods*/

		/* Start: Properties Trait Methods*/
		'getPropertiesBlocks',
		'ajaxGetPropertiesBlocks',
		'ajaxEditPropertyRelation',
		/* End: Properties Trait Methods*/

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
		$this->_config = new \modules\realties\lib\RealtyConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->realties();
	}

	protected function realties ()
	{
		$this->checkUserRightAndBlock('realties');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$category = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$text = (empty($this->getGET()['text'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['text']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);
		$client = (empty($this->getGET()['clientId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['clientId']);
		
		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($category))
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $category);

		if (!empty($status))
			$this->modelObject->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);
		
		if (!empty($client))
			$this->modelObject->setSubquery('AND `clientId` LIKE \'%?s%\'', $client);

		$this->modelObject->setOrderBy('`priority` DESC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'realties');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('realty_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->setObject($this->_config->getObjectClass(), $objectId)
				 ->addImages();
		}
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('realty_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id']);
		$res =$this->modelObject->editWithParameters($this->getPOST(), array(
			'statusId', 'categoryId',  'priority', 'code','rooms', 
			'bathRooms', 'guests', 'guestsPay', 'minRent', 'name', 'description',
			'realtyRules', 'date', 'metaTitle', 'metaKeywords', 'metaDescription', 
			'headerText', 'entryTime', 'exitTime', 'anyTime', 'guestsFixed'
		));
		$this->ajax($res, 'ajax', true);
	}

	protected function editField()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$field = array_keys($_POST)[0];
		$value = array_shift($_POST);

		$this->ajaxResponse($object->editField($value, $field));
	}

	protected function realty()
	{
		$this->checkUserRightAndBlock('realty');
		$this->useRememberPastPageList();

		$realty = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$realty = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editRealty' => 'Параметры и фото');
		if ($realty->id) {
			$tabs = array_merge($tabs, array(
				'prices' => 'Цены',
				'properties' => 'Свойства',
				'parameters' => 'Параметры',
				'files' => 'Файлы'
			));
		}

		$realties = new $this->objectsClass;
		$this->setContent('realty', $realty)
			 ->setContent('object', $realty) // Need for images template
			 ->setContent('objects', $realties) // Need for images template
			 ->setContent('tabs', $tabs)
			 ->setContent('realties', $realties)
			 ->setContent('statuses', $realties->getStatuses())
			 ->setContent('mainCategories', $realties->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'realty');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('realty_delete');
		if (isset($this->getREQUEST()[0]))
			$realtyId = (int)$this->getREQUEST()[0];

		if (!empty($realtyId)) {
			$realty = $this->getObject($this->objectClass, $realtyId);
			$this->ajaxResponse($realty->remove());
		}
	}

	private function getRealtyParameters()
	{
		return $this->getObject('\modules\parameters\lib\Parameters')->getParametersByCategoryAlias('realties')->setOrderBy('`priority` ASC');
	}

	private function getLocations()
	{
		return new \modules\locations\lib\Locations();
	}

	public function getLocation()
	{
		$loc = new \core\locations\geo\GeoLocator($_SERVER['REMOTE_ADDR']);
		echo $loc->lat.' '.$loc->lng;
		echo $loc->country.' '.$loc->region.' '.$loc->city;
	}

	public function changePropertiesValuesPriority ()
	{
		$data = $this->getREQUEST()['data'];
		$object = new $this->objectClass($this->getPOST()->objectId);
		$counter = 0;
		foreach ($data as $objectId=>$priority) {
			$counter++;
			$propertyValue = $object->getPropertyValueById($objectId);
			$this->setObject($propertyValue)
				->modelObject->edit(array('id'=>$objectId, 'priority'=>$counter), array('id', 'priority'));
			$this->modelObject->getErrors();
		}
		echo 1;
	}

	public function showCitiesInRealties() {
		$objects = new \modules\realties\lib\Realties;

		foreach($objects as $object) {
			echo $object->getAddress()->getLocationsCity()->name.'<br/>';
		}
	}
	
	public function GetRealtiesQuantityByStatus($statusId) {
		$this->checkUserRightAndBlock('realties');
		$realtiesObject = new $this->objectsClass;
		$where['query'] = 'mt.statusId=?d';
		$where['data'] = array($statusId);
		
		if($this->isAuthorisatedUserAnManager()){
			$partner = $this->getAuthorizatedManagerPartner();
			$where['query'] .= ' AND mt.partnerId=?d';

			$where['data'][] = $partner->id;
		}
		
		$filter = array('where' => $where);
		return $realtiesObject->countAll($filter);
	}

}

<?php
namespace controllers\admin;
class ParametersAdminController extends \controllers\base\Controller
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
		'parameters',
		'add',
		'edit',
		'editField',
		'parameter',
		
		'translateParameterValues',
		'actionTranslate',
		
		'remove',
		'changeParametersValuesPriority',
		'addParameterValue',
		'editParameterValue',
		'deleteParameterValue',
		'ajaxGetParametersValuesBlock',
		'getParametersByCategoryIdInHTMLOptions',

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

	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\parameters\lib\ParameterConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->parameters();
	}

	protected function parameters ()
	{
		$this->checkUserRightAndBlock('parameters');
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

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'parameters');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('parameters_add');

		$this->ajax($this->addParameter($this->getPOST()), 'ajax', true);
	}

	private function addParameter($post)
	{
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($post, $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->getObject($this->objectClass, $objectId)->getAdditionalCategories()->edit($post->additionalCategories);
			$this->setObject($this->_config->getObjectClass(), $objectId)
				 ->addImages();
		}
		return $objectId;
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('parameters_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}
	
	protected function editField()
	{
		$this->checkUserRightAndBlock('parameters_edit');
		$fieldKey = array_keys($_POST)[0];
		$field    = array_shift($_POST);
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->editField($field, $fieldKey), 'ajax', true);
	}

	protected function parameter()
	{
		$this->checkUserRightAndBlock('parameter');
		$this->useRememberPastPageList();

		$parameter = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$parameter = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editParameter' => 'Параметры');

		$parameters = new $this->objectsClass;
		$this->setContent('parameter', $parameter)
			 ->setContent('object', $parameter) // Need for images template
			 ->setContent('objects', $parameters) // Need for images template
			 ->setContent('tabs', $tabs)
			 ->setContent('parameters', $parameters)
			 ->setContent('chooseMethods', new \modules\parameters\components\chooseMethods\lib\ChooseMethods)
			 ->setContent('statuses', $parameters->getStatuses())
			 ->setContent('mainCategories', $parameters->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'parameter');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('parameters_delete');
		if (isset($this->getREQUEST()[0]))
			$parameterId = (int)$this->getREQUEST()[0];

		if (!empty($parameterId)) {
			$parameter = $this->getObject($this->objectClass, $parameterId);
			$this->ajaxResponse($parameter->remove());
		}
	}

	protected function ajaxGetParametersValuesBlock()
	{
		echo $this->getParameterValuesBlock($this->getREQUEST()[0]);
	}

	protected function getParameterValuesBlock ($parameterId) {
		$parameter = $this->getObject($this->objectClass, (int)$parameterId);
		$this->setContent('parameter', $parameter)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'parametersValues');
	}

	protected function addParameterValue()
	{
		$parameterValues = $this->getObject('\modules\parameters\components\parametersValues\lib\ParameterValues');

		$objectId =  $this->setObject($parameterValues)
						  ->modelObject
						  ->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function editParameterValue()
	{
		$parameterValue = $this->getObject('\modules\parameters\components\parametersValues\lib\ParameterValue', $this->getPOST()['id']);
		$edit = $this->setObject($parameterValue)->modelObject->edit($this->getPOST());

		$this->ajax($edit, 'ajax', true);
	}

	protected function deleteParameterValue()
	{
		if (isset($this->getREQUEST()[0]))
			$parameterValueId = (int)$this->getREQUEST()[0];

		if (!empty($parameterValueId)) {
			$parameterValue = $this->getObject('\modules\parameters\components\parametersValues\lib\ParameterValue', $parameterValueId);
			$this->ajaxResponse($parameterValue->remove());
		}
	}

	protected function changeParametersValuesPriority ()
	{
		$data = $this->getREQUEST()['data'];
		$counter = 0;
		foreach ($data as $objectId=>$priority) {
			$counter++;
			$parameterValue = new \modules\parameters\components\parametersValues\lib\ParameterValue((int)$objectId);
			$this->setObject($parameterValue)
				->modelObject->edit(array('id'=>$objectId, 'priority'=>$counter), array('id', 'priority'));
			$this->modelObject->getErrors();
		}
		echo 1;
	}
	
	protected function translateParameterValues()
	{
		$parameter = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$parameter = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
		
		$this->setContent('parameter', $parameter)
			 ->includeTemplate($this->_config->getAdminTemplateDir().'translateParametersValues');
	}
	
	protected function actionTranslate()
	{
		
		$flag = true;
		foreach ( $this->getPOST()['values'] as $valId=>$value ) {
			$parameterValue = $this->getObject('\modules\parameters\components\parametersValues\lib\ParameterValue', $valId);
			$edit = $this->setObject($parameterValue)->modelObject->edit($value);
			if (!$edit) {
				$flag = false;
			}
		}
		$this->ajax($flag, 'ajax', true);
	}
}

<?php
/*
This methods must be in $permissibleActions!
protected $permissibleActions = array
	(
		// Start: Images Trait Methods
		'categories',
		'categoryAdd',
		'categoryEdit',
		'category',
		'removeCategory',
		'getMainCategories',
		//   End: Images Trait Methods
	);
 */
namespace core\traits\controllers;
trait Categories
{
	protected function categories ()
	{
		$this->rememberPastPageList($_REQUEST['controller']);

		$objects = new $this->objectsClass;
		$objectsCategories = $objects->getCategories();

		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$parentCategory = (empty($this->getGET()['parentCategoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['parentCategoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$objectsCategories->setSubquery('AND `id` = ?d', $this->getGET()['id']);
		if (!empty($parentCategory))
			$objectsCategories->setSubquery('AND `parentId` = ?d', $parentCategory=='baseCategories' ? 0 : $parentCategory);
		if (!empty($status))
			$objectsCategories->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);
		if (!empty($this->getGET()['id']))
			$objectsCategories->setSubquery('AND `id` = ?d', $this->getGET()['id']);
		if (!empty($alias))
			$objectsCategories->setSubquery('AND `alias` LIKE \'%?s%\'', $alias);
		if (!empty($name))
			$objectsCategories->setSubquery('AND `name` LIKE \'%?s%\'', $name);

		$objectsCategories->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('categories', $objectsCategories)
			->setContent('pager', $objectsCategories->getPager())
			->setContent('pagesList', $objectsCategories->getQuantityItemsOnSubpageListArray())
			->setContent('statuses', $objectsCategories->getStatuses())
			->setContent('objects', $objects)
			->includeTemplate(lcfirst($this->objectsClassName).'Categories');
	}

	protected function categoryAdd()
	{
		$objects = new $this->objectsClass;
		$this->setObject($objects->getCategories())->ajax($this->modelObject->add($this->getPOST()), 'ajax');
	}

	protected function categoryEdit()
	{
		$objects = new $this->objectsClass;
		$category = $objects->getCategories()[(int)$this->getPOST()['id']];
		$this->setObject($category)->ajax($category->edit($this->getPOST()), 'ajax');
	}

	protected function category ()
	{
		$this->useRememberPastPageList();

		$objectsObject = new $this->objectsClass;
		$category = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$category = new \core\modules\categories\Category((int)$this->getREQUEST()[0], $this->_config);

		$tabs = array('editCategory' => 'Основная информация');
		$this->setContent('mainCategories', $objectsObject->getMainCategories())
			 ->setContent('statuses', $objectsObject->getMainCategories()->getStatuses())
			 ->setContent('tabs', $tabs)
			 ->setContent('object', $category)
			 ->includeTemplate(lcfirst($this->objectsClassName).'Category');
	}

	protected function removeCategory()
	{
		if (isset($this->getREQUEST()[0]))
			$categoryId = (int)$this->getREQUEST()[0];

		if (!empty($categoryId)) {
			$category = new \core\modules\categories\Category($categoryId, $this->_config);
			if ($category->delete())
				echo true;
			else {
				echo $this->ajaxResponse(array(
					'message' => 'Error! System can\'t delete category'
				));
			}
		}
	}

	public function changeCategoriesPriority ()
	{
		$data = $_GET['data'];
		$counter = 1;
		foreach ($data as $objectId=>$priority) {
			$counter++;
			$object = new \core\modules\categories\Category((int)$objectId, $this->_config);
			$object->edit(array('priority'=>$counter));
		}
	}

}

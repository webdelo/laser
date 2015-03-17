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
trait TranslateCategories
{
	use Categories;
	
	protected function category ()
	{
		$this->useRememberPastPageList();

		$objectsObject = new $this->objectsClass;
		$category = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$category = new \core\modules\categories\TranslateCategory((int)$this->getREQUEST()[0], $this->_config);

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
			$category = new \core\modules\categories\TranslateCategory($categoryId, $this->_config);
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
			$object = new \core\modules\categories\TranslateCategory((int)$objectId, $this->_config);
			$object->edit(array('priority'=>$counter));
		}
	}

}
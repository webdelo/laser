<?php
namespace controllers\admin\traits;
trait ListActionsAdminControllerTrait
{
	protected function changePriority ()
	{
		$data = $this->getREQUEST()['data'];
		$counter = 0;
		foreach ($data as $objectId=>$priority) {
			if (!empty($objectId) && !empty($priority)) {
				$counter++;
				$this->setObject($this->_config->getObjectClass(), (int)$objectId)
					->modelObject->editField($counter, 'priority');
				$this->modelObject->getErrors();
			}
		}
		echo 1;
	}

	protected function groupActions()
	{
		$data = $this->getPost();
		if (empty($data['categoryId']) && empty($data['statusId']))
			return  $this->ajaxResponse(array('categoryId'=>'Выберите изменяемое свойство!'));

		if (empty($data['group']))
			return $this->ajaxResponse(array('actionButton'=>'Выберите объекты для изменения в списке!'));

		foreach ($data['group'] as $key=>$objectId)
			$this->setObject($this->getOperationObject($objectId))->ajax($this->modelObject->edit($this->modifyData($data, $objectId)), 'ajax', true);
	}

	private function getOperationObject($objectId)
	{
		if(isset($this->getGet()['categoriesAction']))
			return new \core\modules\categories\Category($objectId, $this->_config);
		return $this->getObject($this->objectClass, $objectId);
	}

	private function modifyData($data, $objectId)
	{
		if (empty($data['categoryId']))
			unset($data['categoryId']);
		if (empty($data['statusId']))
			unset($data['statusId']);

		if(isset($this->getGet()['categoriesAction']) && isset($data['categoryId'])){
			$data['parentId'] = $data['categoryId'];
			$data['parentId'] = $data['parentId']=='main' ? 0 : $data['parentId'];
			unset($data['categoryId']);
		}

		$object = $this->getOperationObject($objectId);
		if(is_array($object->getAdditionalCategoriesArray()))
			$data['additionalCategories'] = $object->getAdditionalCategoriesArray();

		return $data;
	}

	protected function groupRemove ()
	{
		if (empty($this->getPOST()['group']))
			return $this->ajaxResponse(array('removeButton'=>'Выберите объекты для удаления в списке!'));

		foreach($this->getPOST()['group'] as $key=>$objectId)
			if ($this->getOperationObject($objectId)->delete())
				$result = true;

		$this->ajaxResponse( $result ? $result : array('removeButton' => 'Ошибка при удалении объектов в списке!') );
	}

	protected function rememberPastPageList($objectClass)
	{
		$sessionFilter = \core\SessionFilters::getInstance();
		$pastTypePage = \core\utils\DataAdapt::textValid($sessionFilter->get('pageType','type'));
		$pastUri = \core\utils\DataAdapt::textValid($sessionFilter->get($objectClass,'pastUri'));
		if($pastTypePage=='object'){
			header('Location: '.str_replace('amp;', '', $pastUri));
		}
		$sessionFilter->set($objectClass, array('pastUri'=>$_SERVER['REQUEST_URI']));
		$sessionFilter->set('pageType', array('type'=>'list'));
	}

	protected function useRememberPastPageList()
	{
		$sessionFilter = \core\SessionFilters::getInstance();
		$sessionFilter->set('pageType', array('type'=>'object'));
	}
}
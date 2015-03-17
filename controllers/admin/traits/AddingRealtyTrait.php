<?php
//	Для вывода своего темплейта нужно переопределить два метода
//	1. getTemplateWithClient
//	2. getTemplateToSearchClient
//	В них нужно указать путь к другим файлам шаблонов
namespace controllers\admin\traits;
trait AddingRealtyTrait
{
	
	private $addingStatus   = 5;
	private $addingCategory = 2;
	
	private function getRealtyObject($id)
	{
		return !empty($id) ? new $this->objectClass($id) : $this->getNoop() ;
	}
	
	protected function newRealty()
	{
		$object  = $this->getRealtyObject(isset($_REQUEST[0])?$_REQUEST[0]:null);
		$this->setContent('object', $object)
			 ->includeTemplate('newRealty');
	}
	
	protected function saveClientInRealty()
	{
		if ( $this->isNotNoop($this->getPOST()->id) )
			$result = $this->clientToRealty($this->getPOST()->id, $this->getPOST()->clientId);
		else
			$result = $this->addRealtyBeforeClientSave($this->getPOST()->clientId);
		
		$this->ajax($result, 'ajax', true);
	}
	
	protected function addRealtyBeforeClientSave($clientId)
	{
		$objects = new $this->objectsClass;
		return $this->setObject($objects)->modelObject->add( array('statusId'=>$this->addingStatus, 'categoryId'=>$this->addingCategory, 'clientId'=>$clientId ) );
	}
	
	protected function clientToRealty($objectId, $clientId)
	{
		$object = $this->getRealtyObject($objectId);
		return $this->setObject($object)->modelObject->editField($clientId, 'clientId');
	}
	
	public function ajaxGetClientDetails()
	{
		echo $this->getClientDetails($this->getPOST()->clientId);
	}
	
	public function getClientDetails($id)
	{
		$this->setContent('object', $this->getRealtyObject($id))
			 ->includeTemplate('newRealty/clientDetails');
	}
	
	public function ajaxGetClientBlock()
	{
		echo $this->getClientBlock($this->getGET()->objectId);
	}
	
	public function getClientBlock($id = null)
	{
		$object = $this->getRealtyObject($id);
		if ($object->clientId) {
			return $this->getClientDetails($id);
		} else {
			return $this->getSearchBlock($id);
		}
	}
	
	public function getSearchBlock($id)
	{
		return $this->setContent('object', $this->getRealtyObject($id))
					->includeTemplate('newRealty/searchClient');
	}
	
	public function ajaxGetMainBlock()
	{
		echo $this->getMainBlock($this->getGET()->objectId);
	}
	
	public function getMainBlock($id = null)
	{
		if ( empty($id) ) {
			$this->includeTemplate('newRealty/mainBlockNoop');
		} else {
			$objects = new $this->objectsClass;
			$this->setContent('object', $this->getRealtyObject($id))
				 ->setContent('mainCategories', $objects->getMainCategories(1))
				 ->setContent('categories', $objects->getCategories())
			     ->includeTemplate('newRealty/mainBlock');
		}
	}
}

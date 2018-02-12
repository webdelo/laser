<?php
//	Для вывода своего темплейта нужно переопределить два метода
//	1. getTemplateWithClient
//	2. getTemplateToSearchClient
//	В них нужно указать путь к другим файлам шаблонов
namespace controllers\admin\traits;
trait ClientDecoratorTrait
{
	private $clientClass = '\modules\clients\lib\Client';
	private $clientsClass = '\modules\clients\lib\Clients';
	
	public function getClientDecoratorTemplate( $id = NULL )
	{
		if ( $id ) {
			$this->setContent('object', new $this->objectClass($id))->includeTemplate('clientDecoratorTrait/client');
		} else {
			$this->includeTemplate('clientDecoratorTrait/clientNoop');
		}
	}
	
	public function ajaxGetClientTemplate() {
		echo $this->getClientTemplate($this->getREQUEST()[0]);
	}
	
	public function getClientTemplate( $id = NULL )
	{
		$object = new $this->objectClass($id);
		$this->setContent('object', new $this->objectClass($id));
		if ($object->clientId) {
			$this->getTemplateWithClient($object->clientId);
		} else {
			$this->getTemplateToSearchClient();
		}
	}
	
	public function getTemplateWithClient()
	{
		$this->includeTemplate('clientDecoratorTrait/clientDetails');
	}
	
	public function getTemplateToSearchClient()
	{
		$this->includeTemplate('clientDecoratorTrait/searchClient');
	}
	
	public function deleteClientRelation($objectId)
	{
		$object = new $this->objectClass($objectId);
		return $object->editField(0, 'clientId');
	}
	
	public function ajaxDeleteClientRelation()
	{
		$this->ajaxResponse($this->deleteClientRelation($this->getREQUEST()[0]));
	}
	
	public function ajaxSearchClientsToAutosuggest()
	{
		$search = explode(' ', $this->getGET()->q);
		$clients = $this->getObject($this->clientsClass);
		$query = ' AND (';
		foreach ( $search as $word ) {
			$query .= '
					(
					 (
						`phone` LIKE \'%'.$word.'%\' OR
						`company` LIKE \'%'.$word.'%\' OR
						`surname` LIKE \'%'.$word.'%\' OR
						`name` LIKE \'%'.$word.'%\' OR
						`patronimic` LIKE \'%'.$word.'%\'
					 ) OR
					`id` IN (
						SELECT `id` FROM `tbl_user_logins` WHERE `login`  LIKE \'%'.$word.'%\'
					 )
				)
			AND';

		}
		$query = substr($query, 0, -3);
		$query .=')';
		$clients->setSubquery($query);

		foreach ($clients as $object) {
				$json = array();
				$json['value'] = $object->id;
				$json['name'] = $object->getLogin().' ['.$object->phone.'] '. $object->getAllName();
				$data[] = $json;
		}

		if (!isset($data)) {
			$data[0]['value'] = 'no value';
			$data[0]['name']  = 'Результатов не найдено';
			$data[0]['code']  = '';
		}

		echo json_encode($data);
	}
	
	public function clientRelationSave($objectId, $clientId)
	{
		$object = new $this->objectClass($objectId);
		return $object->editField($clientId, 'clientId');
	}
	
	public function ajaxClientRelationSave()
	{
		$this->ajaxResponse($this->clientRelationSave($this->getPOST()->id, $this->getPOST()->clientId));
	}
	
}

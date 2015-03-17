<?php
namespace controllers\admin;
class ClientsAdminController extends \controllers\base\ClientsBaseController
{
	use	\core\traits\Pager,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Images,
		\core\traits\controllers\Templates,
		\core\traits\Settings,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	protected $permissibleActions = array(
		'add',
		'edit',
		'client',
		'editLoginById',
		'editClientById',
		'remove',
		'ajaxGetAutosuggestClients',
		'ajaxGetClientById',
		'ajaxEditPassword',
		'editClient',
		'editDeliveryAddress',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

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
	}

	protected function defaultAction()
	{
		return $this->clients();
	}

	protected function clients()
	{
		$this->checkUserRightAndBlock('clients');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);

		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$allName = (empty($this->getGET()['allName'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['allName']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$company = (empty($this->getGET()['company'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['company']);
		$email = (empty($this->getGET()['email'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['email']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);


		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($status))
			$this->modelObject->loadWithRemovedObjects()->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($allName))
			$this->modelObject->setSubquery('AND (`name` LIKE \'%?s%\'      OR     `patronimic` LIKE \'%?s%\'    OR     `surname` LIKE \'%?s%\' )', $allName, $allName, $allName);

		if (!empty($company))
			$this->modelObject->setSubquery('AND `company` LIKE \'%?s%\'', $company);

		if (!empty($email))
			$this->modelObject->setSubquery('AND `id` IN (SELECT `id` FROM `tbl_user_logins` WHERE `login` LIKE \'%?s%\')', $email);

		$this->modelObject->setOrderBy('`id` DESC')->setPager($itemsOnPage);

		$this->setContent('clients', $this->modelObject)
			->setContent('statuses', $this->modelObject->getStatuses())
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate('clients');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('client_add');
		$_POST['passwordConfirm'] = $_POST['password'];
		parent::add();

	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('client_edit');
		parent::edit();
	}

	public function NormalizeClientAddress($Data)
	{
		$NewData = $Data;

		if (mb_substr($NewData['region'], 0, 3,'UTF-8') == "р. ") { $NewData['region'] = substr($NewData['region'], 4);
		} elseif (mb_substr($NewData['region'], 0, 2,'UTF-8') == "р.") { $NewData['region'] = substr($NewData['region'], 3);}

		if (mb_substr($NewData['city'], 0, 3,'UTF-8') == "г. ") { $NewData['city'] = substr($NewData['city'], 4);
		} elseif (mb_substr($NewData['city'], 0, 2,'UTF-8') == "г.") { $NewData['city'] = substr($NewData['city'], 3);}

		if (mb_substr($NewData['street'], 0, 4,'UTF-8') == "ул. ") { $NewData['street'] = substr($NewData['street'], 6);
		} elseif (mb_substr($NewData['street'], 0, 3,'UTF-8') == "ул.") { $NewData['street'] = substr($NewData['street'], 5);}

		if (mb_substr($NewData['home'], 0, 3,'UTF-8') == "д. ") { $NewData['home'] = substr($NewData['home'], 4);
		} elseif (mb_substr($NewData['home'], 0, 2,'UTF-8') == "д.") { $NewData['home'] = substr($NewData['home'], 3);}

		if (mb_substr($NewData['block'], 0, 6,'UTF-8') == "корп. ") { $NewData['block'] = substr($NewData['block'], 10);
		} elseif (mb_substr($NewData['block'], 0, 5,'UTF-8') == "корп.") { $NewData['block'] = substr($NewData['block'], 9);}

		if (mb_substr($NewData['flat'], 0, 4,'UTF-8') == "кв. ") { $NewData['flat'] = substr($NewData['flat'], 6);
		} elseif (mb_substr($NewData['flat'], 0, 3,'UTF-8') == "кв.") { $NewData['flat'] = substr($NewData['flat'], 5);}

		return $NewData;
	}

	protected function editDeliveryAddress()
	{
		$client = $this->getObject($this->objectClass, $this->getPOST()['objectId']);
		$NewData = $this->NormalizeClientAddress($this->getPOST());
		$this->setObject($client)->ajax($this->modelObject->edit($NewData), 'ajax', true);
	}

	protected function editClient()
	{
		$client = $this->getObject($this->objectClass, $this->getGET()->objectId);
		$this->setObject($client)->ajax($this->modelObject->edit($this->getPOST()), 'ajax', true);
	}

	protected function client()
	{
		$this->checkUserRightAndBlock('client');
		$this->useRememberPastPageList();

		$object = new \core\Noop();

		if (isset($this->getREQUEST()[0])) {
			$object = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);
		}
		$tabs = array(
			'editClient' => 'Общая информация',
			'imagesTab' => 'Изображения'
		);

		$objects = new \modules\clients\lib\Clients();
		$countries = (new \modules\locations\lib\Locations)->getCountries();

		$this->setContent('object', $object)
			 ->setContent('tabs', $tabs)
			 ->setContent('statuses', $objects->getStatuses())
			 ->setContent('countries', $countries)
			 ->includeTemplate('client');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('client_delete');
		if (isset($this->getREQUEST()[0]))
			$objectId = (int)$this->getREQUEST()[0];

		if (!empty($objectId)) {
			$object = $this->getObject($this->_config->getObjectClass(), $objectId);
			$this->ajaxResponse($object->remove());
		}
	}

	protected function ajaxGetAutosuggestClients()
	{
		$this->setObject($this->objectsClass);
		$clients = $this->modelObject->setSubquery('AND ( `name`LIKE \'%?s%\'   OR   `surname`LIKE \'%?s%\'
										OR   `patronimic`LIKE \'%?s%\'   OR   `company`LIKE \'%?s%\'
										) ', $_GET["q"], $_GET["q"], $_GET["q"], $_GET["q"]);

		foreach($clients as $client){
			$json = array();
			$json['value'] = $client->id;
			$json['name'] = $client->surname.' '.$client->name;
			if($client->patronimic)
				$json['name'] = $json['name'].' '.$client->patronimic;
			$json['name'] = $json['name'].' - '.$client->city;
			if($client->company)
				$json['name'] = $json['name'].' ('.$client->company.')';
			$json['login'] = $client->getLogin();
			$data[] = $json;
		}

		if(!isset($data)){
			$data[0]['value'] = 'no value';
			$data[0]['name'] = 'Результатов не найдено';
			$data[0]['login'] = 'no login';
		}

		echo json_encode($data);
	}

	protected function ajaxGetClientById()
	{
		$client = $this->getObject($this->_config->getObjectClass(), $this->getPost()['clientId']);

		$name = $client->surname.' '.$client->name;
		if($client->patronimic)
			$name = $name.' '.$client->patronimic;
		$name = $name.' - '.$client->city;
		if($client->company)
			$name = $name.' ('.$client->company.')';

		$array = array(
			'name'=>$name,
			'email'=>$client->getLogin()
		);
		echo json_encode($array);
	}

	protected function ajaxEditPassword()
	{
		$this->checkUserRightAndBlock('client_edit');
		$this->setObject($this->objectClass,$this->getPOST()['id'])
			 ->ajax($this->modelObject->editPassword($this->getPOST()['password'], $this->getPOST()['passwordConfirm']), 'ajax');
	}

	protected function editLoginById()
	{
		$this->ajax($this->editLogin(), 'ajax', true);
	}

	protected function editClientById()
	{
		$client = $this->getObject($this->objectClass, $this->getPOST()->id);
		$this->setObject($client)->ajax($this->modelObject->edit($this->getPOST()), 'ajax', true);
	}
}

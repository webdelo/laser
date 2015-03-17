<?php
namespace controllers\front\cabinet;
class DialogsFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\controllers\front\traits\CabinetFrontControllerTrait,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\controllers\Authorization;

	private $defaultDetailsPage = 'dialog';

	protected $permissibleActions = array(
		'dialogs',
		'dialog',
		'ajaxGetDialogList',
		'ajaxSetAsReaded',
		'ajaxGetDialogsList'
	);

	public function __construct()
	{
		parent::__construct();
	}

	public function __call($name, $arguments)
	{
		if($this->getController('Authorization')->isGuest())
			return $this->getController('authorization')->authorizationPage();
		elseif( !$this->getREQUEST()->action )
			return $this->defaultAction();
		elseif($this->setAction($this->getDetailsAction($name))->isPermissibleAction()) {
			return $this->callAction($arguments);
		} else
			return $this->redirect404();
	}

	public function getDetailsAction($name)
	{
		return ( (int)$name ) ? $this->defaultDetailsPage : $name;
	}

	public function defaultAction()
	{
		$this->dialogs();
	}

	protected function dialogs()
	{
		return $this->setTitle('Переписка с другими пользователями')
					->setDescription('Переписка с другими пользователями')
					->setContent('subpage', 'dialogs/dialogs')
					->includeTemplate('cabinet/main');
	}

	protected function ajaxGetDialogsList()
	{
		$objects = $this->getBookingList();
		$objects->filterByStatusAlias($this->getREQUEST()[0]);
		$objects->filterByRealtyCode($this->getGET()->realtyCode);
		$objects->setOrderBy(' `priority` DESC ');

		$objects->setPager(5);
		$this->setContent('objects', $objects)
			 ->setContent('pager', $objects->getPager())
			 ->includeTemplate('cabinet/dialogs/dialogsList');
	}

	protected function ajaxGetDialogList()
	{
		$this->includeDialogList($this->getREQUEST()[0]);
	}

	protected function includeDialogList($bookingId)
	{
		$booking = $this->getBookingList()->getObjectByCode($bookingId);
		$messages = $booking->getMessages();
		$messages->setOrderBy(' `date` DESC ');

		$this->setContent('object', $booking)
			 ->setContent('messages', $messages)
			 ->includeTemplate('cabinet/dialogs/dialogList');
	}

	private function checkUsersRights($object)
	{
		$users = array(
			$object->getClient()->id,
			$object->getRealty()->getClient()->id
		);
		return in_array((int)$this->getAuthorizatedUserId(), $users);
	}

	protected function ajaxSetAsReaded()
	{

	}
}
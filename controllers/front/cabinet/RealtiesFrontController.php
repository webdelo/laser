<?php
namespace controllers\front\cabinet;
class RealtiesFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\controllers\front\traits\CabinetFrontControllerTrait,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\controllers\Authorization;

	protected $permissibleActions = array(
		'realties',
		'realty',
		'ajaxBlockRealty',
		'ajaxPublicRealty',
		'ajaxGetRealtiesList'
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
		elseif($this->setAction($name)->isPermissibleAction()) {
			return $this->callAction($arguments);
		}else
			return $this->redirect404();
	}

	public function defaultAction()
	{
		$this->realties();
	}

	protected function realties()
	{
		return $this->setTitle('Ваши объявления')
					->setDescription('Работа и редактирование Ваших объектов')
					->setContent('subpage', 'realties/realties')
					->setContent('statuses', $this->getRealtiesStatuses())
					->includeTemplate('cabinet/main');
	}

	protected function ajaxGetRealtiesList()
	{
		$objects = $this->getRealties();
		if ( $this->getGET()->status && $this->isNotNoop($this->getGET()->status) ) {
			$objects->filterByStatusAlias($this->getGET()->status);
		}
		$objects->setPager(5);
		$objects->setOrderBy(' `date` DESC ');

		$this->setContent('objects', $objects)
			 ->setContent('pager', $objects->getPager())
			 ->includeTemplate('cabinet/realties/realtiesList');
	}

	public function getRealtyTemplateByStatus($object){
		$this->setContent('object', $object)->includeTemplate('/cabinet/realties/realtyTemplates/'.$object->getStatus()->alias);
	}

	public function ajaxRemoveRealty()
	{
		$this->ajaxResponse($this->removeRealty($this->getREQUEST()[0]));
	}

	private function removeRealty($code)
	{
		$realties = new \modules\realties\lib\Realties();
		$realty = $realties->getObjectByCode($code);
		return ($realty->ownerVisit() && $realty->isNotCompleted()) ? $realty->remove() : false;
	}

	public function ajaxBlockRealty()
	{
		$this->ajaxResponse($this->blockRealty($this->getREQUEST()[0]));
	}

	public function blockRealty($code)
	{
		$realties = new \modules\realties\lib\Realties();
		$realty = $realties->getObjectByCode($code);
		return ($realty->ownerVisit()) ? $realty->editField($realties->getConfig()->blockedStatus, 'statusId') : false ;
	}

	public function ajaxPublicRealty()
	{
		$this->ajaxResponse($this->publicRealty($this->getREQUEST()[0]));
	}

	public function publicRealty($code)
	{
		$realties = new \modules\realties\lib\Realties();
		$realty = $realties->getObjectByCode($code);
		return ($realty->ownerVisit()) ? $realty->editField($realties->getConfig()->activeStatus, 'statusId') : false ;
	}

	private function checkUserRightForObject($object)
	{
		return ( $object->clientId == $this->getAuthorizatedUser()->id );
	}

}
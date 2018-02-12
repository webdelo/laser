<?php
namespace controllers\front;
class ProfileFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\controllers\Authorization;
	
	protected $permissibleActions = array(
		'profile',
	);
	
	private $user;

	public function __construct()
	{
		parent::__construct();
	}
	
	public function __call($name, $arguments)
	{
		if ((int)$this->getREQUEST()->action) {
			if (!$this->isUserExists((int)$this->getREQUEST()->action)) 
				return $this->redirect404();
		}
		
		if ( !$this->getREQUEST()->action && $this->isNotNoop($this->getREQUEST()->action) ) {
			return $this->defaultAction();
		} elseif ( !empty((int)$this->getREQUEST()->action) ) {
			return $this->setAction('profile')->callAction($arguments);
		} elseif ( $this->setAction($this->getREQUEST()->action)->isPermissibleAction() ) {
			return $this->callAction($arguments);
		} elseif($this->setAction($name)->isPermissibleAction()) {
			return $this->callAction($arguments);
		}
		
		return $this->redirect404();
	}

	public function defaultAction()
	{
		$this->profile();
	}
	
	public function profile()
	{
		$this->checkUserExistsAndBlock();
		
		$object   = $this->getUser();
		$realties = new \modules\realties\lib\Realties;
		$realties->filterByClientId($object->id);
		$realties->filterByStatusId($realties->getConfig()->activeStatus);
		$realties->setOrderBy(' `date` DESC ');
		$realties->setLimit(2);
		
		$this->setContent('object', $object)
			 ->setContent('realties', $realties)
			 ->setContent('bodyType', 'article')
			 ->includeTemplate('profile/profile');
	}
	
	public function getUser()
	{
		if ( !$this->user ) {
			if ((int)$this->getREQUEST()->action) {
				$this->isUserExists((int)$this->getREQUEST()->action);
			}

			$this->user = (int)$this->getREQUEST()->action
				? $this->getObject("\modules\clients\lib\Client", (int)$this->getREQUEST()->action)
				: $this->getAuthorizatedUser();
		}
		
		return $this->user;
	}
	
	private function isUserExists($userId)
	{
		$clients = new \modules\clients\lib\Clients;
		return $clients->isExist((int)$userId);
	}
	
	private function checkUserExistsAndBlock()
	{
		if ( !$this->isUserExists($this->getUser()->id) ) {
			return $this->redirect404();
		}
	}
	
}
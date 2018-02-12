<?php
namespace controllers\front\cabinet;
class ReviewsFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\Pager,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\controllers\front\traits\CabinetFrontControllerTrait,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\controllers\Authorization;

	protected $permissibleActions = array(
		'reviews'
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
		$this->reviews();
	}

	protected function reviews()
	{
		return $this->setTitle('Ваши отзывы о предложения других пользователей и отзывы пользователей о ваших предложениях')
					->setDescription('Ваши отзывы о предложения других пользователей и отзывы пользователей о ваших предложениях')
					->setContent('subpage', 'reviews')
					->includeTemplate('cabinet/main');
	}
}
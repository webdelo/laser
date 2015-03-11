<?php
namespace controllers\front;
class OrderFrontController
{
	use	\core\traits\controllers\DomainsSubControllersRedirects,
		\core\traits\RequestHandler;

	public function __construct()
	{
		$this->setControllersFolder('order\\');
	}
}
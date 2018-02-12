<?php
namespace controllers\front;
class ServiceFrontController
{
	use	\core\traits\controllers\DomainsSubControllersRedirects,
		\core\traits\RequestHandler;

	public function __construct()
	{
		$this->setControllersFolder('service\\');
	}
}
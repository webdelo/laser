<?php
namespace controllers\front;
class RealtiesFrontController
{
	use	\core\traits\controllers\DomainsSubControllersRedirects,
		\core\traits\RequestHandler;

	public function __construct()
	{
		$this->setControllersFolder('realties\\');
	}
}

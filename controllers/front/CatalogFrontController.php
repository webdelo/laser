<?php
namespace controllers\front;
class CatalogFrontController
{
	use \core\traits\controllers\DomainsSubControllersRedirects,
		\core\traits\RequestHandler;
	
	public function __construct()
	{
		$this->setControllersFolder('catalog\\');
	}
}

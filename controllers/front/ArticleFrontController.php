<?php
namespace controllers\front;
class ArticleFrontController
{
	use	\core\traits\controllers\DomainsSubControllersRedirects,
		\core\traits\RequestHandler;

	public function __construct()
	{
		$this->setControllersFolder('article\\');
	}
}

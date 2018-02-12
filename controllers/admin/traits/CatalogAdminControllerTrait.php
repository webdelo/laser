<?php
namespace controllers\admin\traits;
trait CatalogAdminControllerTrait
{
	private function setGoodObjectToContent($objectId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($objectId);
		return $this->setContent('object', $good);
	}
}
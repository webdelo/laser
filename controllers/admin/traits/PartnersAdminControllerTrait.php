<?php
namespace controllers\admin\traits;
trait PartnersAdminControllerTrait
{
	protected function getPartners()
	{
		$partners = new \modules\partners\lib\Partners();
		return $partners->setSubquery('AND `statusId` = ?d', \modules\partners\lib\PartnerConfig::STATUS_ACTIVE);
	}

	protected function getPartnersByModule($module)
	{
		$partners = new \modules\partners\lib\Partners();
		$modulesDomain = new \modules\modulesDomain\lib\ModulesDomain();
		$partners->setSubquery('
		    AND `id` IN (
			SELECT `ownerId`
			FROM `'.$partners->mainTable().'_modules_domain`
			WHERE `objectId` IN (
			    SELECT `id`
			    FROM `'.$modulesDomain->mainTable().'`
			    WHERE `alias` = \'?s\'
			)
		    )
		', (string)$module);

		if($this->isAuthorisatedUserAnManager())
			$partners->setSubquery('AND `id` = ?d', $this->getAuthorizatedUser()->partnerId);

		return $partners->setSubquery('AND `statusId` = ?d', \modules\partners\lib\PartnerConfig::STATUS_ACTIVE);
	}
}

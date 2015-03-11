<?php
namespace core\traits\validators;
trait DomainAlias
{
	public function _validDomainAlias($data)
	{
		$domainAliases = \core\Configurator::getInstance()->url->domains->getArray();
		return isset($domainAliases[$data]);
	}
}
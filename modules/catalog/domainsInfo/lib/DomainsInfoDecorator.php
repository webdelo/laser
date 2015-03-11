<?php
namespace modules\catalog\domainsInfo\lib;
class DomainsInfoDecorator extends \core\modules\base\ModuleDecorator
{
	private $domainsInfo;
	
	function __construct($object)
	{
		parent::__construct($object);;
	}
	
	function getDomainsInfo()
	{
		if (empty($this->domainsInfo))
			$this->domainsInfo = new DomainsInfo($this->getParentObject());
		return $this->domainsInfo;
	}
	
	function getDomainInfoByDomainAlias($domainAlias)
	{		
		return $this->getDomainsInfo()->getDomainInfoByDomainAlias($domainAlias);
	}
}
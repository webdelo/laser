<?php
namespace modules\catalog\domainsInfo\lib;
class DomainsInfoObject extends \core\modules\base\ModuleObjects
{
	use \core\traits\ObjectPool;
	
	protected $configClass = '\modules\catalog\domainsInfo\lib\DomainInfoConfig';
	private $parentObject;
		
	function __construct($object)
	{
		parent::__construct(new $this->configClass());
		$this->setParentObject($object)->setGoodFilters();
	}
	
	private function setParentObject($object)
	{
		if (is_object($object)) {
			$this->parentObject = $object;
			return $this;
		}
		throw new \Exception('Goods object must be passed in '.get_class($this).'::__constructor()!');
	}
	
	private function setGoodFilters()
	{
		$filtes = new \core\FilterGenerator();
		$filtes->setSubquery('`objectId` = ?d', $this->parentObject->id);
		return parent::setFilters($filtes);
	}
	
	public function setFilters($filterGenerator)
	{
		throw new \Exception('Filters are automatically assigned in '.  get_class($this).'!');
	}
	
	public function getDomainInfoByDomainAlias($domainAlias)
	{		
		$id = $this->getFieldWhereCriteria($this->idField,$domainAlias,'domainAlias',$this->parentObject->id,'objectId');
		return empty($id) ? $this->getNoop() : $this->getObjectById($id);
	}
}
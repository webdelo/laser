<?php
namespace modules\catalog;
abstract class CatalogGood extends \core\modules\base\ModuleDecorator implements \interfaces\ICatalogGood
{
	function __construct($object)
	{
		parent::__construct($object);
	}
	
	/* Start: ICatalog interface methods*/
	public function getCode()
	{
		return $this->getParentObject()->getCode();
	}
	
	public function getClass()
	{
		return $this->getParentObject()->getClass();
	}
		
	public function getName()
	{
		return $this->getParentObject()->getName();
	}
		
	public function getDescription()
	{
		return $this->getParentObject()->getDescription();
	}
	/*   End: ICatalog interface methods*/
}
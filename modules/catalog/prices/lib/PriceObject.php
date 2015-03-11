<?php
namespace modules\catalog\prices\lib;
class PriceObject extends \core\modules\base\ModuleObject
{
	protected $configClass = '\modules\catalog\prices\lib\PriceConfig';
	
	function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}
	
	public function getOldPrice()
	{
		$this->loadObjectInfo();
		$price = empty($this->objectInfo['oldPrice']) ? round($this->price*1.05,-2) : $this->objectInfo['oldPrice'];
		return $price;
	}
	
	public function getDiscount()
	{
		return ($this->getOldPrice() - $this->price);
	}
	
	public function isDefaultDiscount()
	{
		return empty($this->objectInfo['oldPrice']);
	}
}
<?php
namespace modules\measures\lib;
class MeasureDecorator extends \core\modules\base\ModuleDecorator
{
	private $measure;

	function __construct($object)
	{
		parent::__construct($object);
	}

	public function getMeasure()
	{
		if (empty($this->measure))
			$this->measure = $this->measureId ? new Measure($this->measureId) : $this->getNoop();
		
		return $this->measure;
	}
	
	public function getMeasureShort()
	{
		return $this->getMeasure()->getShortName();
	}
	
	public function getMeasureDeclension($numbers)
	{
		return $this->getMeasure()->getDeclension($numbers);
	}
	
	
}

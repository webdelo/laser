<?php
namespace modules\measures\lib;
class MeasuresDecorator extends \core\modules\base\ModuleDecorator
{
	private $measure;

	function __construct($object)
	{
		parent::__construct($object);
	}
	
	public function getMeasuresByCategory()
	{
		$measures = new Measures();
		$measures->setSubquery(' AND `categoryId`=?d ', $this->measureCategoryId);
		return $measures;
	}
}

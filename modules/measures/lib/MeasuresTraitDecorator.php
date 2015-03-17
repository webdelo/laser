<?php
namespace modules\measures\lib;
trait MeasuresTraitDecorator
{
	private $measure;

	public function getMeasuresByCategory()
	{
		$measures = new Measures();
		$measures->setSubquery(' AND `categoryId`=?d ', $this->measureCategoryId);
		return $measures;
	}
}

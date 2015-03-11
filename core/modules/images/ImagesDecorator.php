<?php
namespace core\modules\images;
class ImagesDecorator extends \core\modules\base\ModuleDecorator
{
	const PRIMARY_CATEGORY_ID = 2;
	const STATUS_ACTIVE = 1;
	public $images;

	function __construct($object)
	{
		parent::__construct($object);
	}

	protected function getImagesCategories()
	{
		$images = new Images($this->getParentObject());
		return $images->getCategories();
	}

	protected function getImagesStatuses()
	{
	    $images = new Images($this->getParentObject());
	    return $images->getStatuses();
	}

	protected function getImagesByCategory($categories)
	{
	    $images = new Images($this->getParentObject());
		$categories = (is_array($categories)) ? implode(',',$categories) : (int)$categories;
	    $images->setSubquery(' AND `objectId` = ?d AND `categoryId` IN (?s) AND `statusId` = ?d ',$this->getParentObject()->id,$categories, self::STATUS_ACTIVE);
		$images->setOrderBy(' `priority` ASC ');
	    return $images;
	}

	protected function getImagesByCategoryAndStatus($categories, $statuses)
	{
		$images = $this->getImagesByCategory($categories);
		$statuses = (is_array($statuses)) ? implode(',',$statuses) : (int)$statuses;
		$images->setSubquery(' AND `statusId` = ?d', $statuses);
		return $images;
	}

	protected function getFirstPrimaryImage()
	{
	    $image = new \core\modules\images\ImageNoop();
	    $images = new Images($this->getParentObject());
	    $images->setSubquery(' AND `objectId` = ?d AND `categoryId` = ?d',$this->getParentObject()->id,self::PRIMARY_CATEGORY_ID)->setOrderBy('`id` ASC')->setLimit('1');
	    if($images->count() != 0){
			$image = $images->current();
	    } else {
			$images->reset()->setSubquery(' AND `objectId` = ?d',$this->getParentObject()->id)->setOrderBy('`id` ASC')->setLimit('1');
			if($images->count() != 0){
				$image = $images->current();
			}
	    }
	    return $image;

	}

	protected function getPrimaryImages()
	{
	    $images = new Images($this->getParentObject());
	    $images->setSubquery(' AND `objectId` = ?d AND `categoryId` = ?d',$this->getParentObject()->id,self::PRIMARY_CATEGORY_ID);
		$images->setOrderBy(' `priority` ASC ');
		return $images;
	}

	protected function getImagesByObjectId()
	{
		$images = new Images($this->getParentObject());
		$images->setSubquery(' AND `objectId` = ?d',$this->getParentObject()->id);
		$images->setOrderBy(' `priority` ASC ');
	    return $images;
	}

	protected function getImageById($id)
	{
		$images = new Images($this->getParentObject());
		$images->setSubquery(' AND `id` = ?d', $id);
	    return $images->current();
	}

	protected function getImages()
	{
		$images = new Images($this->getParentObject());
	    return $images;
	}
}

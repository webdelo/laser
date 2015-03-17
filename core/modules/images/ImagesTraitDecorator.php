<?php
namespace core\modules\images;
trait ImagesTraitDecorator
{
	private $images;

	private $_imageByObjectId;

	public function getImagesCategories()
	{
		return $this->getImages()->getCategories();
	}

	public function getImages()
	{
		if (empty($this->images))
			$this->images = new Images($this);
		return $this->images;
	}

	public function getImagesStatuses()
	{
	    return $this->getImages()->getStatuses();
	}

	public function getImagesByCategory($categories)
	{
	    $images = new Images($this);
		$categories = is_array($categories) ? implode(',',$categories) : (int)$categories;
	    $images->setSubquery(' AND `objectId` = ?d AND `categoryId` IN (?s) AND `statusId` = ?d ',$this->id,$categories, ImageConfig::STATUS_ACTIVE)
			   ->setOrderBy(' `priority` ASC ');
	    return $images;
	}

	public function getImagesByCategoryAndStatus($categories, $statuses)
	{
		$images = $this->getImagesByCategory($categories);
		$statuses = is_array($statuses) ? implode(',',$statuses) : (int)$statuses;
		$images->setSubquery(' AND `statusId` = ?d', $statuses);
		return $images;
	}

	public function getFirstPrimaryImage()
	{
	    $image = new \core\modules\images\ImageNoop();
	    $images = $this->getImages();
	    $images->setSubquery(' AND `objectId` = ?d AND `categoryId` = ?d', $this->id,  ImageConfig::PRIMARY_CATEGORY_ID)
			   ->setOrderBy('`priority` ASC')
			   ->setLimit('1');
	    if ($images->count()){
			$image = $images->current();
	    } else {
			$images->reset()
				   ->setSubquery(' AND `objectId` = ?d',$this->id)
				   ->setOrderBy('`priority` ASC')
				   ->setLimit('1');
			if ($images->count())
				$image = $images->current();
	    }
	    return $image;
	}

	public function getPrimaryImages()
	{
		$images = new Images($this);
		$images->setSubquery(' AND `objectId` = ?d AND `categoryId` = ?d', $this->id, ImageConfig::PRIMARY_CATEGORY_ID)
			   ->setOrderBy(' `priority` ASC ');
		return $images;
	}

	public function getSecondaryImages()
	{
		$images = new Images($this);
		$images->setSubquery(' AND `objectId` = ?d AND `categoryId` = ?d', $this->id, ImageConfig::SECONDARY_CATEGORY_ID)
			   ->setOrderBy(' `priority` ASC ');
		return $images;
	}

	public function getImagesByObjectId()
	{
		if (empty($this->_imageByObjectId)){
			$this->_imageByObjectId = new Images($this);
			$this->_imageByObjectId->setSubquery(' AND `objectId` = ?d',$this->id)
								   ->setOrderBy(' `priority`, `categoryId` DESC ');
		}
		return $this->_imageByObjectId;
	}

	public function hasImages()
	{
		return $this->getImagesByObjectId()->count() > 0;
	}

	public function getImageById($id)
	{
		$images = $this->getImages()->setSubquery(' AND `id` = ?d', $id);
		return $images->current();
	}
	
	public function setPrimaryImage($imageId) {
		return $this->getImagesByObjectId()->getObjectById((int)$imageId)->setPrimary();
	}
}

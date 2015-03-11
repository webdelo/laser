<?php
namespace modules\catalog\complects\lib;
class Complects extends \core\modules\base\ModuleDecorator implements \Countable
{
	function __construct()
	{
		$object = new ComplectsObject();
		$object = new \core\modules\statuses\StatusesDecorator($object);
		parent::__construct($object);
	}

	public function getComplectsByGoodId($goodId, $statusId = null)
	{
		$this->setSubquery('
						AND `id` IN (
							SELECT `complectId`
							FROM `'.$this->getObject('\modules\catalog\complects\complectGoods\lib\ComplectGoods')->mainTable().'`
							WHERE `goodId` = ?d
							AND `mainGood` = ?d
						)
						AND `statusId` =?d'
						, $goodId, 1, $statusId
					)
			->setOrderBy('`priority` ASC, `id` DESC');
		return $this;
	}

	/* Start: Countable Methods */
	public function count()
	{
		return $this->getParentObject()->count();
	}
	/* End: Countable Methods */
}
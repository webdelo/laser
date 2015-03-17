<?php
namespace modules\console\lib;
class ConsoleItem extends \core\modules\base\ModuleObject
{
	use \core\traits\controllers\Authorization,
		\core\traits\ObjectPool,
		\core\modules\categories\CategoryTraitDecorator,
		\core\modules\statuses\StatusTraitDecorator;
	
	protected $configClass = '\modules\console\lib\ConsoleItemConfig';
	
	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}
	
	/* Start: Main Data Methods */
	public function getTitle()
	{
		return $this->title;
	}
	public function getDescription()
	{
		return $this->description ? $this->description : 'Нет описания';
	}
	/*   End: Main Data Methods */

	/* Start: URL Methods */
	public function getPath()
	{
		return '/admin/console/viewItem/'.$this->id.'/';
	}
	/*   End: URL Methods */

	public function isValidPath($path)
	{
		return $this->getPath() == rtrim($path,'/').'/';
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	public function setAsArchived()
	{
		$this->editField($this->getAuthorizatedUserId(), 'managerId');
		$this->editField(null, 'viewDate');
		$this->editField(null, 'archiveDate');
		
		return $this->editField($this->getConfig()->archivedStatus, 'statusId');
	}
	
	public function setAsViewed()
	{
		$this->editField($this->getAuthorizatedUserId(), 'managerId');
		$this->editField(null, 'viewDate');
		
		return $this->editField($this->getConfig()->viewedStatus, 'statusId');
	}
	
	public function getManager()
	{
		return ( $this->managerId ) 
			? $this->getObject('\modules\administrators\lib\Administrator', $this->managerId)
			: $this->getNoop();
	}
	
	public function isViewed()
	{
		return $this->getConfig()->viewedStatus == $this->statusId;
	}
	
	public function isArchived()
	{
		return $this->getConfig()->archivedStatus == $this->statusId;
	}
}
<?php
namespace modules\messages\lib;
class Message extends \core\modules\base\ModuleObject implements \interfaces\IConsole, IMessage
{
	use \core\traits\controllers\Authorization,
		\core\traits\ObjectPool,
		\modules\clients\lib\ClientTraitDecorator;
	
	protected $configClass = '\modules\messages\lib\MessageConfig';

	public function __construct($objectId, $configObject)
	{
		parent::__construct($objectId, new $this->configClass($configObject));
	}
	
	public function getConfirmedBy()
	{
		return (int)$this->confirmedBy ? $this->getObject('\modules\administrators\lib\Administrator', $this->confirmedBy) : $this->getNoop();
	}
	
	public function getText()
	{
		return $this->text;
	}
	
	public function isNew()
	{
		return (int)$this->statusId == $this->getConfig()->newStatus;
	}
	
	public function isActive()
	{
		return (int)$this->statusId == $this->getConfig()->activeStatus;
	}
	
	public function isSystem()
	{
		return $this->isSystem;
	}
	
	public function isBlocked()
	{
		return (int)$this->statusId == $this->getConfig()->blockedStatus;
	}
	
	public function isReaded()
	{
		return !$this->isNew();
	}
	
	public function isNotReaded()
	{
		return !$this->isReaded();
	}
	
	public function isInModeration()
	{
		return $this->statusId == $this->getConfig()->moderStatus;
	}
	
	public function setAsReaded()
	{
		return $this->editField($this->getConfig()->activeStatus, 'statusId');
	}
	
	public function ownerVisit()
	{
		return $this->clientId == $this->getAuthorizatedUser()->id;
	}
	
	public function isNewMessageForMe()
	{
		return !$this->ownerVisit() && $this->isNotReaded();
	}
	
	public function isNewMessageForOpponent()
	{
		return $this->ownerVisit() && $this->isNotReaded();
	}

	/* Start: IConsole methods */
	public function toConsole($title = '')
	{
		$console = $this->getObject('\modules\console\lib\Console');
		return $console->addImportant('Добавлено новое сообщение', $this->getAdminPath());
		
	}
	/* End: IConsole methods */
	
	public function getAdminPath()
	{
		return  $this->getOwnerObject()->getAdminPath().'#message'.$this->id;
	}
	
	public function getOwnerObject()
	{	
		return $this->ownerId ? $this->getObject('\modules\bookings\lib\Booking', $this->ownerId) : $this->getNoop();
	}
	
	public function accept()
	{	
		$this->editField($this->getAuthorizatedUserId() ,'confirmedBy');
		$this->editField(null ,'confirmedDate');
		
		return $this->editField($this->getConfig()->newStatus ,'statusId');;
	}
	
	public function deny($description)
	{
		$this->editField($this->getAuthorizatedUserId() ,'confirmedBy');
		$this->editField(null ,'confirmedDate');
		$res = $this->editField($description, 'description');
		
		return $this->editField($this->getConfig()->blockedStatus ,'statusId');;
	}
}
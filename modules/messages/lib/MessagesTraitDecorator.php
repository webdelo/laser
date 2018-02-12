<?php
/* Requires \core\traits\controllers\Authorization in parent object */
/* Requires \core\traits\ObjectPool in parent object */
namespace modules\messages\lib;
trait MessagesTraitDecorator
{
	private $messages;
	private $newMessages;
	private $newMessagesForMe;
	private $lastMessage;

	public function getMessages()
	{
		if ( !$this->messages ) {
			$messages = new \modules\messages\lib\Messages($this);
			$messages->setSubquery(' AND `ownerId` = ?d ', $this->id);
			$this->messages = $messages;
		}
		return $this->messages;
	}

	public function getLastMessage()
	{
		if ( !$this->lastMessage ) {
			$messages = new \modules\messages\lib\Messages($this);
			$messages->setSubquery(' AND `ownerId` = ?d ', $this->id);
			$messages->setOrderBy(' `date` DESC ');
			$messages->setLimit(1);
			$this->lastMessage = $messages->current() ? $messages->current() : $this->getNoop();
		}
		return $this->lastMessage;
	}

	public function hasMessages()
	{
		return $this->getMessages()->count() > 0 ;
	}

	public function getNewMessages()
	{
		if ( !$this->newMessages ) {
			$messages = new \modules\messages\lib\Messages($this);
			$messages->setSubquery(' AND `ownerId` = ?d ', $this->id);
			$this->newMessages = $messages;
		}
		return $this->newMessages;
	}

	public function hasNewMessages()
	{
		return $this->getNewMessages()->count() > 0 ;
	}

	public function getNewMessagesForMe()
	{
		if ( !$this->newMessagesForMe ) {
			$messages = new \modules\messages\lib\Messages($this);
			$messages->setSubquery(' AND `ownerId` = ?d AND `statusId` = ?d AND `clientId` != ?d ', $this->id, $this->getConfig()->newStatus, $this->getAuthorizatedUserId());
			$this->newMessagesForMe = $messages;
		}
		return $this->newMessagesForMe;
	}

	public function hasNewMessagesForMe()
	{
		return $this->getNewMessagesForMe()->count() > 0 ;
	}
}

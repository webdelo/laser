<?php
namespace modules\messages\lib;
interface IMessage {
	
	// return boolean
	public function isNew();
	public function isActive();
	public function isSystem();
	public function isBlocked();
	public function isReaded();
	public function isNotReaded();
	public function isInModeration();
	public function ownerVisit();
	public function isNewMessageForMe();
	public function isNewMessageForOpponent();

	// return something content
	public function getAdminPath();	
	public function getOwnerObject();
	public function getConfirmedBy();
	public function getText();
	
	// set something content
	public function setAsReaded();
	public function accept();
	public function deny($description);
}
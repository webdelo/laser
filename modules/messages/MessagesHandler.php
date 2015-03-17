<?php
namespace modules\messages;
class MessagesHandler
{
	use \core\traits\controllers\Authorization;
	
	protected $messages;
	
	public function __construct(lib\Messages $messages)
	{
		$this->setMessages($messages);
		return $this;
	}
	
	public function setMessages(lib\Messages $messages)
	{
		$this->messages = $messages;
		return $this;
	}
	
	public function getMessages()
	{
		return $this->messages;
	}
	
	public function add($data)
	{
		$filterByUrl = function($data){
			return \core\utils\Utils::filterTextByUrl($data['text']);
		};
		$filterByEmail = function($data){
			return \core\utils\Utils::filterTextByEmail($data['text']);
		};
		$filterByPhone = function($data){
			return \core\utils\Utils::filterTextByPhone($data['text']);
		};
		
		return $this->getMessages()->setFilter($filterByPhone, $filterByEmail, $filterByUrl)->add($data, array(
			'clientId',
			'ownerId',
			'date',
			'text',
			'statusId'
		));
	}
}
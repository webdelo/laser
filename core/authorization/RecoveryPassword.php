<?php
namespace core\authorization;
class RecoveryPassword extends \core\modules\base\ModuleAbstract
{
	use \core\traits\RequestHandler;
	
	private $requestLifetime = 86400; // seconds
	
	private $_userIdToken;
	
	public function __construct()
	{
		parent::__construct();
		$this->cleanTable();
	}
	
	private function cleanTable()
	{
		$query = '
			DELETE FROM 
				`'.$this->mainTable().'`
			WHERE
				(NOW()-`date`) > ?d
			';
		$data = array($this->requestLifetime);
		\core\db\Db::getMysql()->query($query, $data);
	}
	
	public function addToken($userId)
	{
		$this->generateTokenByUserId($userId); 
		$data = array(
			'user_id'   =>(int)$userId,
			'token'    =>$this->getToken(),
			'ip'      =>$this->getSERVER()['REMOTE_ADDR'],
		);
		return ($this->add($data)) ? $this->getToken() : false;
	}
	
	private function generateTokenByUserId($userId)
	{
		$this->_userIdToken = md5(mktime()+$userId);
		return $this;
	}
	
	private function getToken()
	{
		return $this->_userIdToken;
	}
	
	public function getUserIdByToken($token)
	{
		$filter = array(
			'where' => array(
				'query' => '`token` = "?s"',
				'data'  => array($token),
				)
			);
		$tokenData = $this->getOne('id,user_id', $filter);
		if (empty($tokenData['user_id']))
			return false;
		$this->deleteToken($tokenData['id']);
		return $tokenData['user_id'];
	}
	
	private function deleteToken($tokenId)
	{
		return $this->deleteById($tokenId);
	}
}
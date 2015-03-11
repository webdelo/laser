<?php
namespace modules\shopcart\lib;
class AuthorizatedShopcart extends Shopcart
{
	use	\core\traits\controllers\Authorization;

	protected $table = 'tbl_shopcart';
	protected $authorizatedUserId;
	protected $goods = array();

	public function __construct()
	{
		$this->setAuthorizatedUserId();
		if ($this->isGuest())
			parent::__construct();
	}

	protected function setAuthorizatedUserId()
	{
		$this->authorizatedUserId = $this->getAuthorizatedUser()->id;
	}

	protected function isAuthorizatedUser()
	{
		return !!$this->authorizatedUserId;
	}

	protected function isGuest()
	{
		return !$this->isAuthorizatedUser();
	}

	protected function setShopcartGood($objectClass, $objectId, $quantity)
	{
		if ($this->isAuthorizatedUser()) {
			$goodExistInShopcartTable = $this->isGoodExistInShopcartTable($objectClass, $objectId);

			if($goodExistInShopcartTable){
				$query = '
					UPDATE
						'.$this->table.'
					SET
						`quantity`= ?d
					WHERE
						`id` = ?d';
				$data = array(($quantity + $goodExistInShopcartTable['quantity']), $goodExistInShopcartTable['id']);
			} else {
				$query = '
					INSERT INTO
						'.$this->table.'
					(`objectClass`, `objectId`, `quantity`, `clientId`, `domain`)
						VALUES
					("?s", ?d, ?d, ?d, "?s")';
				$data = array($objectClass, $objectId, $quantity, $this->authorizatedUserId, $this->getCurrentDomain());
			}

			$res = \core\db\Db::getMysql()->query($query, $data);
			if ($res)
				return $this;
			throw new Exception('Failed to add good in shopcart table in database!');
		} else
			return parent::setShopcartGood($objectClass, $objectId, $quantity);
	}

	private function isGoodExistInShopcartTable($objectClass, $objectId)
	{
		$query = '
			SELECT
				*
			FROM
				`'.$this->table.'`
			WHERE
				`clientId` = ?d
					AND
				`objectClass` = "?s"
					AND
				`objectId` = ?d
			LIMIT 1';
		$data = array($this->authorizatedUserId, $objectClass, $objectId);
		$res = \core\db\Db::getMysql()->row($query, $data);
		return is_numeric($res['id']) ? $res : false;
	}

	protected function &getGoods()
	{
		if ($this->isGuest())
			return parent::getGoods();
		if (empty($this->goods)) {
			$query = '
				SELECT
					*
				FROM
					`'.$this->table.'`
				WHERE
					`clientId` = ?d
						AND
					`domain` = "?s" ';
			$data = array($this->authorizatedUserId, $this->getCurrentDomain());
			$res = \core\db\Db::getMysql()->rows($query, $data);
			$this->goods = array();
			foreach($res as $element)
				$this->goods[] = new ShopcartGood($element['objectClass'], $element['objectId'], $element['quantity']);
		}
		return $this->goods;
	}

	protected function removeGood($code)
	{
		if ($this->isAuthorizatedUser()){
			$keyArray = explode('-', $code);

			$query = '
				DELETE FROM
					`'.$this->table.'`
				WHERE
					`clientId` = ?d
						AND
					`objectClass` = "?s"
						AND
					`objectId` = ?d';
			$data = array($this->authorizatedUserId, $keyArray[0], $keyArray[1]);
			$res = \core\db\Db::getMysql()->query($query, $data);
			if ($res)
				return $this;
			throw new Exception('Failed remove good in shopcart table in database!');
		} else
			return parent::removeGood($code);
	}

	public function resetShopcart()
	{
		if ($this->isGuest()) {
			return parent::resetShopcart();
		}

		foreach ($this as $good){
			$res = $this->removeGoodByCode($good->getCode());
			if(!$res)
				return false;
		}
		return $this;
	}

	protected function updateCookie()
	{
		return $this->isGuest() ? parent::updateCookie() : $this;
	}

	public function isZeroPriceGoods()
	{
		foreach ($this as $good)
			if($good->isZeroPrice())
				return true;
		return false;
	}

	public function isNotZeroPriceGoods()
	{
		return ! $this->isZeroPriceGoods();
	}

}
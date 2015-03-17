<?php
namespace modules\administrators\lib;
class Administrator extends \core\authorization\AuthorizatedUser implements \interfaces\IUserForAuthorization
{
	use \core\traits\ObjectPool,
		\core\modules\statuses\StatusTraitDecorator,
		\core\modules\groups\GroupTraitDecorator,
		\core\modules\rights\RightsListTraitDecorator;

	protected $configClass = '\modules\administrators\lib\AdministratorConfig';
	protected $rightsKey = 'rights';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function delete()
	{
		if ($this->getRights()->delete())
			return parent::delete();
		return false;
	}

	public function getAllName()
	{
		return $this->name.' '.$this->firstname.' '.$this->lastname;
	}

	public function getDefaultName()
	{
		var_dump(1);
		return trim($this->getAllName()) ? $this->getAllName() : $this->getLogin();
	}

	public function getFullName()
	{
		return trim($this->getAllName()) ? $this->getAllName() : 'Имя не указано';
	}

	public function getMobile()
	{
		return $this->loadObjectInfo()->objectInfo['mobile'];
	}

}
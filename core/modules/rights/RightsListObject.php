<?php
namespace core\modules\rights;
class RightsListObject extends \core\modules\base\ModuleRelations
{
	protected $configClass = '\core\modules\rights\RightsListConfig';

	function __construct($ownerId, $configObject)
	{
		parent::__construct($ownerId, new $this->configClass($configObject));
	}

	public function checkRightByAlias($alias)
	{
		$rights = new Rights();
		$rightId = $rights->getIdByAlias($alias);
		return $this->checkRightById($rightId);
	}

	public function checkRightById($rightId)
	{
		return $this->objectExists($rightId);
	}

}
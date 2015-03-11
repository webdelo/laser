<?php
namespace core\modules\categories;
class AdditionalCategoriesObject extends \core\modules\base\ModuleRelations
{
	protected $configClass = '\core\modules\categories\AdditionalCategoriesConfig';

	function __construct($ownerId, $configObject)
	{
		parent::__construct($ownerId, new $this->configClass($configObject));
	}

	public function checkCategoryByAlias($alias)
	{
		$categories = new Categories();
		$categoryId = $categories->getIdByAlias($alias);
		return $this->checkCategoryById($categoryId);
	}

	public function checkCategoryById($categoryId)
	{
		return $this->objectExists($categoryId);
	}

}
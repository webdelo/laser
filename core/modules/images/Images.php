<?php
namespace core\modules\images;
class Images extends \core\modules\base\ModuleObjects
{
	use \core\modules\statuses\StatusesTraitDecorator,
		\core\modules\categories\CategoriesTraitDecorator;

	protected $configClass = '\core\modules\images\ImageConfig';

	function __construct($configObject)
	{
		parent::__construct(new $this->configClass($configObject));
	}

	public function add($data, $fields = null)
	{
		$file = new \core\files\uploader\File(DIR.$data['tmpName']);
		$data = array_merge($data, $this->getAdditionalData($file));
		$imageId = parent::add($data, $fields);
		if ($imageId) {
			$this->getObjectById($imageId)->defaultSetPrimary();
			if (!$file->move(DIR.$this->getParentObjectConfig()->imagesPath.$imageId.'.'.$file->extension)) {
				return false;
			}
		} else {
			return false;
		}
		return $imageId;
	}

	private function getAdditionalData($file)
	{
		return array(
			'extension' => $file->extension,
			'objectId'  => $this->getConfig()->getParentConfig()->id
		);
	}

	private function getParentObjectConfig()
	{
		return $this->getConfig()->getParentConfig()->getConfig();
	}

}
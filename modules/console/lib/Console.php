<?php
namespace modules\console\lib;
class Console extends \core\modules\base\ModuleObjects
{
	use \core\traits\objects\WordsSearch,
		\core\traits\ObjectPool,
		\core\modules\categories\CategoriesTraitDecorator,
		\core\modules\statuses\StatusesTraitDecorator;

	protected $configClass     = '\modules\console\lib\ConsoleItemConfig';
	protected $objectClassName = '\modules\console\lib\ConsoleItem';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function add($data, $fields = array())
	{
		throw new \Exception('Can\'t call method Console::add(). Must call addImportant() or addSimple()!');
	}

	public function addImportant($title, $url)
	{
		return $this->addBase($title, $url, $this->getConfig()->importantCategory);
	}

	private function addBase($title, $url, $category = null, $status = null)
	{
		$category = isset($category) ? (int)$category : $this->getConfig()->simpleCategory;
		$status = isset($status) ? (int)$status : $this->getConfig()->newStatus;

		$data = array(
			'title' => $title,
			'url' => $url,
			'statusId' => $status,
			'categoryId' => $category,
		);

		return parent::add($data);
	}

	public function addSimple($title, $url)
	{
		return $this->addBase($title, $url);
	}

	public function excludeArchive()
	{
		return $this->setSubquery(' AND `statusId` != ?d ', $this->getConfig()->archivedStatus);
	}
}

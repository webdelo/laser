<?php
namespace modules\articles\lib;
class Articles extends \core\modules\base\ModuleObjects implements \core\i18n\interfaces\Ii18n
{
    use \core\traits\ObjectPool,
		\core\traits\objects\WordsSearch,
		\core\modules\images\ImagesTraitDecorator,
		\core\modules\filesUploaded\FilesTraitDecorator,
		\core\modules\statuses\StatusesTraitDecorator,
		\core\modules\categories\TranslateCategoriesTraitDecorator,
		\core\i18n\traits\ObjectLangTrait;

	protected $configClass     = '\modules\articles\lib\ArticleConfig';
	protected $objectClassName = '\modules\articles\lib\Article';

	// reimplemented method with lang-setting
	protected function &getModuleObject($id)
	{
		$object = parent::getModuleObject($id);
		$this->addLangObserver($object);
		return $object;
	}

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}

	public function add($data = null, $fields = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::add($compacter->getPost(), $fields);
	}
	
	public function checkAlias($alias)
	{
		return \core\db\Db::getMySql()->isExist($this->mainTable,'alias',$alias);
	}
}

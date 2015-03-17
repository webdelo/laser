<?php
namespace modules\locations\components\cities\lib;
class LocationsCity extends \core\modules\base\ModuleObject implements \core\i18n\interfaces\Ii18n
{
	use \modules\locations\components\regions\lib\LocationsRegionTraitDecorator,
		\modules\locations\components\countries\lib\LocationsCountryTraitDecorator,
		\core\i18n\TextLangParserTraitDecorator,
		\core\traits\RequestHandler,
		\core\i18n\traits\ObjectLangTrait;

	protected $configClass = '\modules\locations\components\cities\lib\LocationsCityConfig';

	function __construct($objectId)
	{
		parent::__construct($objectId, new $this->configClass);
	}

	public function edit($data = null, $fields = array(), $rules = array())
	{
		$compacter = new \core\i18n\TextLangCompacter($this, $data);
		return parent::edit($compacter->getPost(), $fields, $rules);
	}

	public function remove () {
		return ($this->delete()) ? (int)$this->id : false ;
	}

	public function getName($lang = null)
	{
		return $this->getTextFromLangParser($this->name, $this->getLang($lang));
	}
}
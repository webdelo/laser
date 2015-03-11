<?php
namespace modules\parameters\components\chooseMethods\lib;
class ChooseMethodConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Base;

	protected $objectClass  = '\modules\parameters\components\chooseMethods\lib\ChooseMethod';
	protected $objectsClass = '\modules\parameters\components\chooseMethods\lib\ChooseMethods';

	public $templates  = 'modules/parameters/tpl/';

	protected $table = 'parameters_choose_methods'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'name',
		'priority',
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
			),
			'priority' => array(
				'validation' => array('_validInt'),
			)
		);
	}
}

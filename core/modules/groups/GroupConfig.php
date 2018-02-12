<?php
namespace core\modules\groups;
class GroupConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\adapters\Date,
		\core\traits\adapters\Alias,
		\core\traits\adapters\Base;

	protected $objectClass = '\core\modules\groups\Group';
	protected $objectsClass = '\core\modules\groups\Groups';

	protected $table = 'user_groups'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'alias',
		'name',
		'description',
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
		);
	}

}

<?php
namespace core\observersStorage\sqlObserversStorage;
class sqlObserversStorageConfig extends \core\modules\base\ModuleConfig
{
	protected $table = 'observers'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'ownerHash',
		'observerHash',
		'observerSerialize',
	);

	public function rules()
	{
		return array(
			'ownerHash, observerHash' => array(
				'adapt' => '_adaptHTML',
			)
		);
	}
}
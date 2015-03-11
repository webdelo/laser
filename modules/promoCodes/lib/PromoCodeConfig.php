<?php
namespace modules\promoCodes\lib;
class PromoCodeConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\adapters\Date,
		\core\traits\adapters\Base,
		\core\traits\validators\Base,
		\core\traits\adapters\User,
		\modules\catalog\CatalogAdapters,
		\core\traits\outAdapters\OutDate;

	const STATUS_ACTIVE = 1;
	const REMOVED_STATUS_ID = 3;

	protected $removedStatus = self::REMOVED_STATUS_ID;

	protected $objectClass = '\modules\promoCodes\lib\PromoCode';
	protected $objectsClass = '\modules\promoCodes\lib\PromoCodes';

	protected $table = 'promo_codes'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'code',
		'name',
		'description',
		'statusId',
		'categoryId',
		'discount',
		'date',
		'expirationDate',
		'quantity',
		'userId',
	);

	public $templates = 'modules/promoCodes/tpl/';

	public function rules()
	{
		return array(
			'statusId, categoryId, discount' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'userId' => array(
				'validation' => array('_validInt'),
				'adapt' => '_adaptUser',
			),
			'name,description' => array(
				'adapt' => '_adaptHtml',
			),
			'quantity' => array(
				'validation' => array('_validInt'),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'code' => array(
				'adapt' => '_adaptCode',
			),
			'expirationDate' => array(
				'adapt' => '_adaptDate',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate'),
			'expirationDate' => array('_outDate'),
		);
	}

	public function _validCode($data)
	{
		if (empty($data))
			return true;
		$promoCodes = new PromoCodes();
		$id = $promoCodes->getIdByCode($data);
		if ($id != $this->data['id']){
			$this->setError('code', 'Данный Промо-код уже существует');
			return 'error_add';
		}
		return true;
	}
}
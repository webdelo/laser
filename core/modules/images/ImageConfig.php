<?php
namespace core\modules\images;
class ImageConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\core\modules\images\Image';
	protected $objectsClass = '\core\modules\images\Images';

	protected $tablePostfix = '_images'; // set value without preffix!\
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'alias',
		'name',
		'title',
		'description',
		'date',
		'priority',
		'objectId',
		'statusId',
		'categoryId',
		'focus',
		'sharpen',
		'rgbBgColor',
		'extension',
	);

	public function rules()
	{
		return array(
//			'name' => array(
//				'validation' => array('_validNotEmpty'),
//			),
			'title' => array(
				'adapt' => '_adaptHtml',
			),
			'alias' => array(
				'adapt' => '_adaptAlias',
			),
			'tmpName' => array(
				'validation' => array('_validFileExists'),
			),
			'statusId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
			'priority' => array(
				'validation' => array('_validInt'),
			),
			'date' => array(
				'adapt' => '_adaptRegDate',
			),
			'extension' => array(
				'validation' => array('_validNotEmpty'),
				'adapt' => '_adaptHtml',
			),
		);
	}

	public function outputRules()
	{
		return array(
			'date' => array('_outDate')
		);
	}

	public function _outDate($data)
	{
		return \core\utils\Dates::convertDate($data, 'simple');
	}

	public function _adaptRegDate($key)
	{
		$this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : time();
	}

//	public function _adaptAlias($key)
//	{
//		if (empty($this->data[$key])) {
//			$name = explode('.',$this->data['name']);
//			$name = str_replace(' ', '_', array_shift($name));
//			$this->data[$key] = $name;
//		}
//	}
	public function _adaptAlias()
	{
		if( ! $this->data['alias'] )
			$this->generateAlias();
		else {
			$this->data['alias'] = str_replace(' ', '_', $this->data['alias']);
		}
	}
	public function generateAlias()
	{
		$this->data['alias'] = $this->transformAlias($this->data['name']);
	}
	public function transformAlias($name)
	{
		$name = explode('.', $name);
		$ext = array_pop ($name);
		$alias = str_replace(' ', '_', implode("_", $name));

		while( \core\db\Db::getMySql()->isExist($this->table,  'alias', $alias) ){
			$alias1 = $alias;
			$array = explode("_", $alias);
			if( sizeof($array) > 1){
				$last = array_pop ($array);
				if($last == '0'  or  $last == '-0'){
					$alias = implode("_", $array);
					$alias = $alias.'_'.$last.'_1';
				}
				else{
					if((int)$last == 0){
						$alias = $alias1.'_1';
					}
					else{
						$last = (int)$last;
						if($last > 0){
							$alias = implode("_", $array);
							$last = $last + 1;
							$last = (string)$last;
							$alias = $alias.'_'.$last;
						}
						if($last < 0  or  $last == 0){
							$alias = implode("_", $array);
							$last = (string)$last;
							$alias = $alias.'_'.$last.'_1';
						}
					}
				}
			}
			else
				$alias = $alias1.'_1';
		}
		return $alias;
	}

	public function _adaptHtml($key)
	{
		if (isset($this->data[$key]))
			$this->data[$key] = \core\utils\DataAdapt::textValid($this->data[$key]);
	}

	public function _validFileExists($key)
	{
		if (!file_exists(DIR.$this->data[$key])) {
			$this->addError($key, 'File does not exists');
			return false;
		}
		return true;
	}
}
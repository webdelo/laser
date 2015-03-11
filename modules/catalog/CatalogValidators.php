<?php
namespace modules\catalog;
trait CatalogValidators
{
	use \core\traits\validators\Base;

	public function _validCode($code)
	{
		return $this->_validNotEmpty($code);
	}

	public function _validUniqueCode($code)
	{
		$id = CatalogFactory::getInstance()->getIdByCode($code);
		$dataId = isset($this->data['id']) ? $this->data['id'] : -1;
		if( $id     &&     ($id != $dataId) ){
			$this->setError('code', 'Товар с кодом "'.$code.'" уже существует!');
			return false;
		}
		return true;
	}

	public function _validName($name)
	{
		return $this->_validNotEmpty($name);
	}

	public function _validClass($class)
	{
		return class_exists($class, true);
	}

}
<?php
namespace core\traits\adapters;
trait Date
{
	public function _adaptDate($key)
	{
		$this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : '' ;
	}

	public function _adaptRegDate($key)
	{
		$this->data[$key] = (!empty($this->data[$key])) ? \core\utils\Dates::convertDate($this->data[$key], 'mysql') : time() ;
	}

	public function _adaptBirthday($key)
	{
		if (isset($this->data['birthDate']) && isset($this->data['birthMonth']) && isset($this->data['birthYear'])) {
				if(	(int)$this->data['birthDate'] == 0   ||
					(int)$this->data['birthMonth'] == 0   ||
					(int)$this->data['birthYear'] == 0
				)
					$this->data[$key] = 0;
				else
					$this->data[$key] = sprintf("%02d", $this->data['birthDate']).'-'.sprintf("%02d", $this->data['birthMonth']).'-'.sprintf("%02d", $this->data['birthYear']);
		} 
		$this->data = array_merge($this->data, array('birthday'=> ''));
		$this->_adaptDate($key);
	}
}
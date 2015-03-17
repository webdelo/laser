<?php
namespace core\traits\validators;
trait Base
{
	use \core\traits\validators\Email;

	public function _validNotEmpty($data, $defaultValues = array())
	{
		$defaultValues = (is_array($defaultValues)) ? $defaultValues : array();
		return !(empty($data)  || in_array($data, $defaultValues));
	}

	public function _validNumber($data,$settings = array())
	{
		if (empty($data) && !isset($settings['notEmpty']))
			return true;
		return is_numeric($data);
	}

	public function _validInt($data,$settings = array())
	{	
		if (empty($data) && !isset($settings['notEmpty']))
			return true;
		
		if(isset($settings['positive']))
			return (is_numeric($data) && $data > 0);
		return is_numeric($data);
	}

	public function _isUnique($data, $settings = array())
	{
		if (isset($settings['notEmpty']) && empty($data))
			return false;
		$table = empty($settings['table']) ? $this->mainTable() : $settings['table'];
		$q = "SELECT COUNT(*) FROM ".$table." WHERE ".$settings['field']."='?s'";
		$d = array($data);
		if (!empty($this->data[$this->idField])) {
			$q .= ' AND '.$this->idField.' !=?d';
			$d[] = $this->data[$this->idField];
		}
		$row = \core\db\Db::getMysql()->rowNum($q,$d);
		if ($row[0] != 0)
		$this->setError($settings['field'], $settings['message']);

		return ($row[0] == 0);
	}

	public function _validCharacters($data, $settings = array())
	{
		if ( \core\utils\Utils::isEmail($data) ) {
			$this->addError($settings['field'], $this->getErrorsList()['onlyCharacters'][\core\i18n\LangHandler::getInstance()->getLang()]);
			return true;
		}
		return true;
	}

	public function _validInTable($data, $settings = array())
	{
		return (!empty($data)) ? \core\db\Db::getMysql()->isExist($settings['table'],$settings['field'],$data) : true ;
	}

	public function _validIdInTable($data, $settings = array())
	{
		return (!empty($data)) ? !\core\db\Db::getMysql()->isExist($settings['table'],$settings['field'],$data) : true ;
	}

	protected function _validation()
	{
		foreach ($this->dataVar as $key) {
			if (!empty($this->dataRules[$key]['validation'])) {
				$rule = $this->dataRules[$key]['validation'];
				$settings = (isset($rule[1])) ? $rule[1] : '';
				$object = (method_exists($this->_moduleConfig, $rule[0])) ? $this->_moduleConfig : $this;
				$data = (isset($this->data[$key])) ? $this->data[$key] : false;
				$res = $object->$rule[0]($data, $settings);
				if ($res === false && $res != 'error_add' && !$this->errorExists($key))
					$this->setError($key);
			}

		}
		return !$this->errorsExists();
	}

	public function _validLimitString($data,$settings = array())
	{
		if(mb_strlen($data, 'utf-8') > $settings['limit']){
			$this->setError($settings['field'], $settings['message1'].$settings['limit'].$settings['message2']);
			return false;
		}
		return true;
	}
}

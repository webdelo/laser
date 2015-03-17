<?php
namespace core\traits\validators;
trait Alias
{
	public function _validAlias($data, $settings = array())
	{
		if (empty($data)) {
			$this->error['alias'] = $this->errorsList['empty_alias'];
			return false;
		}

		if( !preg_match( '/^[a-z0-9_.-]+$/', $data ) ) {
			$this->addError('alias', $this->getErrorsList()['denied_symbols'][\core\i18n\LangHandler::getInstance()->getLang()]);
			return true;
		}

		if (isset($this->data[$this->idField])) {
			$newAliasUnique = \core\db\Db::getMysql()->rowAssoc('SELECT `alias` FROM `'.$this->mainTable().'` WHERE `alias`="'.$data.'" AND `id`!='.$this->data[$this->idField]);
			return !$newAliasUnique['alias'];
		}
		return !\core\db\Db::getMysql()->isExist($this->mainTable(),'alias',$data);
	}
}
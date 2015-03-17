<?php
namespace core\traits\adapters;
trait Alias
{
	private $deniedSimbols = array(
		'<',
		'>' ,
		'«',
		'»',
		'№',
		'+',
		',',
		'\'',
		'&quot;',
		'!',
		'@',
		'#',
		'$',
		'%',
		'^',
		'&',
		'*',
		'=',
		'`',
		'~',
		':',
		';',
	);

	private $replaceWithUnderscoreSimbols = array(
		' ',
	);

	public function _adaptAlias($key)
	{
		$alias = !empty($this->data[$key]) ? $this->data[$key] : $this->data['name'];
		$alias = mb_strtolower(\core\utils\Utils::translit($alias));
		$alias = preg_replace("|[^\d\w ]-+|i", "", $alias);
		$alias = str_replace ($this->deniedSimbols, '', $alias );
		$alias = str_replace ($this->replaceWithUnderscoreSimbols, '_', $alias );

		if($this->isConstantAlias($alias))
			return $this->data[$key] = $alias;

		if(!$this->isAliasExist($alias))
			return $this->data[$key] = $alias;
		
		$this->data[$key] = $this->_transformAlias($alias);
	}

	private function isConstantAlias($alias)
	{
		return !empty($this->data['id'])  &&  $this->isAliasAndIdExist($alias);
	}

	private function isAliasAndIdExist($alias)
	{
		return \core\db\Db::getMysql()->rowAssoc('SELECT `alias` FROM `'.$this->mainTable().'` WHERE `alias`="'.$alias.'" AND `id`='.$this->data['id']);
	}

	private function isAliasExist($alias)
	{
		return \core\db\Db::getMysql()->rowAssoc('SELECT `alias` FROM `'.$this->mainTable().'` WHERE `alias`="'.$alias.'"');
	}

	private function _transformAlias($alias)
	{
		do {
			$array = explode('_', $alias);
			if (count($array) > 1) { // $alias already contains '_' char in it
				$last = array_pop($array);
				if( preg_match('/^\d+$/', $last) ) // if $last has numerical presentation
					$alias = implode('_', $array) . '_' . ($last+1);
				else
					$alias .= '_1';
			}
			else
				$alias .= '_1';

		} while($this->isAliasExist($alias));
		return $alias;
	}
}

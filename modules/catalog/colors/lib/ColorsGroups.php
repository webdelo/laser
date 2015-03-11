<?php
namespace modules\catalog\colors\lib;
class ColorsGroups extends \core\modules\base\ModuleDecorator
{
	function __construct($configObject)
	{
		$object = new ColorsGroupsObject($configObject);
		parent::__construct($object);
	}
	
	public function addGroup($data){
		$dataGroup = array(
			'name' => $this->truncateLastWord($data->goodName)
		);
		return $this->add($dataGroup);
	}
	
	public function truncateLastWord($string)
	{
		$array = explode(' ', $string);
		$lastWord = mb_strtolower($array[sizeof($array)-1], 'utf-8');
		if ( $lastWord == 'белый' || $lastWord == 'черный' )
			array_pop($array);
		$string = implode(' ', $array);
		return $string;
	}
}
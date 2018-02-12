<?php
namespace core\i18n;
class LangFieldWrapper
{
	static public function getFieldTranslateInArray(\core\modules\base\ModuleObject $object, $fieldGetter) 
	{
		foreach (LangHandler::getInstance()->getLangs() as $lang) {
			$array[$lang] = $object->$fieldGetter($lang);
		}
		return $array;
	}
	
	static public function printInputs($object, $getter, $field)
	{
		if ( $object instanceof \core\modules\base\ModuleObject ) {
			return self::printInputsFromObject($object, $getter, $field);
		} else if ( $object instanceof \core\Noop ) {
			return self::printInputsFromNoop($field);
		}
		throw new \Exception('Undefined first parameter in method '.__METHOD__.'. It\'s need to be ModuleObject or Noop');
	}
	
	static private function printInputsFromObject($object, $getter, $field)
	{
		$count=0; 
		foreach( self::getFieldTranslateInArray($object, $getter) as $lang=>$value ) { 
			$count++;
			$activator = $count==1?' activator':'';
			echo '<input type="text" name="'.$field.'_'.$lang.'" data-title="'.$lang.'" class="i18n'.$activator.'" value="'.$value.'" />';
		}
	}
	
	static private function printInputsFromNoop($field)
	{
		$count=0; 
		foreach( LangHandler::getInstance()->getLangs() as $lang ) { 
			$count++;
			$activator = $count==1?' activator':'';
			echo '<input type="text" name="'.$field.'_'.$lang.'" data-title="'.$lang.'" class="i18n'.$activator.'" value="" />';
		}
	}
	
	static public function printTextarea($object, $getter, $field)
	{
		if ( $object instanceof \core\modules\base\ModuleObject ) {
			return self::printTextareaFromObject($object, $getter, $field);
		} else if ( $object instanceof \core\Noop ) {
			return self::printTextareaFromNoop($field);
		}
		throw new \Exception('Undefined first parameter in method '.__METHOD__.'. It\'s need to be ModuleObject or Noop');
	}
	
	static private function printTextareaFromObject($object, $getter, $field)
	{
		$count=0; 
		foreach( self::getFieldTranslateInArray($object, $getter) as $lang=>$value ) { 
			$count++;
			$activator = $count==1?' activator':'';
			echo '<textarea name="'.$field.'_'.$lang.'" data-title="'.$lang.'" class="i18n'.$activator.'" cols="95" rows="10">'.$value.'</textarea>';
		}
	}
	
	static private function printTextareaFromNoop($field)
	{
		$count=0; 
		foreach( LangHandler::getInstance()->getLangs() as $lang ) { 
			$count++;
			$activator = $count==1?' activator':'';
			echo '<textarea		name="'.$field.'_'.$lang.'" data-title="'.$lang.'" class="i18n'.$activator.'" cols="95" rows="10"></textarea>';
		}
	}
}

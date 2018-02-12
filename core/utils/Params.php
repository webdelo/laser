<?php
namespace core\utils;
class Params
{
	public static function getParamsArray($string)
    {
		if (empty($string))
			return $string;
		
        $params = explode("\r\n", trim($string));
		foreach ($params as $key => $param){
			$param = explode(':', $param);
			$params[$key] = array('name' => $param[0], 'value' => $param[1]);
		}
		return $params;
    }
}
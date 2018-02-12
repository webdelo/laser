<?php
namespace core\traits\adapters;
trait Search
{
	protected function _outSearchText($data, $params)
	{
		$text        = strip_tags($data);
		$shift_left  = 0;
		$shift_right = 300;
		$current_value = '';

		if(strlen($params['search'])>1) {
			$pos = strripos($text, $params['search']);
			$shift_left = ($pos > 150 ) ? $pos-150 : 0 ;
			$shift_right = (strlen($text)<$pos+150) ? $pos+150 : $shift_right ;
			$current_value = substr($text, $pos, strlen($params['search']));
		}

		$text = substr($text, $shift_left, $shift_right);
		$text = str_ireplace($params['search'], '<span class="highlight">'.$current_value.'</span>', $text);

		return ($shift_left!=0) ? '...'.$text.'...' : $text.'...' ;
	}

	protected function _outMoveToText($data, $params)
	{

		$current_value = '';

		if(strlen($params['search'])>1) {
			$pos = strripos($data, $params['search']);
			$current_value = substr($data, $pos, strlen($params['search']));
		}
		$id = str_ireplace(' ', '_', $current_value);
		//
		$text = str_ireplace($params['search'], '<strong id="'.$id.'_move" class="highlight">'.$current_value.'</strong>', $data);
		return $text;
	}
}
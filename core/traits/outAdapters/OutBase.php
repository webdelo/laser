<?php
namespace core\traits\outAdapters;
trait OutBase
{
	public function _outHtml($data)
	{
		
		$data = htmlspecialchars_decode($data);
//		var_dump($data);
		$data = html_entity_decode($data);
//		var_dump($data);
//		$data = str_replace("\r\n", "<br>", $data);
		return $data;
	}
}
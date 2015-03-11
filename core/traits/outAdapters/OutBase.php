<?php
namespace core\traits\outAdapters;
trait OutBase
{
	public function _outHtml($data)
	{
		return htmlspecialchars_decode($data);
	}
}
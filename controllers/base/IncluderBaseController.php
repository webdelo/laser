<?php
namespace controllers\base;
class IncluderBaseController extends Controller
{
	public function __call($name, $args)
	{
		$object = '\\core\\files\\includers\\'.ucfirst($name).'Includer';
		if (class_exists($object))
			$object = new $object($this->getREQUEST()[0]);
		else
			$this->redirect404();
	}
	
}


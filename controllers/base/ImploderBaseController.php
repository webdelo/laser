<?php
namespace controllers\base;
class ImploderBaseController extends Controller
{
	public function __call($name, $args)
	{
		$object = '\\core\\files\\imploders\\'.ucfirst($name).'Imploder';
		if (class_exists($object)){
			return new $object($args);	
		} else {
			$this->redirect404();
		}
	}
}


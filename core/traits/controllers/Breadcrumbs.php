<?php
namespace core\traits\controllers;
trait Breadcrumbs
{
	static public $breadcrumbs = array();
	
	public function setLevel($name, $url=null, $title=null) 
	{
		self::$breadcrumbs[] = array(
			'name'  => $name,
			'url'   => $url,
			'title' => $title
		);
		
		return $this;
	}
	
	public function setLevels ($object)
	{
		$this->setLevel($object->category->getParent()->name, $object->category->getParent()->getPath())
			 ->setLevel($object->category->name, $object->category->getPath());
	}

	public function includeBreadcrumbs($filename='breadCrumbs')
	{
		$this->setContent('breadcrumbs', self::$breadcrumbs)
			 ->includeTemplate($filename);
	}		
	
}
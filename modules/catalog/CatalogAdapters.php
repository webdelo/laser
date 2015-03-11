<?php
namespace modules\catalog;
trait CatalogAdapters
{
	public function _adaptCode($key)
	{
		if (empty($this->data[$key])){
			$id = isset($this->data['id']) ? $this->data['id'] : CatalogFactory::getInstance()->getLastId()+1;
			$this->data[$key] = $id + 2000;
		}
		return $key;
	}
}
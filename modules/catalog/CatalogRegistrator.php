<?php
namespace modules\catalog;
class CatalogRegistrator extends \core\modules\base\ModuleObjects
{
	use	\modules\catalog\CatalogAdapters,
		\modules\catalog\CatalogValidators;

	protected $code;
	protected $name;
	protected $class;

	protected function addGood()
	{
		if ($this->getErrors())
			return false;
		return CatalogFactory::getInstance()->add($this->code, $this->name, $this->class);
	}

	public function setCode($code)
	{
		$code = (string)$this->_adaptCode($code);
		if($this->_validUniqueCode($code))
			$this->code = $code;
		return $this;
	}

	public function setName($name)
	{
		$name = (string)$name;
		if ($this->getConfig()->_validName($name)) {
			$this->name = $name;
		} else
			$this->setError('name', 'Name is not valid!');
		return $this;
	}

	protected function setClass($class)
	{
		if ($this->getConfig()->_validClass($class))
			$this->class = $class;
		else
			$this->setError('class', 'Non-existent good class!');
		return $this;
	}

	public function add($data, $fields = NULL)
	{
		$this->setClass($this->getConfig()->getObjectClass());
		$this->_beforeChangeWithoutAdapt($data, $this->getConfig()->getObjectFields());
		if ($this->getErrors()) {
			return false;
		} else {
			$data['id'] = $this->addGood();
			parent::add($data);
			return $data['id'];
		}
	}
}
<?php
namespace core\traits;
trait Errors
{
	protected $errors = array();
	protected $errorsList;

	public function setError($key, $text=null)
	{
		$text = $text ? $text : $this->getErrorsList()['defaultError'];
		$text = (empty($this->getErrorsList()[$key])) ? $text : $this->getErrorsList()[$key];
		$this->addError($key, $text);
		return $this;
	}

	private function getErrorsList()
	{
		if (empty($this->errorsList)) {
			include(DIR.'includes/errorsList.php');
			$this->errorsList = $errorsList;
		}
		return $this->errorsList;
	}

	public function addError($key, $value)
	{
		$this->errors[(string)$key] = (string)$value;
		return $this;
	}

	public function getErrors()
	{
		return (empty($this->errors)) ? false : $this->errors;
	}

	public function errorsExists()
	{
		return !empty($this->errors);
	}

	public function errorExists($key)
	{
		return !!$this->getError($key);
	}

	public function getError($key)
	{
		return isset($this->errors[$key]) ? $this->errors[$key] : false;
	}

	public function resetErrors()
	{
		$this->errors = array();
		return $this;
	}
}
<?php
namespace core\utils;
class Protector
{
	protected static $instance;
	
	private $_magicQuotes;
	private $_data;

	public static function getInstance()
	{
		if (is_null(self::$instance))
			self::init();
		return self::$instance;
	}
	
	public static function init()
	{
		self::$instance = new Protector();
	}
	
	private function __construct()
	{
		$this->_setMagicQuotesFlag();
	}
	
	private function _setMagicQuotesFlag()
	{
		$this->_magicQuotes = (get_magic_quotes_gpc()) ? true : false;
		return $this;
	}
	
	public function xss(&$data)
	{
		$this->_setData($data)->clearData()->htmlspecialcharsEncodeData();
		return $this;
	}
	
	private function _setData(&$data)
	{
		$this->_data = &$data;
		$this->escapeData();
		return $this;
	}
	
	private function escapeData()
	{
		if ($this->_magicQuotes) {
			$this->_data = $this->_escapeDataRecursive($this->_data);
		}
		return $this;
	}
	
	private function _escapeDataRecursive($data)
	{
		if (is_array($data))
			foreach ($data as $key=>$value){
				$data[$key] = $this->_escapeDataRecursive($value);
			}
		else
			$data = mysql_real_escape_string($data);
		return $data;
	}
	
	private function clearData()
	{
		if ($this->_magicQuotes) {
			$this->_data = $this->_clearDataRecursive($this->_data);
		}
		return $this;
	}
	
	private function _clearDataRecursive($data)
	{
		if (is_array($data))
			foreach ($data as $key=>$value){
				$data[$key] = $this->_eclearDataRecursive($value);
			}
		else
			$data = stripslashes($data);
		return $data;
	}
	
	private function htmlspecialcharsEncodeData()
	{
		$this->_data = $this->_htmlspecialcharsEncodeDataRecursive($this->_data);
		return $this;
	}
	
	private function _htmlspecialcharsEncodeDataRecursive($data)
	{
		if (is_array($data))
			foreach ($data as $key=>$value){
				$data[$key] = $this->_htmlspecialcharsEncodeDataRecursive($value);
			}
		else
			$data = htmlspecialchars($data);
		return $data;
	}

}
<?php
namespace core\i18n\translator;
abstract class FileTranslateData implements ITranslateData
{
	private $data = array();

	public function getPattern($alias, $lang)
	{
		if ( !array_key_exists($alias, $this->getData()) )
			throw new \Exception('Alias - '.$alias.' not found in language data source.');

		if ( !array_key_exists($lang, $this->getData()[$alias]) )
			throw new \Exception('Language - '.$lang.' not found in language data source.');

		return $this->getData()[$alias][$lang];
	}

	public function setDataFromFile($filePath)
	{
		if ( !$this->getData() ) {
			include $filePath;
			return $this->setData($data);
		}
		return $this;
	}

	private function setData( array $data)
	{
		$this->data = $data;
		return $this;
	}

	private function getData()
	{
		return $this->data;
	}
}

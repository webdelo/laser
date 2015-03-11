<?php
namespace modules\catalog\availability\automaticUpdates;
class UntilovParser extends BaseParser
{
	private $file;
	protected $partnerId = 4;

	public function __construct($filePath)
	{
		$this->file = new \core\files\uploader\File($filePath);
		$this->setAvailabiliteUpdateGoods($this->parseFile());
	}
	
	public function getPartnerId(){
		return $this->partnerId;
	}
	
	private function parseFile()
	{
		$fileContent = $this->file->getFileContent();
		$rows = explode("\r\n", $fileContent);
		foreach ($rows as $key => $value){
			$rows[$key] =  explode(';', $value);
		}
		return $rows;
	}
	
	private function setAvailabiliteUpdateGoods($csvDataArray)
	{
		foreach($csvDataArray as $good){
			if (!empty($good[1]))
				$this->availabiliteUpdateGoods[] = new AvailabiliteUpdateGood($good[0], $good[1], $good[3], $good[2], $good[4], $good[6]);
		}
		return $this;
	}
}
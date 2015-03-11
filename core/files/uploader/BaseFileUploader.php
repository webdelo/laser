<?php
namespace core\files\uploader;
abstract class BaseFileUploader extends \core\files\DirectoryFilesReader
{
	protected $tempFolder;
	protected $fileActualTimeLimit = 1800; // seconds
	protected $allowedExtensions = array();
	protected $inputKey;

	private $_extension;
	private $_fileData;

	public function __construct()
	{
		$this->tempFolder = DIR . $this->tempFolder;
		parent::__construct($this->tempFolder);
		$this->clearTempFolder();
	}

	public function clearTempFolder()
	{
		$tempDirectoryFilesList = $this->getFilesList();
		if (isset($tempDirectoryFilesList))
			foreach ($tempDirectoryFilesList as $file) {
				$fileStats = stat($this->tempFolder.'/'.$file);
				$fileCreationDate = $fileStats[9];
				if ($this->isFileOutdated($fileCreationDate)) {
					unlink($this->tempFolder.'/'.$file);
				}
			}
	}

	private function isFileOutdated($fileCreationDate)
	{
		return ( (time() - $fileCreationDate) > $this->fileActualTimeLimit );
	}

	public function upload()
	{
		try {
			 return $this->getFileData()
						 ->getFileExtension()
						 ->checkFileMimeType()
						 ->moveUploadedFile();
		} catch (Exception $e) {
			return $this->getResultArray($e->getMessage());
		}

	}

	protected function getFileData()
	{
		if (empty($_FILES[$this->inputKey]))
			throw new \Exception('Variable $_FILES['.$this->inputKey.'] is empty!');
		$this->_fileData = $_FILES[$this->inputKey];
		return $this;
	}

	protected function getFileExtension()
	{
		$fileParts = pathinfo($this->_fileData['name'][0]);
		$this->_extension = $fileParts['extension'];
		return $this;
	}

	protected function checkFileMimeType()
	{
		if (in_array($this->_extension,  $this->allowedExtensions))
			return $this;
		throw new \Exception('You can upload only the following file extensions: '.implode(', ', $this->allowedExtensions).'!');
	}

	protected function moveUploadedFile()
	{
		$errorMessage = 'The temporary directory does not exist or is not writable rights!';
		$tempFileName = $this->getTempFileName();
		return (@move_uploaded_file($this->_fileData['tmp_name'][0], $tempFileName)) ? $this->getResultArray($this->getFileUrl($tempFileName), true) : $this->getResultArray($errorMessage);
	}

	protected function getTempFileName()
	{
		return $this->tempFolder.session_id().'-'.str_replace(' ', '', microtime()).'.'.$this->_extension;
	}

	protected function getResultArray($message, $result = false)
	{
		return array(
			'result'  => $result,
			'message' => $message
		);
	}

	protected function getFileUrl($tempFileName)
	{
		return '/'.str_replace(DIR, '', $tempFileName);
	}

}
<?php
namespace core\modules\filesUploaded;
class FilesFileUploader extends \core\files\uploader\BaseFileUploader
{
	protected $tempFolder = 'tmp/files/';
	protected $allowedExtensions = array(
		'pdf',
		'PDF',
		'doc',
		'DOC',
		'docx',
		'DOCX',
		'txt',
		'TXT',
		'xls',
		'XLS',
		'xlsx',
		'XLSX',
		'zip',
		'ZIP',
		'rar',
		'RAR',
		'xml',
		'XML',

		'gif',
		'GIF',
		'png',
		'PNG',
		'jpeg',
		'JPEG',
		'jpg',
		'JPG',
		'tif',
		'TIF'
	);
	protected $inputKey = 'Filedata';

	public function __construct()
	{
		parent::__construct();
	}

}
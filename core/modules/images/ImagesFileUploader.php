<?php
namespace core\modules\images;
class ImagesFileUploader extends \core\files\uploader\BaseFileUploader
{
	protected $tempFolder = 'tmp/images/';
	protected $allowedExtensions = array(
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
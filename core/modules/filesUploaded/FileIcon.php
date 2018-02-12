<?php
namespace core\modules\filesUploaded;
class FileIcon
{
	protected $iconsArray = array(
		'application/msexcel' => 'file1_excel.png',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'file1_excel.png',

		'application/msword' => 'file2_word.png',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'file2_word.png',

		'application/pdf' => 'file3_acrobat.png',
		'application/zip' => 'file4_archiveZip.png',
		'application/x-rar-compressed' => 'file5_archiveRar.png',
		'text/plain' => 'file6_notepad.png',
		'image/jpeg' => 'file7_pictureJpg.png',
		'image/png' => 'file8_picturePng.png',
		'defaultIcon' => 'file9_file.png',
	);

	function __construct(){}

	public function getFileIconByType($type)
	{
		$type = isset($this->iconsArray[$type]) ? $type : 'defaultIcon';
		return DIR_ADMIN_HTTP.'images/bg/'.$this->iconsArray[$type];
	}
}

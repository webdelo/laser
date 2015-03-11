<?php
namespace core\files\imploders;
class CSSimploder extends FileImploder
{	
	public function __construct($args)
	{
		$this->setConfig(array(
				'filePathIn'  => DIR.'css/',
				'filePathOut' => DIR.'css/tmp/',
				'httpPathOut' => '/includer/css/',
				'ext'         => '.css',
				'compress'    => false,
				'cache'       => true,
				'finalSymbol' => '/'
		));
	}
	
	public function getTag($fileName = null, $path = null)
	{
		echo (empty($fileName) && empty($path))
			? "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->config['httpPathOut'].$this->finalFileName.$this->config['finalSymbol']."\" />\r\n"
			: "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$path.$fileName."\" />\r\n";
	}
}
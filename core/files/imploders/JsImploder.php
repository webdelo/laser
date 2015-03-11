<?php
namespace core\files\imploders;
class JSimploder extends FileImploder
{	
	public function __construct($args)
	{
		$this->setConfig(array(
				'filePathIn'  => DIR_HTTP.'js/',
				'filePathOut' => DIR_HTTP.'js/tmp/',
				'httpPathOut' => '/includer/js/',
				'ext'         => '.js',
				'compress'    => false,
				'cache'       => true,
				'finalSymbol' => '/'
		));
	}
	
	public function getTag($fileName = null, $path = null)
	{
		echo (empty($fileName) && empty($path))
			? "<script type=\"text/javascript\" src=\"".$this->config['httpPathOut'].$this->finalFileName.$this->config['finalSymbol']."\"></script>\r\n"
			: "<script type=\"text/javascript\" src=\"".$path.$fileName."\"></script>\r\n";
	}
}

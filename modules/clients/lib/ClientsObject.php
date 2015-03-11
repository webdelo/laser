<?php
namespace modules\clients\lib;
class ClientsObject extends \core\authorization\UserRegistrator
{
    	protected $configClass     = '\modules\clients\lib\ClientConfig';
	
	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
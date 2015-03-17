<?php
namespace modules\clients\lib;
class Clients extends \core\authorization\UserRegistrator
{
	use \core\traits\ObjectPool,
		\core\modules\statuses\StatusesTraitDecorator,
		\core\modules\images\ImagesTraitDecorator;

    protected $configClass     = '\modules\clients\lib\ClientConfig';
	protected $objectClassName = '\modules\clients\lib\Client';

	function __construct()
	{
		parent::__construct(new $this->configClass);
	}
}
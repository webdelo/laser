<?php
namespace modules\modulesDomain\lib;
class RelationModuleDomainConfig extends \core\modules\base\ModuleConfig
{
	protected $objectClass = '\modules\modulesDomain\lib\ModuleDomain';
	protected $objectsClass = '\modules\modulesDomain\lib\ModulesDomain';

	protected $tablePostfix = '_modules_domain'; // set value without preffix!
	protected $idField = 'objectId';
	protected $objectFields = array(
		'id',
		'ownerId',
		'objectId',
	);

}

<?php
namespace modules\catalog\domainsInfo\lib;
class DomainInfoConfig extends \core\modules\base\ModuleConfig
{
	use \core\traits\validators\Base,
		\core\traits\validators\DomainAlias,
		\core\traits\validators\Alias,
		\core\traits\adapters\Base,
		\core\traits\outAdapters\OutBase;
	
	const ACTIVE_STATUS_ID  = 1;
	const BLOCKED_STATUS_ID = 2;

	protected $objectClass  = '\modules\catalog\domainsInfo\lib\DomainInfo';
	protected $objectsClass = '\modules\catalog\domainsInfo\lib\DomainsInfo';

	public $templates  = 'modules/catalog/domainsInfo/tpl/';
	public $imagesPath = 'files/domainsInfo/images/';
	public $imagesUrl  = 'data/images/domainsInfo/';

	protected $table = 'catalog_domainsinfo'; // set value without preffix!
	protected $idField = 'id';
	protected $objectFields = array(
		'id',
		'domainAlias',
		'objectId',
		'code',
		'alias',
		'name',
		'smallDescription',
		'description',
		'text',
		'metaTitle',
		'metaKeywords',
		'metaDescription',
		'headerText',
	);

	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty'),
			),
			'domainAlias' => array(
				'validation' => array('_validDomainAlias'),
			),
			'alias' => array(
				'validation' => array('_validAlias'),
				'adapt' => '_adaptAlias',
			),
			'code' => array(
				'validation' => array('_isUnique', array('notEmpty' => true, 'field' => 'code')),
			),
			'smallDescription, description, text, metaTitle, metaKeywords, metaDescription, headerText' => array(
				'adapt' => '_adaptHtml',
			),
			'objectId' => array(
				'validation' => array('_validInt', array('notEmpty'=>true)),
			),
		);
	}
	
	public function outputRules()
	{
		return array(
			'text' => array('_outHtml'),
			'description' => array('_outHtml'),
			'smallDescription' => array('_outHtml'),
		);
	}
				
}
<?php
namespace controllers\admin;
class SubGoodsAdminController extends \controllers\base\Controller
{
	use \core\traits\controllers\Templates;


	protected $permissibleActions = array(
		'getSubGoodsTable',
		'ajaxGetSubGoodsTable',
		'ajaxAddSubGood',
		'ajaxDeleteSubGood'
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config          = new \modules\catalog\subGoods\lib\SubGoodConfig();
		$this->objectClass      = $this->_config->getObjectClass();
		$this->objectsClass     = $this->_config->getObjectsClass();
		$this->objectClassName  = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function getSubGoodsTable($goodId)
	{
		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($goodId);
		$this->setContent('good', $good)
			->setContent('subGoods', $good->getSubGoods())
			 ->includeTemplate('subGoodsTable');
	}

	protected function ajaxGetSubGoodsTable()
	{
		echo $this->getSubGoodsTable($this->getPost()->goodId);
	}

	protected function ajaxAddSubGood()
	{
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		$this->ajax($objectId, 'ajax', true);
	}

	protected function ajaxDeleteSubGood()
	{
		$subGood = $this->getObject($this->objectClass, $this->getPost()->subGoodId);
		$this->ajaxResponse($subGood->remove());
	}


}
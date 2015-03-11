<?php
namespace controllers\admin;
class ComplectGoodsAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization;

	protected $permissibleActions = array(
		'add',
		'edit',
		'remove',
		'ajaxAddComplectGood',
		'ajaxDeleteComplectGood',
		'getComplectGoodsTableByComplectId',
		'ajaxEditComplectGood',
		'ajaxGetAutosuggestGoods',
		'ajaxGetPriceByQuantity',
		'ajaxGetAutosuggestGoodById'
	);

	private $deniedClassesForAutosugest = array(
		'modules\catalog\complects\lib\Complect',
		'modules\catalog\offers\lib\Offer'
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\catalog\complects\complectGoods\lib\ComplectGoodConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->redirect404();
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('complect_edit');
		$objectId =  $this->setObject($this->_config->getObjectsClass())
						  ->modelObject->add(
								$this->getPOST(),
								$this->modelObject->getConfig()->getObjectFields()
						  );
		if ($objectId)
			$this->setObject($this->_config->getObjectClass(), $objectId);
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('complect_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}


	protected function remove()
	{
		$this->checkUserRightAndBlock('complect_edit');
		if (isset($this->getREQUEST()[0]))
			$objectId = (int)$this->getREQUEST()[0];

		if (!empty($objectId)) {
			$object = $this->getObject($this->objectClass, $objectId);
			$this->ajaxResponse($object->remove());
		}
	}

	protected function ajaxAddComplectGood()
	{
		$this->add();
	}

	protected function ajaxDeleteComplectGood()
	{
		$this->getREQUEST()[0] = $this->getPost()['goodId'];
		$this->remove();
	}

	protected function getComplectGoodsTableByComplectId($complectId = null)
	{
		$complectId = empty($complectId) ? $this->getPOST()['complectId'] : $complectId;
		$complect = $this->getObject('\modules\catalog\complects\lib\Complect', $complectId);
		$this->setContent('complectId', $complectId)
			->setContent('complectGoods', $complect->getComplectGoods())
			->includeTemplate('complectGoodsTable');
	}

	protected function ajaxEditComplectGood()
	{
		$complectGood = $this->getObject($this->objectClass, $this->getPOST()['id']);
		$res = $complectGood->edit($this->getPost(), array('id', 'discount', 'quantity', 'goodDescription', 'mainGood'));
		echo $res;
	}

	protected function ajaxGetAutosuggestGoods()
	{
		$objects = \modules\catalog\CatalogFactory::getInstance()->getGoodsByNameOrCode($_GET["q"]);
		foreach($objects as $object){
			if(!in_array(get_class($object), $this->deniedClassesForAutosugest)){
				try {
					$json = array();
					$json['value'] = $object->id;
					$json['name'] = $object->getName();
					$json['code'] = $object->getCode();
					$json['price'] = is_object($object->getPriceByMinQuantity()) ? 'no price' : $object->getPriceByMinQuantity();
					$json['basePrice'] = is_object($object->getBasePriceByMinQuantity()) ? 'no basePrice' : $object->getBasePriceByMinQuantity();
					if($json['basePrice'] == null)
						$json['basePrice'] = '0';
				} catch (\exceptions\ExceptionPrice $exc) {
					$json['price'] =  'no price';
					$json['basePrice'] =  'no basePrice';
				}
				$data[] = $json;
			}
		}

		if(!isset($data)){
			$data[0]['value'] = 'no value';
			$data[0]['name'] = 'Результатов не найдено';
			$data[0]['code'] = 'no code';
			$data[0]['price'] = 'no price';
			$data[0]['basePrice'] = 'no basePrice';
		}

		echo json_encode($data);
	}

	protected function ajaxGetAutosuggestGoodById()
	{
		$object = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['goodId']);
		try {
			$json = array();
			$json['quantity'] = $object->getMinQuantity();
			$json['price'] = is_object($object->getPriceByMinQuantity()) ? 'no price' : $object->getPriceByMinQuantity();
			$json['basePrice'] = is_object($object->getBasePriceByMinQuantity()) ? 'no basePrice' : $object->getBasePriceByMinQuantity();
			if($json['basePrice'] == null)
				$json['basePrice'] = '0';
		} catch (\exceptions\ExceptionPrice $exc) {
			$json['quantity'] = 'no quantity';
			$json['price'] =  'no price';
			$json['basePrice'] =  'no basePrice';
		}
		echo json_encode($json);
	}

	protected function ajaxGetPriceByQuantity()
	{
		$object = \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getPOST()['id']);
		try {
			$json = array();
                        $price = $object->getPrices()->getPriceByQuantity($this->getPOST()['quantity']);
			$json = $price->getPrice();
		} catch (\exceptions\ExceptionPrice $exc) {
			$json = '';
		}
		echo json_encode($json);
	}
}

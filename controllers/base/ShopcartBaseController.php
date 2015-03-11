<?php
namespace controllers\base;
class ShopcartBaseController extends Controller
{
	private $shopcartObject;

	protected $permissibleActions = array(
		'ajaxGetSimpleShopcartBlock',
		'ajaxAddGood',
		'addGood',
		'ajaxResetShopcart',
		'ajaxRemoveGood',
		'removeGood',
		'ajaxGetShopcartGoodsTableContent',
		'getSimpleShopcartBlock',
	);

	protected $templates = array(
		'fullPage'    => 'shopcart/shopcart',
		'simpleBlock' => 'shopcart/shopcartSimpleBlock',
		'goodsTable'  => 'shopcart/shopcartGoodsTable',
	);

	public function __construct()
	{
		parent::__construct();
		$this->shopcartObject = \modules\shopcart\lib\Shopcart::getInstance();
	}

	protected function getShopcart()
	{
		return $this->shopcartObject;
	}

	protected function defaultAction()
	{
		$this->setAction('viewShopcart')->viewShopcart();
	}

	protected function viewShopcart()
	{
		$this->setContent('shopcart', $this->getShopcart())
			 ->includeTemplate($this->templates['fullPage']);
	}

	protected function getSimpleShopcartBlock()
	{
		ob_start();
		$this->setContent('shopcart', $this->getShopcart())
			->includeTemplate($this->templates['simpleBlock']);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	protected function ajaxGetSimpleShopcartBlock()
	{
		echo $this->getSimpleShopcartBlock();
	}

	protected function ajaxAddGood()
	{
		$post = new \core\ArrayWrapper($this->getPOST());
		$this->ajaxResponse( $this->addGood($post->goodClass, $post->goodId, $post->quantity) );
	}

	protected function addGood($goodClass, $goodId, $quantity)
	{
		return $this->setObject($this->getShopcart())
				->modelObject->addGood($goodClass, $goodId, $quantity);
	}

	protected function ajaxResetShopcart()
	{
		$this->getShopcart()->resetShopcart();
		$this->ajaxResponse(true);
	}

	protected function ajaxRemoveGood()
	{
		$data = new \core\ArrayWrapper($this->getPOST());
		$this->ajaxResponse( $this->removeGood($data) );
	}

	protected function removeGood($data)
	{
		return $this->setObject($this->getShopcart())
				->modelObject->removeGoodByCode($data->goodCode);
	}

	protected function getShopcartGoodsTableContent()
	{
		ob_start();
		$this->setContent('shopcart', $this->getShopcart())
			->includeTemplate($this->templates['goodsTable']);
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	protected function ajaxGetShopcartGoodsTableContent()
	{
		echo $this->getShopcartGoodsTableContent();
	}

	public function getTotalPrice()
	{
		return $this->getShopcart()->getTotalPrice();
	}
}
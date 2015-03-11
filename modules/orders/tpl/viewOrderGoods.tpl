<script type="text/javascript" src="/modules/orders/js/transformer<?=($this->checkUserRight('order_edit_from_view_mode'))?'Edit':'View'?>Mode.js"></script>
<script type="text/javascript" src="/modules/orders/js/goodsTableFunctions.js"></script>
<table class="goodsList">
	<tr class="top">
		<td>№</td>
		<td colspan="3">Товар</td>
		<td>Цена, <span class="grayText">руб.</span></td>
		<td>Себестоимость<br />(за шт.), <span class="grayText">руб.</span></td>
		<td>Кол-во, <span class="grayText">шт.</span></td>
		<td>Стоимость, <span class="grayText">руб.</span></td>
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<td>Себестоимость, <span class="grayText">руб.</span></td>
		<td><?= $this->isAuthorisatedUserAnManager() ? 'Откат' : 'Прибыль'?>, <span class="grayText">руб.</span> <?=$order->getCashRatePrice()?'( -'.$order->cashRate.'% )':''?> </td>
		<? endif; ?>
	</tr>
	<? $count=0; foreach( $order->getOrderGoods() as $good ): ?>
	<tr class="line">
		<td><?=++$count?></td>
		<td style="border-right: none;">
			<a href="http://<?=$good->getGood()->getGoodDomain()?><?=$this->isAuthorisatedUserAnManager() ? $good->getGood()->getPath() : $good->getGood()->getAdminUrl()?>/" target="blank">
				<img src="<?=$good->getGood()->getFirstPrimaryImage()->getImage('40x40')?>" />
			</a>
		</td>
		<td style="border-left: none; text-align: left;">
			<a href="http://<?=$good->getGood()->getGoodDomain()?><?=$this->isAuthorisatedUserAnManager() ? $good->getGood()->getPath() : $good->getGood()->getAdminUrl()?>/" target="blank" class="goodLink">
				<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)
			</a>
			<div class="comment">
				<textarea
					name="goodDescription"
					class="editDelivery transformer"
					data-action="/admin/orderGoods/ajaxEditOrderGood/"
					data-post="&id=<?=$good->id?>"
					data-default="добавить описание"
				><?=$good->goodDescription?></textarea>
			</div>
		</td>
		<td>
			<?if(!$this->isAuthorisatedUserAnManager()):?>
			<?if($this->isHasParametersOrProperties($good)):?>
			<span href="/admin/orders/getOrderGoodDetails/?objectId=<?=$good->id?>" title="Назначить свойства и параметры" class="goodLink orderGoodDetailsModal" modalTitle="<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)">
				<div class="action_buts">
			<a>Свойства</a>
			</div>
			</span>
			<?endif?>
			<?endif?>
		</td>
		<td>
			<input
				type="text"
				class="editDelivery transformer"
				name="price"
				value="<?=$good->price?>"
				data-action="/admin/orderGoods/ajaxEditOrderGood/"
				data-post="&id=<?=$good->id?>"
			/>
		</td>
		<td>
			<input
				type="text"
				class="editDelivery transformer"
				name="basePrice"
				value="<?=$good->basePrice?>"
				data-action="/admin/orderGoods/ajaxEditOrderGood/"
				data-post="&id=<?=$good->id?>"
			/>
		</td>
		<td>
			<input
				type="text"
				class="editDelivery transformer"
				name="quantity"
				value="<?=$good->quantity?>"
				data-action="/admin/orderGoods/ajaxEditOrderGood/"
				data-post="&id=<?=$good->id?>"
			/>
		</td>
		<td><?=\core\utils\Prices::standartPrice($good->getPrice())?></td>
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<td><?=\core\utils\Prices::standartPrice($good->getBasePrice())?></td>
		<td>
			<?=\core\utils\Prices::standartPrice($good->getIncome())?>
			<?if($this->checkUserRight('orderGoods_delete') && $order->getOrderGoods()->count() > 1 ):?>
			<div style="position: relative; width: 100px;">
				<a
					class="pointer delete deleteOrderGoodFromView hide confirm"
					title="Удалить товар"
					data-action="/admin/orderGoods/ajaxDeleteOrderGood/"
					data-post="goodId=<?=$good->id?>"
					data-confirm="Удалить товар?"
				></a>
			</div>
			<?endif;?>
		</td>
		<? endif; ?>
	</tr>
	<? endforeach; ?>
	<? if ( $this->isNotNoop($order->getPromoCode()) ): ?>
	<tr class="line">
		<td colspan="6" style="text-align: right; padding-right: 10px;">
			<strong>( <?=$order->getPromoCode()->name?> ) Промо-скидка:</strong>
		</td>
		<td>
			 <?=$order->getPromoCode()->discount?>%
		</td>
		<td>
			-<?=\core\utils\Prices::standartPrice($order->getOrderGoods()->getPromoCodeDiscountSum())?>
		</td>
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<td>0</td>
		<td>0</td>
		<? endif; ?>
	</tr>
	<? endif; ?>
	<tr class="line resultLine">
		<td colspan="6" style="text-align: right; padding-right: 10px;">
			<strong>Сумма за товары:</strong>
		</td>
		<td>
			<?=$order->getOrderGoods()->getTotalGoodsQuantity()?>
		</td>
		<td>
			<?=\core\utils\Prices::standartPrice($order->getOrderGoods()->getTotalGoodsSum())?>
		</td>
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<td>
			<?=\core\utils\Prices::standartPrice($order->getOrderGoods()->getTotalGoodsBaseSum())?>
		</td>
		<td>
			<?=\core\utils\Prices::standartPrice($order->getOrderGoods()->getIncome())?>
		</td>
		<? endif; ?>
	</tr>
	<? if ( $this->isNotNoop($order->getDelivery()) ): ?>
	<tr class="line resultLine">
		<td colspan="7" style="text-align: right; padding-right: 10px;">
			<strong><?=$order->getDelivery()->getCategory()->name?> ( <?=$order->getDelivery()->getName()?> ):</strong>
		</td>
		<td>
			<input
				type="text"
				name="deliveryPrice"
				class="editDelivery transformer"
				data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
				value="<?=$order->deliveryPrice?>"
			/>
		</td>
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<td>
			<input
				type="text"
				name="deliveryBasePrice"
				class="editDelivery transformer"
				data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
				value="<?=$order->deliveryBasePrice?>"
			/>
		</td>
		<td >
			<?=\core\utils\Prices::standartPrice($order->deliveryPrice - $order->deliveryBasePrice)?>
			<?if($this->checkUserRight('orderGoods_delete')):?>
			<div style="position: relative; width: 100px;">
				<a
					class="pointer delete deleteDeliveryFromView confirm"
					title="Удалить доставку"
					data-action="/admin/orders/ajaxRemoveDeliveryFromOrder/<?=$order->id?>"
					data-method="post"
					data-confirm="Удалить доставку?"
				></a>
			</div>
			<? endif; ?>
		</td>
		<? endif; ?>
	</tr>
	<? endif; ?>
	<tr class="line resultLine">
		<td colspan="7" style="text-align: right; padding-right: 10px;">
			<strong>Итого:</strong>
		</td>
		<td>
			<?=\core\utils\Prices::standartPrice($order->getTotalSum())?>
		</td>
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<td>
			<?=\core\utils\Prices::standartPrice($order->getTotalBaseSum())?>
		</td>
		<td title="С учетом обналички <?=$order->cashRate?>%">
			<?=\core\utils\Prices::standartPrice($order->getIncome())?>
			<? if($order->getCashRatePrice()): ?>( -<?=$order->getCashRatePrice()?> )<? endif; ?>
		</td>
		<? endif; ?>
	</tr>
</table>

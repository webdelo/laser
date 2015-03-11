<? if ( $goods->count() > 0 ): ?>
<script type="text/javascript">
	$(function(){
		var order = new orderProcessing;
		order.editInput('.editInput', function (response) {
			if (typeof response === 'number')
				this.reloadGoodsTable()
					.reloadResultPriceBlock()
			else
				alert(response);
		});
	});
</script>

<table class="goodsList">
	<tr class="top">
		<td width="15">#</td>
		<td class="borderLeft">Товар</td>
		<td class="borderLeft" style="width:226px;"><span class="priceAdditionalInfo">Поставщик /</span> Цена <span class="priceAdditionalInfo">/ Доход </span></td>
		<td class="borderLeft">Количество</td>
		<td class="borderLeft">Стоимость</td>
		<td class="borderLeft"><img src="/admin/images/bg/trash.png" alt="Удалить"></td>
	</tr>
	<? $count = 0; foreach($goods as $good):?>
	<tr class="line" data-id="<?=$good->id?>">
		<td><?=++$count?></td>
		<td>
			<div class="normalView">
				<a href="<?=$good->getGood()->getAdminUrl()?>/" target="blank" title="Показать товар">
					<img src="<?=$good->getGood()->getFirstPrimaryImage()->getImage('40x40')?>">
					<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)
				</a>
				<?if($this->isHasParametersOrProperties($good)):?>
				<span style="margin-top:0px;" href="/admin/orderProcessing/getOrderProcessingGoodDetails/?objectId=<?=$good->id?>" title="Назначить свойства и параметры" class="goodLink orderGoodDetailsModal" modalTitle="<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)">
					<a class="graybutton">Свойства</a>
				</span>
				<?endif?>
				<div class="clear"></div>
				<span>(в наличии <?=$good->getGood()->getTotalAvailability()?> шт.)</span>
			</div>
		</td>
		<td>
			<div class="normalView">
				<span class="priceAdditionalInfo"> <?=$good->getBasePrice()?> /</span>
				<input
					name="price"
					value="<?=$good->getPrice()?>"
					class="editInput"
					data-action="/admin/orderProcessing/editGood/?goodId=<?=$good->id?>&orderId=<?=$order->id?>"
					data-method="post"
				>
				<span class="priceAdditionalInfo">/ <?=$good->getPrice() - $good->getBasePrice()?> </span>
			</div>
		</td>
		<td>
			<div class="normalView">
				<input
					name="quantity"
					value="<?=$good->getQuantity()?>"
					class="editInput"
					data-action="/admin/orderProcessing/editGood/?goodId=<?=$good->id?>&orderId=<?=$order->id?>"
				>
			</div>
		</td>
		<td class="rowPrice">
				<?=$good->getTotalPrice()?> <span class="rub">p</span>
		</td>
		<td>
				<a
					title="Удалить товар"
					class="pointer delete confirm deleteOrderGood"
					data-action="/admin/orderProcessing/deleteGood/?goodId=<?=$good->id?>&orderId=<?=$order->id?>"
					data-confirm="Удалить товар?"
				>
				</a>
		</td>
	</tr>
	<? endforeach; ?>
	<tr class="line">
		<td colspan="3" style="text-align: right; padding-right: 10px; font-weight: bold;">
			Итого:
		</td>
		<td>
			<?=$goods->getTotalQuantity()?>
		</td>
		<td class="totalPrice">
			<?=$goods->getTotalGoodsPrice()?> <span class="rub">p</span>
		</td>
		<td></td>
	</tr>
	<? if ( $this->isNotNoop($order->getPromoCode()) ): ?>
	<tr class="line">
		<td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;">
			Скидка по промокоду ( <span style="color: #999;"><?=$order->getPromoCode()->name?></span> <?=$order->getPromoCode()->discount?>%) :
		</td>
		<td class="totalPrice">
			<?=$order->getGoods()->getPromoCodeDiscountSum()?> <span class="rub">p</span>
		</td>
		<td>
			<a
				title="Удалить промо-код"
				class="pointer delete confirm deletePromoCode"
				data-action="/admin/orderProcessing/ajaxDeletePromo/?orderId=<?=$order->id?>"
				data-confirm="Удалить код?"
			>
			</a>
		</td>
	</tr>
	<? else: ?>
	<tr class="line">
		<td colspan="5" style="text-align: right; padding-right: 10px; font-weight: bold;">
			<div class="addPromo" data-action="/admin/orderProcessing/ajaxAddPromo/">
				Скидка по промокоду (<?=($order->getPromoCode()->discount)?$order->getPromoCode()->discount:0?>%) :
				<input type="hidden" name="orderId" value="<?=$order->id?>" />
				<input type="text" name="promoCode" />
				<a class="buttonInContent addPromoSubmit">Добавить</a>
			</div>
		</td>
		<td></td>
	</tr>
	<? endif; ?>
	<? if ( $this->isNotNoop($order->getDelivery()) ): ?>
	<tr class="line">
		<td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;" title="<?=$order->getDelivery()->getCategory()->name?> <?=$order->getDelivery()->getName()?>">
			Доставка:
		</td>
		<td class="totalPrice">
			<?=$order->deliveryPrice?> <span class="rub">p</span>
		</td>
		<td></td>
	</tr>
	<? endif; ?>
	<? if( $this->isNotNoop($order->getPromoCode()) || $this->isNotNoop($order->getDelivery()) ): ?>
	<tr class="line">
		<td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;">
			Итого:
		</td>
		<td class="totalPrice">
			<?=$order->getTotalPrice()?> <span class="rub">p</span>
		</td>
		<td></td>
	</tr>
	<? endif; ?>
</table>
<? endif; ?>
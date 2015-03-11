<table class="goodsList">
	<tr class="top">
		<td width="15">#</td>
		<td class="borderLeft">Товар</td>
		<td class="borderLeft">Цена</td>
		<td class="borderLeft">Количество</td>
		<td class="borderLeft">Стоимость</td>
	</tr>
	<? $count = 0; foreach($order->getGoods() as $good):?>
	<tr class="line" data-id="<?=$good->id?>">
		<td><?=++$count?></td>
		<td>
			<div class="normalView">
				<a href="<?=$good->getGood()->getPath()?>" target="blank">
					<?=$good->getGood()->getName()?>
				</a>
			</div>
		</td>
		<td>
			<?=$good->getPrice()?>
		</td>
		<td>
			<?=$good->getQuantity()?>
		</td>
		<td class="rowPrice">
				<?=$good->getTotalPrice()?> <span class="rub">p</span>
		</td>
	</tr>
	<? endforeach; ?>
	<tr class="line">
		<td colspan="3" style="text-align: right; padding-right: 10px; font-weight: bold;">
			Товары:
		</td>
		<td>
			<?=$order->getGoods()->getTotalQuantity()?>
		</td>
		<td class="totalPrice">
			<?=$order->getGoods()->getTotalGoodsPrice()?> <span class="rub">p</span>
		</td>
	</tr>
	<? if ( $this->isNotNoop($order->getPromoCode()) ): ?>
	<tr class="line">
		<td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;">
			Скидка по промокоду (<?=$order->getPromoCode()->discount?>% за <?=$order->getPromoCode()->name?>%) :
		</td>
		<td class="totalPrice">
			<?=$order->getGoods()->getPromoCodeDiscountSum()?> <span class="rub">p</span>
		</td>
	</tr>
	<? endif; ?>
	<tr class="line">
		<td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;">
			<?=$order->getDelivery()->getCategory()->name?> (<?=$order->getDelivery()->getName()?>)
		</td>
		<td class="totalPrice">
			<?=$order->deliveryPrice?> <span class="rub">p</span>
		</td>
	</tr>
	<tr class="line">
		<td colspan="4" style="text-align: right; padding-right: 10px; font-weight: bold;">
			Итого:
		</td>
		<td class="totalPrice">
			<?=$order->getTotalPrice()?> <span class="rub">p</span>
		</td>
	</tr>
</table>
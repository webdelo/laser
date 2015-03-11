<div class="goodsTable">
	<p class="title">Список товаров:</p>
<?if ($orderGoods->count()):?>
	<table class="goodsList">
		<tr class="top">
			<td>#</td>
			<td class="borderLeft" colspan="2">Товар</td>
			<td class="borderLeft">Цена / Базовая цена</td>
			<td class="borderLeft">Количество</td>
			<td class="borderLeft">Стоимость</td>
<?if($this->checkUserRight('orderGoods_delete')):?>
			<td class="borderLeft"><img src="/admin/images/bg/trash.png" alt="Удалить" /></td>
<?endif?>
		</tr>
<? $i=1; foreach($orderGoods as $good):?>
		<tr class="line" data-id="<?=$good->id?>">
			<td><?=$i?></td>
			<td>
				<div class="normalView">
					<a href="<?=$good->getGood()->getAdminUrl()?>/" target="blank">
						<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)
					</a>
					<br />
					<div class="comment"><?=$good->goodDescription?></div>
				</div>
				<div class="editView hide">
					<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)
					<br />
					<textarea name="goodDescriptionHidden"><?=$good->goodDescription?></textarea>
				</div>
			</td>
			<td class="noBorderLeft">
				<?if($this->checkUserRight('orderGoods_edit')):?>
				<div class="normalView">
					<a class="pointer editOrderGood" data-id="<?=$good->id?>" title="Изменить товар"></a>
				</div>
				<div class="editView hide">
					<a class="pointer editOrderGoodAction" data-id="<?=$good->id?>" title="Сохранить изменения"></a>
				</div>
				<?endif?>
			</td>
			<td>
				<div class="normalView">
					<?=$good->price?> / <font color="#999"><?=$good->basePrice?></font>
				</div>
				<div class="editView hide">
					<input name="editPrice" value="<?=$good->price?>"> / <input name="editBasePrice" value="<?=$good->basePrice?>">
				</div>
			</td>
			<td>
				<div class="normalView">
					<?=$good->quantity?>
				</div>
				<div class="editView hide">
					<input name="editQuantity" value="<?=$good->quantity?>">
				</div>
			</td>
			<td>
				<?=$good->price * $good->quantity?>
			</td>
<?if($this->checkUserRight('orderGoods_delete')):?>
			<td>
				<div><a class="pointer delete deleteOrderGood"  title="Удалить товар"></a></div>
			</td>
<?endif?>
		</tr>
<? $i++;  endforeach;?>
		<tr class="line">
			<td colspan="5" style="text-align: right; padding-right: 10px;">
				Сумма за товары (<?=$orderGoods->getTotalGoodsQuantity()?>):
			</td>
			<td>
				<?=$orderGoods->getTotalGoodsSum()?>
			</td>
<?if($this->checkUserRight('orderGoods_delete')):?>
			<td></td>
<? endif; ?>
		</tr>
		<?if ($order->promoCodeDiscount):?>
		<tr class="line">
			<td colspan="5" style="text-align: right; padding-right: 10px;">

				<?if ($this->isNotNoop($order->getPromoCode())):?>
					<font color="grey">(<?=$order->getPromoCode()->getName()?>, Код: <?=$order->getPromoCode()->getCode()?>)</font>
				<?endif;?>
				Промо-скидка (<?=$order->getPromoCodeDiscount()?>%):
			</td>
			<td>
				-<?=$orderGoods->getPromoCodeDiscountSum()?>
			</td>
<?if($this->checkUserRight('orderGoods_delete')):?>
			<td>
				<div><a class="pointer delete" id="deleteOrderPromoCode" title="Удалить промо-код"></a></div>
			</td>
<? endif; ?>
		</tr>
		<? endif;?>
		<? if ($order->deliveryId): ?>
		<tr class="line">
			<td colspan="5" style="text-align: right; padding-right: 10px;">
				Доставка: <?=$order->getDelivery()->getCategory()->name?>
				<?if ($this->isNotNoop($order->getDelivery())):?>
					<font color="grey">(<?=$order->getDelivery()->getName()?>)</font>
				<?endif;?>
				<input type="hidden" name="deliveryId" value="<?=$order->deliveryId?>" />
			</td>
			<td>
				<?if ($this->isNotNoop($order->getDelivery())):?>
					<?=$order->deliveryPrice?>
				<?endif;?>
			</td>
<?if($this->checkUserRight('orderGoods_delete')):?>
			<td>
				<div>
					<a
						class="pointer delete deleteOrderDelivery"
						title="Удалить доставку"
						data-action="/admin/orders/ajaxRemoveDeliveryFromOrder/<?=$order->id?>"
						data-method="post"
						data-confirm="Удалить доставку?"
					></a>
				</div>
			</td>
<? endif; ?>
		</tr>
		<? endif;?>
		<? if ($order->deliveryId || $order->promoCodeDiscount ): ?>
		<tr class="line">
			<td colspan="5" style="text-align: right; padding-right: 10px;">
				Общая сумма:
			</td>
			<td>
				<?=$order->getTotalSum()?>
			</td>
<?if($this->checkUserRight('orderGoods_delete')):?>
			<td></td>
<? endif; ?>
		</tr>
		<? endif;?>
	</table>
<?else:?>
	<i>К заказу не добавлено ни одного товара.</i>
<? endif;?>
<?if($this->checkUserRight('orderGoods_add')):?>
	<br /><br />
	<p class="title">Добавить товар:</p>
	<div class="addGoodBlock">
		<table width="100%">
			<tr>
				<td class="first">Товар:</td>
				<td>
					<input type="text" class="inputGoodId">
					<img class="inputGoodLoader" style="margin: 5px 0px -10px 140px; display: none;" src="/images/loaders/loading-small.gif" />
				</td>
			</tr>
			<tr>
				<td class="first">Количество:</td>
				<td><input type="text" name="quantity" class="addedGoodQuantity" value="" style="width: 80px;" /></td>
			</tr>
			<tr>
				<td class="first">Цена:</td>
				<td><input type="text" name="price" class="addedGoodPrice" value="" style="width: 80px;" /></td>
			</tr>
			<tr>
				<td class="first">Базовая цена:</td>
				<td><input type="text" name="basePrice" class="addedGoodBasePrice" value="" style="width: 80px;" /></td>
			</tr>
			<tr>
				<td class="first">Заметки:</td>
				<td><textarea style="width: 306px; height: 60px;" name="goodDescription"></textarea></td>
			</tr>
			<tr>
				<td class="first"></td>
				<td><a id="addGoodToOrder" class="add-bottom pointer" style="margin: 0px 0px 0px -20px; ">Добавить</a></td>
			</tr>
		</table>
	</div>
<?endif?>
</div>

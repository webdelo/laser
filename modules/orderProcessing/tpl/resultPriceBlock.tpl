<table>
	<tr>
		<td><h2>Стоимость <span><?=$order->getGoods()->getTotalQuantity()?></span> товаров: </h2></td>
		<td><h2><span><?=$order->getGoods()->getTotalPrice()?></span><span class="rub">p</span></h2></td>
	</tr>
	<tr>
		<td><h2>Стоимость доставки: </h2></td>
		<td><h2><span><?=$order->deliveryPrice?></span><span class="rub">p</span></h2></td>
	</tr>
	<tr>
		<td><h2>Общая стоимость заказа: </h2></td>
		<td><h2><span><?=$order->getTotalPrice()?></span><span class="rub">p</span></h2></td>
	</tr>
</table>
<div class="clear"></div>
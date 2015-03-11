<div class="deliveryAddressContent">
	<table width="350">
		<tr>
			<td>Цена:</td>
			<td>
				<input class="deliveryPrice" name="deliveryPrice" type="text" value="<?=$order->deliveryPrice?$order->deliveryPrice:$delivery->price?>" />
				<div style="color: #808080; padding: 0; margin: 0;">
					( Прибыль: <span class="deliveryIncome"><?=$delivery->price - $delivery->basePrice?></span> )
				</div>
			</td>
			<td style="padding-left: 5px; padding-right: 5px;">Себеcтоимость: </td>
			<td>
				<input class="deliveryBasePrice" name="deliveryBasePrice" type="text" value="<?=$order->deliveryBasePrice?$order->deliveryBasePrice:$delivery->basePrice?>" />
			</td>
		</tr>
	</table>
	<? if ( $delivery->flexibleAddress ): ?>
		<div class="addressBlock">
				<input type="hidden" name="flexibleAddress" value="<?=$delivery->flexibleAddress?>">
				<select name="country" style="width:100px;">
					<option value="Россия">Россия</option>
				</select>
			<input style="width: 230px;" type="text" name="region" value="<?=$order->region?>" placeholder="Область"/>
			<input style="width: 220px;" type="text" name="city" value="<?=$order->city?>" placeholder="Город"/>
		</div>
		<div class="addressBlock">
			<input type="text" style="width: 200px;" name="street" value="<?=$order->street?>" placeholder="Улица" />
			<input type="text" name="home" value="<?=$order->home?>" placeholder="Дом"/>
			<input type="text" name="block" value="<?=$order->block?>" placeholder="Корпус"/>
			<input type="text" name="flat" value="<?=$order->flat?>" placeholder="Квартира"/>
			<input type="text" name="index" value="<?=$order->index?>" placeholder="Индекс"/>
		</div>
	<? endif; ?>
</div>
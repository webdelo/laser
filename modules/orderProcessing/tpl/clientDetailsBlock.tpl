<script type="text/javascript">
$(function(){
	var order = new orderProcessing;
	order.editInput('.editClientDetails', function (response) {
		if (typeof response !== 'number')
			alert(response);
	}).actions.buyerTelephoneInit();
});
</script>
<table>
	<tr>
		<td colspan="3" style="padding-bottom: 0;">
			<h3>Контактные данные:</h3>
		</td>
	</tr>
	<tr>
		<td>
			<input 
				class="editClientDetails" 
				type="text" 
				name="company" 
				value="<?=$order->company?>" 
				placeholder="Организация"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
		<td>
			<input 
				class="editClientDetails" 
				type="text" 
				name="email" 
				data-alias="login" 
				value="<?=$order->email?>" 
				placeholder="E-mail"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
		<td></td>
	</tr>
	<tr>		
		<td>
			<input 
				class="editClientDetails teledit" 
				type="text" 
				name="phone" 
				value="<?=$order->phone?>" 
				placeholder="Телефон"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
		<td>
			<input 
				class="editClientDetails teledit" 
				type="text" 
				name="mobile" 
				value="<?=$order->mobile?>" 
				placeholder="Дополнительный телефон"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
		<td></td>
	</tr>
	<tr>
		<td colspan="3" style="padding-bottom: 0;">
			<h3>Котнактное лицо:</h3>
		</td>
	</tr>
	<tr>
		<td>
			<input 
				class="editClientDetails" 
				type="text" 
				name="surname" 
				value="<?=$order->surname?>" 
				placeholder="Фамилия"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
		<td>
			<input 
				class="editClientDetails" 
				type="text" 
				name="name" 
				value="<?=$order->name?>" 
				placeholder="Имя"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			>
		</td>
		<td>
			<input 
				class="editClientDetails" 
				type="text" 
				name="patronimic" 
				value="<?=$order->patronimic?>" 
				placeholder="Отчество"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-bottom: 0;">
			<? if( !$order->getDelivery()->flexibleAddress ): ?>
				<a class="showAddressBlock">Добавить почтовый адрес</a>
				<a class="hideAddressBlock hide">Скрыть почтовый адрес</a>	
			<? endif; ?>
		</td>
	</tr>
	<? $hide = ( $order->getDelivery()->flexibleAddress ) ? '' : ' hide' ?>
	<tr class="addressBlock<?=$hide?>">
		<td colspan="3" style="padding-bottom: 0;">
			<h3>Адрес доставки:</h3>
		</td>
	</tr>
	<tr class="addressBlock<?=$hide?>">
		<td>
			<script type="text/javascript">
				$(function(){
					var order = new orderProcessing;
					order.actions.countryChoose();
					order.actions.inputClick();
				});
			</script>
			<select name="country" data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>">
				<option value=""></option>
				<option value="Россия" <?=($order->country=='Россия')?'selected=""':''?>>Россия</option>
			</select>
		</td>
		<td>
			<input 
				class="editClientDetails" 
				data-clicktext="обл. " 
				type="text" 
				name="region" 
				value="<?=$order->region?>" 
				placeholder="Область"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
		<td>
			<input 
				class="editClientDetails" 
				data-clicktext="г. " 
				type="text" 
				name="city" 
				value="<?=$order->city?>" 
				placeholder="Город"
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
			/>
		</td>
	</tr>
	<tr class="addressBlock<?=$hide?>">
		<td colspan="3" style="padding: 0">
			<table>
				<tr>
					<td>
						<input 
							class="editClientDetails mini" 
							data-clicktext=""
							type="text" 
							name="index" 
							value="<?=$order->index?>" 
							placeholder="Индекс"
							data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
						/>
						<input 
							class="editClientDetails address" 
							data-clicktext="ул. " 
							type="text" 
							name="street" 
							value="<?=$order->street?>" 
							placeholder="Адрес"
							data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
						/>
					</td>
					<td>
						<input 
							class="editClientDetails mini" 
							data-clicktext="д. " 
							type="text" 
							name="home" 
							value="<?=$order->home?>" 
							placeholder="Дом"
							data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
						/>
						<input 
							class="editClientDetails mini" 
							data-clicktext="корп. " 
							type="text" 
							name="block" 
							value="<?=$order->block?>" 
							placeholder="Корпус"
							data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
						/>
						<input 
							class="editClientDetails mini" 
							data-clicktext="кв. " 
							type="text" 
							name="flat" 
							value="<?=$order->flat?>" 
							placeholder="Квартира"
							data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>"
						/>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<a 
	class="removeOrder confirm"
	data-confirm="Заказ нельзя будет продолжить, отменить заказ?"
	data-action="/admin/orderProcessing/ajaxDeleteOrder/<?=$order->id?>/"
>Удалить</a>
<a 
	class="getSummary" 
	data-action="/admin/orderProcessing/ajaxCheckoutOrder/?orderId=<?=$order->id?>"
>Оформить заказ
</a>

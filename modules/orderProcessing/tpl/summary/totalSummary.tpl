<script type="text/javascript">
	$(function(){
		var orderMenu = new rightMenu();
		orderMenu.actions.generateMenu.call(orderMenu);
	});
</script>
<h1 class="headerFirstLevel rightMenuRow" data-menu="Подтверждение">Подтверждение:</h1>
<table>
	<tr>
		<td style="padding-bottom: 0;">
			<h3>Товары:</h3>
		</td>
	</tr>
	<tr>
		<td>
			<? $this->getGoodsSummaryBlock() ?>
		</td>
	</tr>
	<? if ( $order->getDelivery()->flexibleAddress ): ?>
	<tr>
		<td style="padding-bottom: 0;">
			<h3>Доставка:</h3>
		</td>
	</tr>
	<tr>
		<td>
			<? $this->getDeliverySummaryBlock() ?>
		</td>
	</tr>
	<? endif; ?>
	<tr>
		<td style="padding-bottom: 0;">
			<h3>Контактная информация:</h3>
		</td>
	</tr>
	<tr>
		<td>
			<? $this->getContactsSummaryBlock() ?>
		</td>
	</tr>
</table>
<a 
	class="cancelConfirmation hide"
>Отменить</a>
<a 
	class="checkoutOrder"
	data-action="/admin/orderProcessing/ajaxSaveOrder/?orderId=<?=$order->id?>"
>Подтвердить</a>


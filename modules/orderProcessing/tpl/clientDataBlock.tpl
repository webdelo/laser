<script type="text/javascript">
	$(function(){
		var order = new orderProcessing;
		order.searchClient();
		var orderMenu = new rightMenu();
		orderMenu.actions.generateMenu.call(orderMenu);
	});
</script>
<table>
	<tr>
		<td class="first headerFirstLevel rightMenuRow" data-menu="Данные покупателя">Данные покупателя:</td>
		<td>
			<div class="clientSearchBlock<?=($order->clientId)?' hide':''?>">
				<input type="text" class="clientSearch" name="clientSearch" value="" placeholder="Постоянный клиент?">
			</div>
			<div class="clientFound<?=($order->clientId)?'':' hide'?>" data-source="/admin/orderProcessing/ajaxGetClientFound/?orderId=<?=$order->id?>">
				<? $this->getClientFound($order->id) ?>
			</div>
		</td>
	</tr>
</table>
<div class="clientDataDetails" data-source="/admin/orderProcessing/ajaxGetClientDetailsBlock/?orderId=<?=$order->id?>">
	<? $this->getClientDetailsBlock($order->id) ?>
</div>
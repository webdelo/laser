<script type="text/javascript">
	$(function(){
		var order = new orderProcessing;
		order.editInput('.editDeliveryPrice', function (response) {
				if (typeof response === 'number') {
					this.reloadResultPriceBlock();
				} else 
					alert(response);
		}).actions.deliveryDateInit().deliveryTimeInit();
		$('a[href=#delivery<?=$order->getDelivery()->getCategory()->id?>]').click();
	});
</script>
<table>
	<tr>
		<td>
			Стоимость: 
		</td>
		<td>
			<input 
				class="editDeliveryPrice" 
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>" 
				name="deliveryPrice" 
				value="<?=(!empty($order->deliveryPrice))?$order->deliveryPrice:$delivery->price?>"
			/>
		</td>
	</tr>
	<tr>
		<td>
			Дата доставки: 
		</td>
		<td>
			<input 
				class="editDeliveryPrice dateEdit" 
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>" 
				name="deliveryDate" 
				value="<?=$order->deliveryDate?>"
			/>
		</td>
	</tr>
	<tr>
		<td>
			Время доставки:
		</td>
		<td>
			<input 
				class="editDeliveryPrice timeEdit" 
				data-action="/admin/orderProcessing/editOrder/?orderId=<?=$order->id?>" 
				name="deliveryTime" 
				value="<?=$order->deliveryTime?>"
			/>
		</td>
	</tr>
</table>
<div>
	<?=$delivery->description?>
</div>


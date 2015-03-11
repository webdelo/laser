<script type="text/javascript">
	$(function(){
		var order = new orderProcessing;
		order.actions.initTabs();
		order.actions.deliveriesChoose();
		
		$('a[href=#delivery<?=$order->getDelivery()->getCategory()->id?>]').click();
		var orderMenu = new rightMenu();
		orderMenu.actions.generateMenu.call(orderMenu);
		
	});
</script>
<table>
	<tr>
		<td class="deliveryList">
			<select 
					name="deliveryId"			
					class="deliveries"
					data-action="/admin/orderProcessing/getDeliveryDataByDeliveryId/?orderId=<?=$order->id?>"
					data-method="post"
					data-type="html"
			>
				<option></option>
				<? foreach( $deliveries as $delivery ): ?>
					<option value="<?=$delivery->id?>" <?=($order->deliveryId==$delivery->id)?'selected':''?>><?=$delivery->getName()?></option>
				<? endforeach; ?>
			</select>
		</td>
		<td rowspan="2">
			<div class="deliveryDetailsBlock">
				<? if ( $categoryId == $order->getDelivery()->categoryId) $this->getDeliveryDataByOrderId($order->id) ?>
			</div>
		</td>
	</tr>
	<tr>
		<td class="deliveryList description">
			<?=($delivery->getCategory()->text)?$delivery->getCategory()->text:'Описание отсутствует'?>
		</td>
	</tr>
</table>


<script type="text/javascript" src="/modules/orders/js/deliveryToViewMode.js"></script>
<? if ( $order->deliveryId ): ?>
	<?=$order->getDeliveryAddressString()?> 
	<a style="text-decoration: none" target="_blank" href="http://maps.yandex.ru/?text=<?=$order->getDeliveryAddressString()?>&z=12">
		<img src="/admin/images/ico/mapMarker.png" height="25" alt="Посмотреть на карте" title="Посмотреть на карте">
	</a>
	<? if( $this->checkUserRight('order_edit') ): ?><a class="pen editDeliveryButton"></a><? endif; ?>
<? else: ?>
	<? if( $this->checkUserRight('order_edit') ): ?><a class="editDeliveryButton">Выбрать тип доставки</a><? endif; ?>
<? endif; ?>
<div id="goodsOrder" class="deliveryEditBlock hide">
<? $this->includeDeliveryAddingTemplate($order) ?>
</div>
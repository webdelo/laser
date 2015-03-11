<script type="text/javascript" src="/modules/orders/js/deliveryAddingInit.js"></script>
<div>
    <p class="title">Доставка:</p>
    <div class="deliveryFormAdd" data-action="/admin/orders/ajaxAddDeliveryToOrder/" data-method="post">
			<input type="hidden" name="orderId" value="<?=$order->id?>">
            <select 
                    name="deliveryCategoryId" 
                    style="width: 150px;"
                    class="deliveryCategories" 
					data-alias="deliveryId"
                    data-action="/admin/deliveries/getDeliveryByCategoryId/"
                    data-method="post"
            >
                    <option value="0">- Выбрать тип -</option>
                    <? foreach( $deliveryCategories as $category ): ?>
                    <option value="<?=$category->id?>" <?=$order->getDelivery()->getCategory()->id==$category->id?'selected':''?> data-next_step_name="- <?=$category->description?> -"><?=$category->name?></option>
                    <? endforeach; ?>
            </select>
            <select 
                    name="deliveryId"			
                    style="<?=$order->deliveryId?'':'display: none; '?>width: 190px;"
                    class="deliveries formEditExclude"
                    data-action="/admin/orders/getFormToDelivery/"
                    data-method="post"
                    data-type="html"
            >
                    <option val="0"><?=$order->deliveryId?'- '.$order->getDelivery()->getCategory()->description.' -':''?></option>
					<? if ( $this->isNotNoop($order->getDelivery()) ): ?>
						<? foreach( $this->getController('deliveries')->getDeliveryListByCategoryId($order->getDelivery()->getCategory()->id) as $delivery ):?>
							<option value="<?=$delivery->id?>" <?=$delivery->id==$order->deliveryId?'selected':''?>><?=$delivery->name?></option>
						<? endforeach; ?>
					<? endif; ?>
            </select>
            <div class="deliveryAddressBlock"><?=$order->deliveryId?$this->getFormByDeliveryId($order->deliveryId):''?></div>
            <input class="deliveryFormAddSubmit" type="button" disabled value="Добавить доставку" id=""/>
    </div>
</div>
<div>
	<p class="title">Промо-код:</p>
	<input class="addedGoodQuantity" id="promoCodeInput" type="text" style="width: 100px;" name="promoCode" value="" data-error-position="top"/>
	<input type="button" value="Добавить код" id="addPromoCode"/>
</div>
<br/><br/>
<? $this->includeDeliveryAddingTemplate($order) ?>
<br/><br/>
<?if(isset($order->id)):?>
<?$this->getController('OrderGoods')->getOrderGoodsTableByOrderId($order->id)?>
<?else:?>
Введите основные параметры заказа, чтобы потом иметь возможность добавлять товары.
<?endif?>
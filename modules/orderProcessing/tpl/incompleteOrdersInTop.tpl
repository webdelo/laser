<? if ( $this->checkUserRight('order_add') ): ?>
<script type="text/javascript" src="/modules/orderProcessing/js/orderProcessingHandler.js"></script>
<? if( $orders->count()>0 ): ?>
<div class="notifyOrder buttonNearProfile" title="Незавершенные оформление заказов">
	<a href="#"></a>
</div>
<? endif; ?>
<div class="startOrder buttonNearProfile" title="Новый заказ">
	<a href="/admin/orderProcessing/" target="_blank"></a>
</div>
<div class="incompleteOrders hide" style="opacity: 1 !important;" data-source="/admin/orderProcessing/ajaxGetIncompleteOrdersTable/"></div>
<? endif; ?>
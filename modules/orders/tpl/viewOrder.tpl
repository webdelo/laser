 <?include(TEMPLATES_ADMIN.'top.tpl');?>
	<link rel="stylesheet" type="text/css" href="/modules/orders/css/style.css">
	<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
	<script type="text/javascript" src="/modules/orders/js/orderView.js"></script>
	<script type="text/javascript" src="/modules/orders/js/orderMailHandler.js"></script>
	<script type="text/javascript" src="/modules/orders/js/orderMail.class.js"></script>
	<script type="text/javascript" src="/js/ajaxLoader.class.js"></script>
	<script type="text/javascript" src="/modules/orders/js/changeStatusId.js"></script>

	<div class="orderGoodDetails" data-source="/admin/orders/getOrderGoodDetails/"></div>

	<div class="main single">
		<div class="max_width">
			<div class="action_buts">
				<?if($order->id  &&  $order->domain == 'go-techno.ru'):?>
				<a class="pointer" href="/admin/orders/cash/<?=$order->id?>/" target="_blank"><img src="/admin/images/buttons/print_24.png" alt="" /> Товарный чек</a>
				<?endif?>

				<?if($this->checkUserRight('order_groupDriver')):?>
				<a class="pointer" href="/admin/orders/groupDriver/?ids=<?=$order->id?>" target="_blank"><img src="/admin/images/buttons/truck3.png" alt="" /> Сопроводительный лист</a>
				<?endif?>

				<?if($this->checkUserRight('order_partnerNotifySend')): ?>
					<a class="alertPartner pointer <?= $order->partnerNotified ? 'hide' : ''?>" data-action="/admin/orders/getTemplateToAlertPartner/<?=$order->id?>/"><img src="/admin/images/buttons/but_resend.png" alt="" /> Оповестить партнера</a>
					<a class="messagePartner pointer <?= !$order->partnerNotified ? 'hide' : ''?>" data-action="/admin/orders/getTemplateToMessagePartner/<?=$order->id?>/"><img src="/admin/images/buttons/but_resend.png" alt="" /> Написать партнеру</a>
				<?endif; ?>

				<? if ($order->id && $this->checkUserRight('order_delete')):?>
					<a class="button confirm pointer" data-confirm= "Remove the order?" data-action="/admin/orders/remove/<?=$order->id?>/"
						data-callback="postRemoveFromDetails" data-post-action="/admin/orders/orders/" >
						<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
					</a>
				<? endif;?>
				<a href="/admin/orders/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
			</div>
			<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
				<a href="/admin/orders/">Заказы</a>    <span>></span>
				<?= $order->id ? 'Номер '.$order->nr : 'Добавление'?>
			</p>
			<div class="clear"></div>

			<div class="viewMode">
				<?foreach ($tabs as $tab=>$tabName):?>
					<div id="<?=$tab?>">
						<?$this->includeTemplate($tab); ?>
					</div>
				<?endforeach?>
			</div>
		</div>
	</div><!--main-->
	<div class="vote"></div>
</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>

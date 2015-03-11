<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<link rel="stylesheet" type="text/css" href="/modules/orders/css/style.css">
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<script type="text/javascript" src="/modules/orders/js/orderMailHandler.js"></script>
		<script type="text/javascript" src="/modules/orders/js/orderMail.class.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/orders/order/<?=$order->id?>/" ><img src="/admin/images/buttons/but_find.png" alt="" /> Просмотр</a>
					<?if($order->id  &&  $order->domain == 'go-techno.ru'):?>
					<a class="pointer" href="/admin/orders/cash/<?=$order->id?>/" target="_blank"><img src="/admin/images/buttons/print_24.png" alt="" /> Товарный чек</a>
					<?endif?>
					<a class="alertPartner pointer" data-action="/admin/orders/getTemplateToAlertPartner/<?=$order->id?>/"><img src="/admin/images/buttons/but_resend.png" alt="" /> Оповестить партнера</a>
					<a class="messagePartner pointer" data-action="/admin/orders/getTemplateToMessagePartner/<?=$order->id?>/"><img src="/admin/images/buttons/but_resend.png" alt="" /> Написать партнеру</a>
					<a class="form<?= $order->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($order->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the order?" data-action="/admin/orders/remove/<?=$order->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/orders/orders/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/orders/order/<?=$order->id?>/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/orders/">Заказы</a>    <span>></span>
					<?= $order->id ? 'Номер '.$order->nr : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $order->id ? 'Edit' : 'Add'?>" action="/admin/orders/<?= $order->id ? 'edit' : 'add'?>/" method="post" data-post-action="<?= $order->id ? 'none' : '/admin/orders/orders/'?>">
					<div id="tabs">
						<div class="tab_page">
							<ul>
							<?foreach ($tabs as $tab=>$tabName):?>
								<li>
									<a href="#<?=$tab?>"><?=$tabName?></a>
								</li>
							<?endforeach?>
							</ul>
						</div>
						<?foreach ($tabs as $tab=>$tabName):?>
							<div id="<?=$tab?>">
								<?$this->includeTemplate($tab); ?>
							</div>
						<?endforeach?>
					</div>
				</form>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
    <?include(TEMPLATES_ADMIN.'footer.tpl');?>

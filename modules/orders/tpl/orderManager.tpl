<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<link rel="stylesheet" type="text/css" href="/modules/orders/css/style.css">
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<script type="text/javascript" src="/modules/orders/js/orderMailHandler.js"></script>
		<script type="text/javascript" src="/modules/orders/js/orderMail.class.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<?if($order->id  &&  $order->domain == 'go-techno.ru'):?>
					<a class="pointer" href="/admin/orders/cash/<?=$order->id?>/" target="_blank"><img src="/admin/images/buttons/print_24.png" alt="" /> Товарный чек</a>
					<?endif?>
					<a class="form<?= $order->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<a href="/admin/orders/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
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

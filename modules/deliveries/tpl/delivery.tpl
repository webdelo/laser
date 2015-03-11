<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $delivery->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($delivery->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the delivery?" data-action="/admin/deliveries/remove/<?=$delivery->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/deliveries/deliveries/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/deliveries/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/deliveries/">Доставки</a>    <span>></span>
					<?= $delivery->id ? $delivery->name : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $delivery->id ? 'Edit' : 'Add'?>" action="/admin/deliveries/<?= $delivery->id ? 'edit' : 'add'?>/" method="post" data-post-action="/admin/deliveries/deliveries/">
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

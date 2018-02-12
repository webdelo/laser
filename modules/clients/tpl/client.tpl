<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<script type="text/javascript" src="/modules/clients/js/clients.handler.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $object->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($object->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the client?" data-action="/admin/clients/remove/<?=$object->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/clients/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/clients/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a> <span>></span>
					<a href="/admin/clients/"> Клиенты</a>    <span>></span>
					<?= $object->id ? 'Редактирование' : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $object->id ? 'Edit' : 'Add'?>" action="/admin/clients/<?= $object->id ? 'edit' : 'add'?>/" method="post" data-post-action="/admin/clients/client/">
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
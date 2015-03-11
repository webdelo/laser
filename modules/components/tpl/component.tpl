<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $component->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($component->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the component?" data-action="/admin/components/remove/<?=$component->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/components/components/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/components/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/constructions/">Конструкции</a> / <a href="/admin/devices/">Девайсы</a>     <span>></span>
					<a href="/admin/components/">Комплектующие</a>    <span>></span>
					<?= $component->id ? $component->name : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $component->id ? 'Edit' : 'Add'?>" action="/admin/components/<?= $component->id ? 'edit' : 'add'?>/" method="post" data-post-action="<?= $component->id ? 'none' : '/admin/components/components/'?>">
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

<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $parameter->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($parameter->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the parameter?" data-action="/admin/parameters/remove/<?=$parameter->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/parameters/parameters/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/parameters/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/parameters/">Характеристики</a>    <span>></span>
					<?= $parameter->id ? $parameter->name : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $parameter->id ? 'Edit' : 'Add'?>" action="/admin/parameters/<?= $parameter->id ? 'edit' : 'add'?>/" method="post" data-post-action="<?= $parameter->id ? 'none' : '/admin/parameters/parameters/'?>">
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

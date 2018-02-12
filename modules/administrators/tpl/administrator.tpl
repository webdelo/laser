<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<link rel="stylesheet" type="text/css" href="/admin/js/jquery/tree/styles/jQuery.Tree.css" />
		<script type="text/javascript" src="/admin/js/jquery/tree/jQuery.Tree.js"></script>
		<script type="text/javascript" src="/modules/administrators/js/administrators.handler.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $administrator->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($administrator->id && $administrator->id !=1):?>
						<a class="button confirm pointer" data-confirm= "Remove the administrator?" data-action="/admin/administrators/remove/<?=$administrator->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/administrators/administrators/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/administrators/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/administrators/">Администраторы</a>    <span>></span>
					<?= $administrator->id ? $administrator->getLogin() : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $administrator->id ? 'Edit' : 'Add'?>" action="/admin/administrators/<?= $administrator->id ? 'edit' : 'add'?>/" method="post" data-post-action="/admin/administrators/administrators/">
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
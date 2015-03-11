<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<link rel="stylesheet" type="text/css" href="/admin/js/jquery/tree/styles/jQuery.Tree.css" />
		<script type="text/javascript" src="/admin/js/jquery/tree/jQuery.Tree.js"></script>
		<script type="text/javascript" src="/modules/administrators/js/administrators.handler.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $profile->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<a href="/admin/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<?= $profile->id ? $profile->getLogin() : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $profile->id ? 'Edit' : 'Add'?>" action="/admin/profile/edit/" method="post" data-post-action="none">
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
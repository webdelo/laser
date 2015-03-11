<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<link rel="stylesheet" type="text/css" href="/modules/catalog/complects/css/style.css">
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<script type="text/javascript" src="/modules/catalog/complects/js/complectMailHandler.js"></script>
		<script type="text/javascript" src="/modules/catalog/complects/js/complectMail.class.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $complect->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($complect->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the complect?" data-action="/admin/complects/remove/<?=$complect->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/complects/complects/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/complects/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/complects/">Комплекты</a>    <span>></span>
					<?= $complect->id ? $complect->getName() : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $complect->id ? 'Edit' : 'Add'?>" action="/admin/complects/<?= $complect->id ? 'edit' : 'add'?>/" method="post" data-post-action="<?= $complect->id ? 'none' : '/admin/complects/complects/'?>">
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

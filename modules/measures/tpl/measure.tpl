<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<script type="text/javascript" src="/modules/measures/js/measures.js"></script>
		<link rel="stylesheet" type="text/css" href="/modules/measures/css/measures.css">
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $measure->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($measure->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the measure?" data-action="/admin/measures/remove/<?=$measure->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/measures/measures/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/measures/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/measures/">Единицы измерения</a>    <span>></span>
					<?= $measure->id ? $measure->name : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $measure->id ? 'Edit' : 'Add'?>" action="/admin/measures/<?= $measure->id ? 'edit' : 'add'?>/" method="post" data-post-action="<?= $measure->id ? 'none' : '/admin/measures/measures/'?>">
					<?foreach ($tabs as $tab=>$tabName):?>
						<div id="<?=$tab?>">
							<?$this->includeTemplate($tab); ?>
						</div>
					<?endforeach?>
				</form>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
    <?include(TEMPLATES_ADMIN.'footer.tpl');?>

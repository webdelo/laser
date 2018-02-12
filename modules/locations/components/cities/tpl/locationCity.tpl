<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<script type="text/javascript" src="/modules/locations/components/cities/js/cities.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $locationCity->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($locationCity->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the locationCity?" data-action="/admin/locationCities/remove/<?=$locationCity->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/locationCities/locationCities/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?>
					<a href="/admin/locationCities/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/locationCities/">Города</a>    <span>></span>
					<?= $locationCity->id ? $locationCity->getName() : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $locationCity->id ? 'Edit' : 'Add'?>" action="/admin/locationCities/<?= $locationCity->id ? 'edit' : 'add'?>/" method="post" data-post-action="<?= $locationCity->id ? 'none' : '/admin/locationCities/locationCities/'?>">
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

<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $property->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<a href="/admin/properties/property/<?=$property->id?>/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/parameters/">Характеристики</a>    <span>></span>
					<?= $property->id ? $property->getName() : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $property->id ? 'Edit' : 'Add'?>" action="/admin/properties/actionTranslate/" method="post" data-post-action="<?= $property->id ? 'none' : '/admin/properties/property/'.$property->id.'/'?>">
					<h1><?=$property->getName()?></h1>
					<table class="parameterValuesTable">
						<tr class="header">
								<td>RU</td>
								<td>EN</td>
								<td>UA</td>
							</tr>
						<? foreach( $property->getPropertyValues() as $value ): ?>
						<tr>
							<? foreach(\core\i18n\LangFieldWrapper::getFieldTranslateInArray($value, 'getValue') as $lang=>$val ): ?>
							<td>
								<input type="text" name="values[<?=$value->id?>][value_<?=$lang?>]" value="<?=$val?>" />
								<input type="hidden" name="values[<?=$value->id?>][id]" value="<?=$value->id?>" />
							</td>
							<? endforeach; ?>
						</tr>
						<? endforeach;?>
					</table>
				</form>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>

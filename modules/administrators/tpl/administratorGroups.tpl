<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/administrators/group/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
				</div>
				<p class="speedbar">
					<a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/administrators/">Администраторы</a>     <span>></span>
					Группы
				</p>
				<div class="clear"></div>
				<div class="table_edit">
					<table  id="objects-tbl" data-sortUrlAction="/admin/administrators/changeCategoriesPriority/?" width="100%">
						<tr>
							<th class="first">id</th>
							<th>Алиас</th>
							<th>Имя</th>
							<th>Описание</th>
							<th class="last" colspan="2">Действия</th>
						</tr>
						<?foreach ($groups as $group):?>
							<tr>
								<td><?=$group->id?></td>
								<td><p class="alias"><a href="/admin/administrators/group/<?=$group->id?>"><?=$group->alias?></a></p></td>
								<td><p class="name"><?=$group->name?></p></td>
								<td><p class="description"><?=$group->description?></p></td>
								<td><a href="/admin/administrators/group/<?=$group->id?>" class="pen"></a></td>
								<? if ( $group->id != 1 ): ?>
								<td>
									<a class="del pointer button confirm" data-confirm="Remove the group?" data-action="/admin/administrators/removeGroup/<?=$group->id?>/" data-callback="postRemoveCategory"></a>
								</td>
								<? endif; ?>
							</tr>
						<?endforeach?>
					</table>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>
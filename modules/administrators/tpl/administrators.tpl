<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/administrators/administrator/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a href="/admin/administrators/groups/"><img src="/admin/images/buttons/folder.png" alt="" /> Группы</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Администраторы
				</p>
				<div class="clear"></div>
				<div class="table_edit">
					<?if(empty($administrators)): echo 'No Data'; else:?>
					<table  id="objects-tbl" data-sortUrlAction="/admin/administrators/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">id</th>
							<th>Логин</th>
							<th>Дата регистрации</th>
							<th>Статус</th>
							<th>Группа</th>
							<th class="last" colspan="4">Действия</th>
						</tr>
						<?foreach ($administrators as $administrator):?>
							<tr id="id<?=$administrator->id?>" class="dblclick" data-url="/admin/administrators/administrator/<?= $administrator->id?>" data-id="<?= $administrator->id?>" data-priority="<?= $administrator->priority?>">
								<td>
									<!--<input type="checkbox" />-->
								</td>
								<td><?=$administrator->id?></td>
								<td><p class="alias"><?=$administrator->getLogin()?></p></td>
								<td><p class="name"><?=$administrator->date?></p></td>
								<td><p class="status <?=$administrator->getStatus()->name?>"><?=$administrator->getStatus()->name?></p></td>
								<td>
									<p class="category_name"><?=$administrator->getGroup()->name?></p>
								</td>
								<td>
									<a href="/admin/administrators/administrator/<?=$administrator->id?>" class="pen"></a>
								</td>
								<? if ( $administrator->id != 1 ): ?>
								<td>
									<a class="del pointer button confirm" data-confirm="Remove the administrator?" data-action="/admin/administrators/remove/<?=$administrator->id?>" data-callback="postRemoveArticle"></a>
								</td>
								<? endif; ?>
							</tr>
						<?endforeach?>
					</table>
					<?endif?>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>
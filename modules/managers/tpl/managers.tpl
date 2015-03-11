<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/managers/manager/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a href="#"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span><a href="/admin/partners/"> Партнеры</a> <span>></span>
					Менеджеры
				</p>
				<div class="clear"></div>
				<div class="table_edit">
					<?if(empty($managers)): echo 'No Data'; else:?>
					<table width="100%">
						<tr>
							<th colspan="2" class="first">id</th>
							<th>Имя</th>
							<th>Статус</th>
							<th class="last" colspan="4">Приоритет</th>
						</tr>
						<?foreach ($managers as $manager):?>
							<tr>
								<td><input type="checkbox" /></td>
								<td><?=$manager->id?></td>
								<td><p class="name"><?=$manager->name?></p></td>
								<td><p class="status" style="color:<?=$manager->getStatus()->color?>"><?=$manager->getStatus()->name?></p></td>
								<td><?=$manager->priority?></td>
								<td><a href="#" class="arr"></a></td>
								<td><a href="/admin/managers/manager/<?=$manager->id?>/" class="pen"></a></td>
								<td><a data-confirm= "Remove the manager?" data-action="/admin/manager/remove/<?=$manager->id?>/"
								       data-callback="postRemoveFromDetails" data-post-action="/admin/managers/" class="del button confirm pointer"></a></td>
							</tr>
						<?endforeach?>
					</table>
					<?endif?>
				</div>

				<?//$this->printPager($pager, 'pager')?>

<!--				<div class="page_num">
					<span>1</span>
					<a href="#">2</a>
					<a href="#">3</a>
					<a href="#">4</a>
					<a href="#">5</a>
				</div>-->




				<div class="page_sort">
					Вывести по:
					<select>
						<option>10</option>
						<option>20</option>
						<option>50</option>
						<option>100</option>
					</select>
				</div>
				<div class="action_edit">
					<table>
						<tr>
							<td><a href="javascript:" class="check_all"><span>Выделить все</span></a></td>
							<td>
								<select>
									<option>С выделеными</option>
								</select>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>
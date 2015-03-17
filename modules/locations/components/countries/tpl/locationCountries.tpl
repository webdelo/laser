<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/locationCountries/locationCountry/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Страны
				</p>
				<div class="clear"></div>
				<!-- Start: Filetrs Block -->
				<div id="filter-form"  style="<?=(isset($_REQUEST['form_in_use'])?'display:block;':'display:none;')?>">
					<form id="search" action="" method="get">
						<input type="hidden" name="form_in_use" value="true" />
						<table>
							<tr>
								<td class="right">ID:</td>
								<td><input class="filterInput" type="text" name="id" value="<?=$this->getGET()['id']?>" /></td>
								<td class="right">Алиас:</td>
								<td><input class="filterInput" type="text" name="alias" value="<?=$this->getGET()['alias']?>" /></td>
								<td class="right">Название:</td>
								<td><input class="filterInput" type="text" name="name" value="<?=$this->getGET()['name']?>" /></td>
								<td>
									<div class="action_buts">
										<a class="pointer" onclick="$('#search').submit()"><img src="/admin/images/buttons/search.png" /> Поиск</a>
										<a class="resetFilters" href="/admin/<?=$_REQUEST['controller']?>/"><img src="/admin/images/buttons/delete.png" /> Сбросить фильтры</a>
									</div>
								</td>
							</tr>
							<tr>

							</tr>
							<tr>

							<tr>
						</table>
					</form>
				</div>
				<!-- End: Filters Block -->
				<div class="table_edit">
					<?if(empty($objects)): echo 'No Data'; else:?>
					<table  id="objects-tbl" data-sortUrlAction="/admin/locationCountries/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">id</th>
							<th>Алиас</th>
							<th>Название</th>
							<th class="last" colspan="4">Операции</th>
						</tr>
						<?foreach ($objects as $object):?>
							<tr id="id<?=$object->id?>" class="dblclick" data-url="/admin/locationCountries/locationCountry/<?= $object->id?>" data-id="<?= $object->id?>">
								<td><input type="checkbox" class="groupElements" /></td>
								<td><?=$object->id?></td>
								<td><p class="alias"><a href="/admin/locationCountries/locationCountry/<?=$object->id?>/"><?=$object->alias?></a></p></td>
								<td><p class="name"><?=$object->getName()?></p></td>
								<td><a href="/admin/locationCountries/locationCountry/<?=$object->id?>/" class="pen"></a></td>
								<td><a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/locationCountries/remove/<?=$object->id?>/" data-callback="postRemoveArticle"></a></td>
							</tr>
						<?endforeach?>
					</table>
					<?endif?>
				</div>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<form id="groupArray" action="/admin/locationCountries/groupActions/" class="groupArray" method="post" data-callback="reloadPage">
						<table>
							<tr>
								<td><a href="javascript:" class="check_all"><span>Выделить все</span></a></td>
								<td>
									<select class="groupActionSelect">
										<option value="">- С выделенными -</option>
										<option value="groupRemove">Удалить</option>
									</select>
								</td>
							</tr>
							<tr class="groupAction groupRemove">
								<td class="first"></td>
								<td>
									<button class="remove button confirm active" name="removeButton" data-confirm="Удалить объекты?" data-action="/admin/locationCountries/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>

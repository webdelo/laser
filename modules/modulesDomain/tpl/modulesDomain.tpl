<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/modulesDomain/moduleDomain/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
					<a href="/admin/modulesDomain/categories/"><img src="/admin/images/buttons/folder.png" alt="" /> Категории</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Модули-Домены
				</p>
				<div class="clear"></div>
				<!-- Start: Filetrs Block -->
				<div id="filter-form"  style="<?=(isset($_REQUEST['form_in_use'])?'display:block;':'display:none;')?>">
					<form id="search" action="" method="get">
						<input type="hidden" name="form_in_use" value="true" />
						<table>
							<tr>
								<td class="right">Категория:</td>
								<td>
									<select class="filterInput" name="categoryId">
										<option></option>
										<?php if ($modulesDomain->getMainCategories()->count() != 0): foreach($modulesDomain->getMainCategories() as $categoryObject):?>
										<option value="<?=$categoryObject->id?>" <?=($categoryObject->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>><?=$categoryObject->getName()?></option>
											<?php if ($categoryObject->getChildren()): foreach($categoryObject->getChildren() as $children):?>
											<option value="<?=$children->id?>" <?=($children->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->getName()?></option>
												<?php if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
												<option value="<?=$children2->id?>" <?=($children2->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->getName()?></option>
												<?php endforeach; endif;?>
											<?php endforeach; endif;?>
										<?php endforeach; endif;?>
									</select>
								</td>
								<td class="right">Статус:</td>
								<td><select class="filterInput" name="statusId">
										<option value="">&nbsp;</option>
										<?php foreach ($modulesDomain->getStatuses() as $status):?>
										<option value="<?=$status->id?>" <?=($this->getGET()['statusId']==$status->id)?'selected':''?>><?=$status->name?></option>
										<?php endforeach;?>
									</select>
								</td>
								<td class="right">ID:</td>
								<td><input class="filterInput" type="text" name="id" value="<?=$this->getGET()['id']?>" /></td>
								<td class="right">Алиас:</td>
								<td><input class="filterInput" type="text" name="alias" value="<?=$this->getGET()['alias']?>" /></td>
							</tr>
							<tr>
								<td class="right">Название:</td>
								<td><input class="filterInput" type="text" name="name" value="<?=$this->getGET()['name']?>" /></td>
								<td class="right">Описание:</td>
								<td><textarea style="width: 171px" name="description" cols="60"><?=$this->getGET()['description']?></textarea></td>
								<td class="right">Текст:</td>
								<td><textarea style="width: 171px" name="text" cols="60"><?=$this->getGET()['text']?></textarea></td>
							</tr>
							<tr>
								<td colspan="8">
									<div class="action_buts">
										<a class="pointer" onclick="$('#search').submit()"><img src="/admin/images/buttons/search.png" /> Поиск</a>
										<a class="resetFilters" href="/admin/<?=$_REQUEST['controller']?>/"><img src="/admin/images/buttons/delete.png" /> Сбросить фильтры</a>
									</div>
								</td>
							<tr>
						</table>
					</form>
				</div>
				<!-- End: Filters Block -->
				<div class="table_edit">
					<?if(empty($modulesDomain)): echo 'No Data'; else:?>
					<table  id="objects-tbl" data-sortUrlAction="/admin/modulesDomain/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">id</th>
							<th>Алиас</th>
							<th>Название</th>
							<th>Категория</th>
							<th>Дата</th>
							<th>Статус</th>
							<th class="last" colspan="4">Приоритет</th>
						</tr>
						<?foreach ($modulesDomain as $moduleDomain):?>
							<tr id="id<?=$moduleDomain->id?>" class="dblclick ui-selectee" data-url="/admin/modulesDomain/moduleDomain/<?=$moduleDomain->id?>" data-id="<?=$moduleDomain->id?>" data-priority="<?=$moduleDomain->priority?>">
								<td><input type="checkbox" /></td>
								<td><?=$moduleDomain->id?></td>
								<td><p class="alias"><a href="/admin/modulesDomain/moduleDomain/<?=$moduleDomain->id?>"><?=$moduleDomain->alias?></a></p></td>
								<td><p class="name"><?=$moduleDomain->name?></p></td>
								<td>
									<p class="category_edit"><a href="/admin/modulesDomain/category/<?=$moduleDomain->getCategory()->id?>"><img src="/admin/images/backgrounds/set.png" /></a></p>
									<p class="category_name"><?=$moduleDomain->getCategory()->getName()?></p></td>
								<td><p class="date"><?=$moduleDomain->date?></p></td>
								<td><p class="status on"><?=$moduleDomain->getStatus()->name?></p></td>
								<td class="td_bord sortHandle header"><?= $moduleDomain->priority?></td>
								<td><a href="/admin/modulesDomain/moduleDomain/<?=$moduleDomain->id?>" class="pen"></a></td>
								<td><a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/modulesDomain/remove/<?=$moduleDomain->id?>/" data-callback="postRemoveArticle"></a></td>
							</tr>
						<?endforeach?>
					</table>
					<?endif?>
				</div>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<table>
						<tr>
							<td><a href="javascript:" class="check_all"><span>Выделить все</span></a></td>
							<td>
								<select>
									<option>С выделенными</option>
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
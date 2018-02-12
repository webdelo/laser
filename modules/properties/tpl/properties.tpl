<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/properties/property/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
					<a href="/admin/properties/categories/"><img src="/admin/images/buttons/folder.png" alt="" /> Категории</a>
					<a href="/admin/realties/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar">
					<a href="/admin/">Главная</a>     <span>></span>
					Свойства
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
										<?php if ($objects->getMainCategories()->count() != 0): foreach($objects->getMainCategories() as $categoryObject):?>
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
								<td  class="right">Статус:</td>
								<td>
									<select class="filterInput" name="statusId">
										<option value="">&nbsp;</option>
										<?php foreach ($objects->getStatuses() as $status):?>
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
								<td colspan="7">
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
					<?if(empty($objects)): echo 'No Data'; else:?>
					<table  id="objects-tbl" data-sortUrlAction="/admin/properties/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">id</th>
							<th>Алиас</th>
							<th>Название</th>
							<th>Возможные значения</th>
							<th>Статус</th>
							<th>Категории</th>
							<th class="last" colspan="4">Приоритет</th>
						</tr>
						<?foreach ($objects as $object):?>
							<tr id="id<?=$object->id?>" class="dblclick" data-url="/admin/properties/property/<?= $object->id?>/" data-id="<?= $object->id?>" data-priority="<?= $object->priority?>">
								<td><input type="checkbox" class="groupElements" /></td>
								<td><?=$object->id?></td>
								<td><p class="alias"><a href="/admin/properties/property/<?=$object->id?>/"><?=$object->alias?></a></p></td>
								<td><p class="name"><?=$object->getName()?></p></td>
								<td>
									<? if ($object->getPropertyValues()->count()>0): $count=0; foreach( $object->getPropertyValues() as $value ): $count++?>
										<?=$value->getValue()?><?if ($object->getPropertyValues()->count()>$count):?>,<?endif;?>
									<? endforeach; else:?>
										<span style="color: gray;">значения отсутствуют</span>
									<? endif;?>
								</td>
								<td><p class="status"><font color="<?=$object->getStatus()->color?>"><?=$object->getStatus()->name?></font></p></td>
								<td>
									<?  if ($object->getAdditionalCategories()->count()>0): $count=0; foreach($object->getAdditionalCategories() as $category): $count++?>
										<?=$category->getName()?><?if ($object->getAdditionalCategories()->count()>$count):?>,<?endif;?>
									<? endforeach; else:?>
										<span style="color: gray;">категории не назначены</span>
									<? endif;?>
								</td>
								<td class="td_bord sortHandle header"><?= $object->priority?></td>
								<td><a href="/admin/properties/property/<?=$object->id?>/" class="pen"></a></td>
								<td><a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/properties/remove/<?=$object->id?>/" data-callback="postRemoveArticle"></a></td>
							</tr>
						<?endforeach?>
					</table>
					<?endif?>
				</div>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<form id="groupArray" action="/admin/properties/groupActions/" class="groupArray" method="post" data-callback="reloadPage">
						<table>
							<tr>
								<td><a href="javascript:" class="check_all"><span>Выделить все</span></a></td>
								<td>
									<select class="groupActionSelect">
										<option value="">- С выделенными -</option>
										<option value="statusId">Назначить статус</option>
										<option value="groupRemove">Удалить</option>
									</select>
								</td>
							</tr>
							<tr class="groupAction statusId">
								<td class="first"><strong></strong></td>
								<td>
									<select id="statusId" name="categoryId">
										<option value="">- Статусы -</option>
										<?php foreach ($objects->getStatuses() as $status):?>
										<option value="<?=$status->id?>" <?=($this->getGET()['statusId']==$status->id)?'selected':''?>><?=$status->name?></option>
										<?php endforeach;?>
									</select>
								<td>
									<button  class="ok groupArraySubmit" name="actionButton">
										<span>ок</span>
									</button>
								</td>
							</tr>
							<tr class="groupAction groupRemove">
								<td class="first"></td>
								<td>
									<button class="remove button confirm active" name="removeButton" data-confirm="Удалить объекты?" data-action="/admin/properties/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
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

<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<? if( $this->checkUserRight('parameters_controller') ): ?>
					<a href="/admin/parameters/"><img src="/admin/images/buttons/parameters.png" height="22" alt="" /> Характеристики</a>
					<? endif; ?>
					<a href="/admin/components/"><img src="/admin/images/buttons/accessories.png" height="22" alt="" /> Комплектующие</a>
					<a href="/admin/devices/device/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
					<a href="/admin/devices/categories/"><img src="/admin/images/buttons/folder.png" alt="" /> Категории</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>
					<span> > </span>
					Девайсы
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
										<option value="<?=$categoryObject->id?>" <?=($categoryObject->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
											<?php if ($categoryObject->getChildren()): foreach($categoryObject->getChildren() as $children):?>
											<option value="<?=$children->id?>" <?=($children->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
												<?php if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
												<option value="<?=$children2->id?>" <?=($children2->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
												<?php endforeach; endif;?>
											<?php endforeach; endif;?>
										<?php endforeach; endif;?>
									</select>
								</td>
								<td class="right">Статус:</td>
								<td><select class="filterInput" name="statusId">
										<option value="">&nbsp;</option>
										<?php foreach ($objects->getStatuses() as $status):?>
										<option value="<?=$status->id?>" <?=($this->getGET()['statusId']==$status->id)?'selected':''?>><?=$status->name?></option>
										<?php endforeach;?>
									</select>
								</td>
								<td class="right">ID:</td>
								<td><input class="filterInput" type="text" name="id" value="<?=$this->getGET()['id']?>" /></td>
								<td class="right">Код:</td>
								<td><input class="filterInput" type="text" name="code" value="<?=$this->getGET()['code']?>" /></td>
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
				<? if (!count($objects)): echo 'No Data'; else: ?>
				<div class="table_edit">
					<table  id="objects-tbl" data-sortUrlAction="/admin/devices/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">#</th>
                            <th>Фото</th>
							<th>id</th>
							<th>Имя</th>
							<th>Статус</th>
							<th>Наличие</th>
							<th class="last" colspan="4">Приоритет</th>
						</tr>
						<? $counter = 0; foreach ($objects as $object): ?>
							<tr id="id<?=$object->id?>" class="dblclick" data-url="/admin/devices/device/<?= $object->id?>" data-id="<?= $object->id?>" data-priority="<?= $object->priority?>">
								<td><input type="checkbox" class="groupElements" /></td>
								<td><?=++$counter;?></td>
                                                                <td>
									<a href="<?=$object->getFirstPrimaryImage()->getImage('800x600')?>" class="lightbox noNextPrev">
										<img src="<?=$object->getFirstPrimaryImage()->getImage('40x40')?>" />
									</a>
								</td>
								<td><?=$object->id?></td>
								<td><p class="name"><?=$object->getName()?></p></td>
								<td><p class="status on"><font color="<?=$object->getStatus()->color?>"><?=$object->getStatus()->name?></font></p></td>
								<td><p class="status on">
										<font color="<?=$object->getAvailabilityList()->getStatus()->color?>">
											<?= $object->getAvailabilityList()->getStatus()->name?>
											<?if ($object->getAvailabilityList()->getTotalQuantity()):?>
												(<?=$object->getAvailabilityList()->getTotalQuantity()?>)
											<?endif;?>
										</font>
									</p>
								</td>
								<td class="td_bord sortHandle header"><?= $object->priority?></td>
								<td><a href="/admin/devices/device/<?=$object->id?>/" class="pen"></a></td>
								<td><a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/devices/remove/<?=$object->id?>/" data-callback="postRemoveArticle"></a></td>
							</tr>
						<? endforeach; ?>
					</table>
				</div>
				<?endif?>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<form id="groupArray" action="/admin/devices/groupActions/" class="groupArray" method="post" data-callback="reloadPage" data-post-action="/admin/devices/">
						<table>
							<tr>
								<td><a href="javascript:" class="check_all"><span>Выделить все</span></a></td>
								<td>
									<select name="getAction" class="groupActionSelect" <?=($this->getGET()['groupAction'])?'style="display:block;"':''?>>
										<option value="">- С выделенными -</option>
										<option value="statusId">Назначить статус</option>
										<option value="categoryId">Назначить категорию</option>
										<option value="componentId">Назначить комплектующие</option>
										<option value="parametersId" <?=($this->getGET()['groupAction'])?'selected':''?>>Назначить характеристики</option>
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
									<button  class="ok groupArraySubmit">
										<span>ок</span>
									</button>
								</td>
							</tr>
							<tr class="groupAction componentId">
								<td class="first"><strong></strong></td>
								<td>
									<select id="componentId" name="componentId">
										<option value="">- Комплектующие -</option>
										<?php foreach ($components as $component):?>
										<option value="<?=$component->id?>" <?=($this->getGET()['componentId']==$component->id)?'selected':''?>><?=$component->name?></option>
										<?php endforeach;?>
									</select>
								<td>
									<button  class="ok groupArraySubmit">
										<span>ок</span>
									</button>
								</td>
							</tr>
							<tr class="groupAction categoryId">
								<td class="first"><strong></strong></td>
								<td>
									<select id="categoryId" name="categoryId">
										<option value="">- Категории -</option>
										<?php if ($objects->getMainCategories()->count() != 0): foreach($objects->getMainCategories() as $categoryObject):?>
										<option value="<?=$categoryObject->id?>" <?=($categoryObject->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
											<?php if ($categoryObject->getChildren()): foreach($categoryObject->getChildren() as $children):?>
											<option value="<?=$children->id?>" <?=($children->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
												<?php if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
												<option value="<?=$children2->id?>" <?=($children2->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
												<?php endforeach; endif;?>
											<?php endforeach; endif;?>
										<?php endforeach; endif;?>
									</select>
								</td>
								<td>
									<button  class="ok groupArraySubmit">
										<span>ок</span>
									</button>
								</td>
							</tr>
							<tr class="groupAction parametersId" <?=($this->getGET()['groupAction'])?'style="display:table-row;"':''?>>
								<td></td>
								<td colspan="2">
									<? $this->includeTemplate('parameters/devicesParametersGroup') ?>
								</td>
							</tr>
							<tr class="groupAction groupRemove">
								<td class="first"></td>
								<td>
									<button class="remove button confirm active" data-confirm="Удалить объекты?" data-action="/admin/devices/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
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
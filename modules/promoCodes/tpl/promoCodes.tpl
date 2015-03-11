<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/promoCodes/promoCode/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a href="/admin/promoCodes/categories/"><img src="/admin/images/buttons/folder.png" alt="" /> Категории</a>
				</div>
				<p class="speedbar">
					<a href="/admin/">Главная</a>
					<span>></span>
					Промо-коды
				</p>
				<div class="clear"></div>

				<?if(!$objects->count()): echo 'Нет данных'; else:?>
				<div class="table_edit">
					<table  id="objects-tbl" data-sortUrlAction="/admin/promoCodes/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">id</th>
							<th>Код</th>
							<th>Скидка</th>
							<th>Название</th>
							<th>Категория</th>
							<th>Дата</th>
							<th>Статус</th>
							<th class="last" colspan="2"></th>
						</tr>
						<?foreach ($objects as $object):?>
							<tr id="id<?=$object->id?>" class="dblclick" data-url="/admin/promoCodes/promoCode/<?= $object->id?>" data-id="<?= $object->id?>" data-priority="<?= $object->priority?>">
								<td><input type="checkbox" class="groupElements" /></td>
								<td><?=$object->id?></td>
								<td><p class="alias"><a href="/admin/promoCodes/promoCode/<?=$object->id?>/"><?=$object->code?></a></p></td>
								<td><p class="alias"><?=$object->discount?>%</p></td>
								<td><p class="name"><?=$object->name?></p></td>
								<td>
									<p class="category_edit"><a href="/admin/promoCodes/category/<?=$object->getCategory()->id?>/"><img src="/admin/images/backgrounds/set.png" /></a></p>
									<p class="category_name"><?=$object->getCategory()->name?></p></td>
								<td><p class="date"><?=$object->date?></p></td>
								<td><p class="status"><font color="<?=$object->getStatus()->color?>"><?=$object->getStatus()->name?></font></p></td>
								<td><a href="/admin/promoCodes/promoCode/<?=$object->id?>/" class="pen"></a></td>
								<td><a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/promoCodes/remove/<?=$object->id?>/" data-callback="postRemoveArticle"></a></td>
							</tr>
						<?endforeach?>
					</table>
				</div>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<form id="groupArray" action="/admin/promoCodes/groupActions/" class="groupArray" method="post" data-callback="reloadPage">
						<table>
							<tr>
								<td><a href="javascript:" class="check_all"><span>Выделить все</span></a></td>
								<td>
									<select class="groupActionSelect">
										<option value="">- С выделенными -</option>
										<option value="statusId">Назначить статус</option>
										<option value="categoryId">Назначить категорию</option>
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
									<button  class="ok groupArraySubmit" name="actionButton">
										<span>ок</span>
									</button>
								</td>
							</tr>
							<tr class="groupAction groupRemove">
								<td class="first"></td>
								<td>
									<button class="remove button confirm active" name="removeButton" data-confirm="Удалить объекты?" data-action="/admin/promoCodes/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
								</td>
							</tr>
						</table>
					</form>
				</div>
				<?endif?>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>
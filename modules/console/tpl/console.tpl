<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/modules/console/js/console.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<link rel="stylesheet" type="text/css" href="/modules/console/css/style.css">
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Консоль
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
								<td class="right">Статус:</td>
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
								<td class="right"></td>
								<td></td>
							</tr>
							<tr>
								<td class="right">Название:</td>
								<td><input class="filterInput" type="text" name="title" value="<?=$this->getGET()['title']?>" /></td>
								<td class="right">Описание:</td>
								<td><textarea style="width: 171px" name="description" cols="60"><?=$this->getGET()['description']?></textarea></td>
								<td class="right"></td>
								<td></td>
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
				<? if( $objects->count() == 0 ): echo 'Новых записей не обнаружено'; else: ?>
					<?foreach ($objects as $object):?>
						<div id="id<?=$object->id?>" class="itemBlock" data-priority="<?=$object->priority?>" title="ID: <?=$object->id?>">
							<div class="itemHeader">
								<span class="categoryName" <?=$object->isViewed()?'style="color: gray;"':''?>><?=$object->isViewed()?'Просмотрено':$object->getCategory()->name?></span>
								<span class="itemDate"><?=$object->date?></span>
							</div>
							<div class="itemContent">
								<p class="name"><?=$object->getTitle()?></p>
							</div>
							<div class="itemActions">
								<!--<a class="viewObject">Посмотреть</a>-->
								<div class="viewObjectDetails">
									<a class="toVievewed" target="_blank" href="<?=$object->getPath()?>justViewItem/"><?=$object->isViewed()?'Посмотреть еще':'Посмотреть'?></a>
									<a class="toArchive" target="_blank" href="<?=$object->getPath()?>viewItemAndHideNotice/">В архив</a>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					<? endforeach; ?>
				<? endif; ?>
				</div>

				<div class="clear"></div>
				<? if ( $objects->count() > 0 ) $this->printPager($pager, 'pager')?>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>

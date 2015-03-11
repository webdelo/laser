<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/offers/offer/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
					<a href="/admin/offers/categories/"><img src="/admin/images/buttons/folder.png" alt="" /> Категории</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Акции
				</p>
				<div class="clear"></div>
				<!-- Start: Filetrs Block -->
				<div id="filter-form"  style="<?=(isset($_REQUEST['form_in_use'])?'display:block;':'display:none;')?>">
					<form id="search" action="" method="get">
						<input type="hidden" name="form_in_use" value="true" />
						<table>
							<tr>
								<td class="right">Модуль:</td>
								<td>
									<select name="moduleId" style="width:150px;">
										<option></option>
										<?if ($this->getController('ModulesDomain')->getModules()->current()): foreach($this->getController('ModulesDomain')->getModules() as $module):?>
										<option value="<?=$module->id?>" <?= $this->getGET()['moduleId']==$module->id ? 'selected' : ''?>><?=$module->name?></option>
										<?endforeach; endif?>
									</select>
								</td>
								<td class="right">Домен:</td>
								<td>
									<select name="domain" style="width:150px;">
										<option></option>
										<?if ($this->getController('ModulesDomain')->getAllDomains()): foreach($this->getController('ModulesDomain')->getAllDomains() as $domain=>$value):?>
										<option value="<?=$domain?>" <?= $this->getGET()['domain']==$domain ? 'selected' : ''?>><?=$domain?></option>
										<?endforeach; endif?>
									</select>
								</td>
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
								<td>
									<script type="text/javascript">
										$('#multiStatus').live('click', function(){
											document.getElementById("multiStatus").multiple=true;
											document.getElementById("multiStatus").size=10;
											$('.shiftCtrl').show();
										})
									</script>
									<select id="multiStatus" class="filterInput" name="statusId"  <?=$this->getPOST()['statusIdArray'] ? ' multiple size="10" ' : ''?>>
										<option value="">&nbsp;</option>
										<?php foreach ($objects->getStatuses() as $status):?>
										<option value="<?=$status->id?>" <?=   $this->getPost()['statusIdArray']    ?    (in_array($status->id, $this->getPost()['statusIdArray']))?'selected':''     :      ''?>><?=$status->name?></option>
										<?php endforeach;?>
									</select>
									<div class="shiftCtrl" style="color: green; display: <?= $this->getPost()['statusIdArray'] ? 'block' : 'none'?>;">Shift / Ctrl	</div>
								</td>


							</tr>
							<tr>
								<td class="right">Тип:</td>
								<td>
									<select name="type" style="width:150px;">
										<option></value>
										<?if($objects->getTypes()):  foreach ($objects->getTypes() as $key=>$value):?>
										<option value="<?=$value?>" <?= $this->getGET()['type']==$value ? 'selected': ''?>><?=$key?></value>
										<?endforeach; endif;?>
									</select>
								</td>
								<td class="right">Название:</td>
								<td><input type="text" name="name" value="<?=$this->getGET()['name']?>" /></td>
								<td class="right">Дата от:</td>
								<td><input id="date_s" class="date" type="text" name="start_date" value="<?=$this->getGET()['start_date']?>" /></td>
								<td class="right">Дата до:</td>
								<td><input id="date_e" class="date" type="text" name="end_date" value="<?=$this->getGET()['end_date']?>" /></td>
							</tr>
							<tr>
								<td class="right">Описание:</td>
								<td><textarea style="width: 171px" name="description" cols="60"><?=$this->getGET()['description']?></textarea></td>
								<td class="right">Товар:</td>
								<td colspan="2">
									<script type="text/javascript" src="/modules/orders/js/autosuggest/jquery.autoSuggest.js"></script>
									<script type="text/javascript" src="/modules/catalog/offers/js/autosuggest/autosuggestOffer.js"></script>
									<script type="text/javascript" src="/modules/catalog/offers/js/autosuggest/autosuggestListPageOffer.js"></script>
									<link rel="stylesheet" type="text/css" href="/modules/orders/css/autoSuggest.css" />
									<input type="text" class="inputGood">
									<input type="hidden" class="goodId" value="<?=$this->getGet()['goodId'] ? $this->getGet()['goodId'] : ''?>">
									<input type="hidden" class="goodName" value="<?=$this->getGet()['goodId'] ? \modules\catalog\CatalogFactory::getInstance()->getGoodById($this->getGet()['goodId'])->getName() : ''?>">
								</td>
							</tr>
							<tr>
								<td colspan="8">
									<div class="action_buts">
										<a class="pointer" onclick="$('#search').submit()"><img src="/admin/images/buttons/search.png" /> Поиск</a>
										<a class="resetFilters" href="/admin/<?=$_REQUEST['controller']?>/"><img src="/admin/images/buttons/delete.png" /> Сбросить фильтры</a>
									</div>
								</td>
							</tr>
						</table>
					</form>
				</div>
				<!-- End: Filters Block -->
				<div class="table_edit">
					<?if(empty($objects)): echo 'No Data'; else:?>
					<table  id="objects-tbl" data-sortUrlAction="/admin/offers/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">#</th>
							<th>Название</th>
							<th>Домен</th>
							<th>Статус</th>
							<th class="last" colspan="4" >Операции</th>
						</tr>
						<? $i=1; foreach ($objects as $object):?>
							<tr id="id<?=$object->id?>" class="dblclick" data-url="/admin/offers/offer/<?= $object->id?>" data-id="<?= $object->id?>" data-priority="<?= $object->priority?>">
								<td><input type="checkbox" class="groupElements" /></td>
								<td><?=$i?></td>
								<td><p><?=$object->name?></p></td>
								<td><p><?=$object->domain?></p></td>
								<td><p class="status"><font color="<?=$object->getStatus()->color?>"><?=$object->getStatus()->name?></font></p></td>
								<td class="td_bord sortHandle header"><?= $object->priority?></td>
								<td><a href="/admin/offers/offer/<?=$object->id?>/" class="pen"></a></td>
								<td><a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/offers/remove/<?=$object->id?>/" data-callback="postRemoveArticle"></a></td>
							</tr>
						<? $i++; endforeach?>
					</table>
					<?endif?>
				</div>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<form id="groupArray" action="/admin/offers/groupActions/" class="groupArray" method="post" data-callback="reloadPage">
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
									<button class="remove button confirm active" name="removeButton" data-confirm="Удалить объекты?" data-action="/admin/offers/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
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

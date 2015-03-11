<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/complects/complect/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Комплекты
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
								<td class="right">Менеджер:</td>
								<td>
									<select class="filterInput" name="managerId">
										<option value="">&nbsp;</option>
										<?php foreach ($this->getActiveManagers() as $manager):?>
										<option value="<?=$manager->id?>" <?=($this->getGET()['managerId']==$manager->id)?'selected':''?>><?=$manager->name?> <?=$manager->getUserName()?></option>
										<?php endforeach;?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="right">Дата от:</td>
								<td><input id="date_s" class="date" type="text" name="start_date" value="<?=$this->getGET()['start_date']?>" /></td>
								<td class="right">Дата до:</td>
								<td><input id="date_e" class="date" type="text" name="end_date" value="<?=$this->getGET()['end_date']?>" /></td>
								<td class="right">Описание:</td>
								<td><textarea style="width: 171px" name="description" cols="60"><?=$this->getGET()['description']?></textarea></td>
								<td class="right">Название:</td>
								<td><input type="text" name="name" value="<?=$this->getGET()['name']?>"></td>
							</tr>
							<tr>
								<td class="right">Товар в комплекте:</td>
								<td colspan="3">
									<script type="text/javascript" src="/modules/catalog/complects/js/autosuggest/autosuggestListPage.js"></script>
									<script type="text/javascript" src="/modules/catalog/complects/js/autosuggest/jquery.autoSuggest.js"></script>
									<link rel="stylesheet" type="text/css" href="/modules/catalog/complects/css/autoSuggest.css" />
									<input type="text" class="inputGood">
									<input type="hidden" class="getGoodId" value="<?=$this->getGet()['goodId'] ? $this->getGet()['goodId'] : ''?>">
									<input type="hidden" class="getGoodName" value="<?=$this->getGet()['goodId'] ? $this->getController('Orders')->getOrderGoodById($this->getGet()['goodId'])->getName() : ''?>">
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
					<table  id="objects-tbl" data-sortUrlAction="/admin/complects/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">#</th>
							<th>Название</th>
							<th>Менеджер</th>
							<th>Товары</th>
							<th>Дата заказа</th>
							<th>Статус</th>
							<th class="last" colspan="4">Приоритет</th>
						</tr>
						<? $i=1; foreach ($objects as $object):?>
							<tr id="id<?=$object->id?>" class="dblclick" data-url="/admin/complects/complect/<?= $object->id?>" data-id="<?= $object->id?>" data-priority="<?= $object->priority?>">
								<td><input type="checkbox" class="groupElements" /></td>
								<td><?=$i?></td>
								<td><p><?=$object->getName()?></p></td>
								<td><p class="date"><?=$object->getComplectManager()->getLogin();?></p></td>
								<td>
									<p class="date">
										<?foreach($object->getComplectGoods() as $good):?>
										<?=$good->getGood()->getName()?> (<?=$good->quantity?> шт.)
										<br />
										<?  endforeach;?>
									</p>
								</td>
								<td><p class="date"><?=\core\utils\Dates::toDatetime($object->date)?></p></td>
								<td><p class="status"><font color="<?=$object->getStatus()->color?>"><?=$object->getStatus()->name?></font></p></td>
								<td class="td_bord sortHandle header"><?= $object->priority?></td>
								<td><a href="/admin/complects/complect/<?=$object->id?>/" class="pen"></a></td>
								<td><a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/complects/remove/<?=$object->id?>/" data-callback="postRemoveArticle"></a></td>
							</tr>
						<? $i++; endforeach?>
					</table>
					<?endif?>
				</div>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<form id="groupArray" action="/admin/complects/groupActions/" class="groupArray" method="post" data-callback="reloadPage">
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
									<button  class="ok groupArraySubmit"  name="actionButton">
										<span>ок</span>
									</button>
								</td>
							</tr>
							<tr class="groupAction groupRemove" name="removeButton">
								<td class="first"></td>
								<td>
									<button class="remove button confirm active" data-confirm="Удалить объекты?" data-action="/admin/complects/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
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

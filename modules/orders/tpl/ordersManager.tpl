<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<link rel="stylesheet" type="text/css" href="/modules/orders/css/style.css">
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<?if( ! $this->isAuthorisatedUserAnManager()):?>
					<a href="/admin/orders/order/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<?endif?>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
					<?if( ! $this->isAuthorisatedUserAnManager()):?>
					<a href="/admin/orders/categories/"><img src="/admin/images/buttons/folder.png" alt="" /> Категории</a>
					<?endif?>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Заказы
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
								<td class="right">Дата от:</td>
								<td><input id="date_s" class="date" type="text" name="start_date" value="<?=$this->getGET()['start_date']?>" /></td>
								<td class="right">Дата до:</td>
								<td><input id="date_e" class="date" type="text" name="end_date" value="<?=$this->getGET()['end_date']?>" /></td>
							</tr>
							<tr>
								<?if( ! $this->isAuthorisatedUserAnManager()):?>
								<td class="right">Партнер:</td>
								<td>
									<select class="filterInput" name="partnerId">
										<option value="">&nbsp;</option>
										<?php foreach ($this->getActivePartners() as $partner):?>
										<option value="<?=$partner->id?>" <?=($this->getGET()['partnerId']==$partner->id)?'selected':''?>><?=$partner->name?></option>
										<?php endforeach;?>
									</select>
								</td>
								<?endif?>
								<td class="right">Номер заказа:</td>
								<td><input class="filterInput" type="text" name="nr" value="<?=$this->getGET()['nr']?>" /></td>
								<td class="right">Номер счета:</td>
								<td><input class="filterInput" type="text" name="invoiceNr" value="<?=$this->getGET()['invoiceNr']?>" /></td>

							</tr>
							<tr>
								<td class="right">Номер плат. пор.:</td>
								<td><input class="filterInput" type="text" name="paymentOrderNr" value="<?=$this->getGET()['paymentOrderNr']?>" /></td>
								<td class="right">Партнер подтвердил последнее оповещение:</td>
								<td><input style="width: 20px" class="filterInput" type="checkbox" name="partnerConfirmed" value="1" <?= $this->getGET()['partnerConfirmed'] ? 'checked' : ''?> /></td>
								<td class="right">Город:</td>
								<td><input class="filterInput" type="text" name="city" value="<?=$this->getGET()['city']?>" /></td>
								<td class="right">Улица:</td>
								<td><input class="filterInput" type="text" name="street" value="<?=$this->getGET()['street']?>" /></td>

							</tr>
							<tr>
								<td class="right">Дом:</td>
								<td><input class="filterInput" type="text" name="home" value="<?=$this->getGET()['home']?>" /></td>
								<td class="right">Описание:</td>
								<td><textarea style="width: 171px" name="description" cols="60"><?=$this->getGET()['description']?></textarea></td>
							</tr>
							<tr>
								<td class="right">Клиент:</td>
								<td colspan="3">
									<script type="text/javascript" src="/modules/orders/js/autosuggest/autosuggestListPage.js"></script>
									<script type="text/javascript" src="/modules/orders/js/autosuggest/jquery.autoSuggest.js"></script>
									<link rel="stylesheet" type="text/css" href="/modules/orders/css/autoSuggest.css" />
									<input type="text" class="inputClient">
									<input type="hidden" class="getClientId" value="<?= $this->getGet()['clientId'] ? $this->getGet()['clientId'] : ''?>">
									<input type="hidden" class="getClientName" value="<?=   $this->getGet()['clientId']   ?   $this->getClientById($this->getGet()['clientId'])->getAllName().' - '.$this->getClientById($this->getGet()['clientId'])->city.' ('.$this->getClientById($this->getGet()['clientId'])->getLogin().')'   :   ''?>">
								</td>
								<td class="right">Товар:</td>
								<td colspan="3">
									<input type="text" class="inputGood">
									<input type="hidden" class="getGoodId" value="<?=$this->getGet()['goodId'] ? $this->getGet()['goodId'] : ''?>">
									<input type="hidden" class="getGoodName" value="<?=$this->getGet()['goodId'] ? $this->getOrderGoodById($this->getGet()['goodId'])->getName() : ''?>">
								</td>
							</tr>
							<tr>
								<td colspan="6">
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
					<table  id="objects-tbl" data-sortUrlAction="/admin/orders/changePriority/?" width="100%">
						<tr>
							<th colspan="2" class="first">#</th>
							<th class="client">Клиент <div class="additionalData">Компания</div></th>
							<th>Товары</th>
							<th>Дата <div class="additionalData">Доставка</div></th>
							<th>Оплата <div class="additionalData">Счет / Платежка</div></th>
							<th>Статус<div class="additionalData">Менеджер</div></th>
							<th class="last" colspan="4" >Операции</th>
						</tr>
						<? $i=1; foreach ($objects as $object):?>
							<tr id="id<?=$object->id?>" class="dblclick <?=($object->isPaid() && $object->isCompleted())?'completed':''?>" data-url="/admin/orders/order/<?= $object->id?>" data-id="<?= $object->id?>" data-priority="<?= $object->priority?>">
								<td><input type="checkbox" class="groupElements" /></td>
								<td><?=$i?></td>
								<td>
									<p><?=$object->getClient()->getAllName()?></p>
									<div class="additionalData">
										<?=$object->getClient()->company?>
										<?=$object->getClient()->phone?'тел. '.$object->getClient()->phone:''?>
										<?=$object->getClient()->mobile?'моб. '.$object->getClient()->mobile:''?>
									</div>
								</td>
								<td>
									<div class="additionalData">
										<ul>
										<? foreach($object->getOrderGoods() as $good):?>
										<li><?=$good->getGood()->getName()?> - <?=$good->quantity?>шт.</li>
										<? endforeach;?>
										</ul>
									</div>
								</td>
								<td>
									<p class="date"><?=\core\utils\Dates::toDatetime($object->date)?></p>
									<div class="additionalData">
										<? if ( !$object->deliveryDate ): ?>
											<p>Дата доставки не указана!</p>
										<? else: ?>
											<? if ( $object->getDaysToDelivery() > 5 ): ?>
												<p class="green"><?=$object->deliveryDate?></p>
											<? else: ?>
												<? if ( $object->getDaysToDelivery() > 0 ): ?>
													<p class="<?=($object->getDaysToDelivery()>3)?'blue':'red'?>"><?=\core\utils\Utils::declension($object->getDaysToDelivery(), array('Остался', 'Осталось', 'Осталось'))?> <?=$object->getDaysToDelivery()?> <?=\core\utils\Utils::declension($object->getDaysToDelivery(), array('день', 'дня', 'дней'))?></p>
												<? endif; ?>
												<? if ( $object->getDaysToDelivery() === 0 ): ?>
													<p class="red">Доставка сегодня!</p>
												<? elseif( $object->isDelivered() ): ?>
													<p class="green">Доставлен!</p>
												<? endif; ?>
												<? if ( $object->getDaysToDelivery() < 0 ): ?>
													<p class="red"><?=\core\utils\Utils::declension($object->getDaysToDelivery(), array('Просрочен', 'Просрочено', 'Просрочено'))?> <?=$object->getDaysToDelivery()*-1?> <?=\core\utils\Utils::declension($object->getDaysToDelivery()*-1, array('день', 'дня', 'дней'))?></p>
												<? endif; ?>
											<? endif; ?>
										<? endif; ?>
									</div>
								</td>
								<td class="paymentStatus" title="<?=$object->getPaymentStatus()->description?>">
									<p class="status"><font color="<?=$object->getPaymentStatus()->color?>"><?=$object->getPaymentStatus()->name?></font></p>
									<div class="additionalData">
										<? if ( $object->isPaid() && !$object->paidPercent ): ?>
											<p class="red">Ожидаются комиссионные!</p>
										<? else: ?>
											<? if ( $object->invoiceNr || $object->paymentOrderNr ): ?>
												<?= $object->invoiceNr ? $object->invoiceNr : ' - '?> / <?= $object->paymentOrderNr ? $object->paymentOrderNr : ' - '?>
											<? endif; ?>
										<? endif; ?>
									</div>
								</td>
								<td title="<?=$object->getStatus()->description?>">
									<p class="status"><font color="<?=$object->getStatus()->color?>"><?=$object->getStatus()->name?></font></p>
									<div class="additionalData"><?=$object->managerId?$object->getManager()->getLogin():'не назначен'?></div>
								</td>
								<td class="td_bord sortHandle header hide"><?= $object->priority?></td>
								<td>
									<a href="/admin/orders/order/<?=$object->id?>/" class="pen"></a>
								</td>
								<td>
									<? if( $this->checkUserRight('order_delete') ): ?>
									<a class="del pointer button confirm" data-confirm="Remove the item?" data-action="/admin/orders/remove/<?=$object->id?>/" data-callback="postRemoveArticle"></a>
									<? endif; ?>
								</td>
							</tr>
						<? $i++; endforeach?>
					</table>
					<?endif?>
				</div>

				<?$this->printPager($pager, 'pager')?>

				<div class="action_edit">
					<form id="groupArray" action="/admin/orders/groupActions/" class="groupArray" method="post" data-callback="reloadPage">
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
									<button  class="ok groupArraySubmit">
										<span>ок</span>
									</button>
								</td>
							</tr>
							<tr class="groupAction groupRemove">
								<td class="first"></td>
								<td>
									<button class="remove button confirm active" data-confirm="Удалить объекты?" data-action="/admin/orders/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
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

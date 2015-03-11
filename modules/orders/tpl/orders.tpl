<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<link rel="stylesheet" type="text/css" href="/modules/orders/css/style.css">
		<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/groupActions.js"></script>
		<script type="text/javascript" src="/modules/orders/js/paidPercent.js"></script>
		<script type="text/javascript" src="/modules/orders/js/groupProfit.js"></script>
		<script type="text/javascript" src="/modules/orders/js/groupDriver.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/orders/charts/"><img src="/admin/images/buttons/diagram.png" alt="" /> Диаграммы</a>
					<a href="/admin/orderProcessing/"><img src="/admin/images/buttons/add.png" alt="" /> Создать</a>
					<a class="filters pointer"><img src="/admin/images/buttons/search.png" alt="" /> Фильтрация</a>
					<a href="/admin/orders/categories/"><img src="/admin/images/buttons/folder.png" alt="" /> Категории</a>
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
								<td>
									Статус оплаты: </br>
									<select class="filterInput" name="paymentStatuses[]" multiple size="4">
										<option></option>
										<?php foreach ($objects->getPaymentStatuses() as $status):?>
										<option value="<?=$status->id?>" <?=$this->getGET()['paymentStatuses']?(in_array($status->id, $this->getGet()['paymentStatuses']))?'selected':'':''?>><?=$status->name?></option>
										<?php endforeach;?>
									</select>
									<div class="shiftCtrl" style="color: green;">Shift / Ctrl	</div>
								</td>
								<td colspan="3">
									<table>
										<tr>
											<td class="right">Модуль:</td>
											<td>
												<select class="filterInput" name="moduleId">
													<option></option>
													<?if ($this->getController('ModulesDomain')->getModules()->current()): foreach($this->getController('ModulesDomain')->getModules() as $module):?>
													<option value="<?=$module->id?>" <?= $this->getGET()['moduleId']==$module->id ? 'selected' : ''?>><?=$module->name?></option>
													<?endforeach; endif?>
												</select>
											</td>
											<td class="right">Домен:</td>
											<td>
												<select class="filterInput" name="domain">
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
										</tr>
										<tr>
											<td class="right">Менеджер:</td>
											<td>
												<select class="filterInput" name="managerId">
													<option value="">&nbsp;</option>
													<?php foreach ($this->getActiveManagers() as $manager):?>
													<option value="<?=$manager->id?>" <?=($this->getGET()['managerId']==$manager->id)?'selected':''?>><?=$manager->name?> <?=$manager->getUserName()?></option>
													<?php endforeach;?>
												</select>
											</td>
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
											<td class="right">Последнее оповещение:</td>
											<td><input style="width: 20px" class="filterInput" type="checkbox" name="partnerConfirmed" value="1" <?= $this->getGET()['partnerConfirmed'] ? 'checked' : ''?> /></td>
										</tr>
										<tr>
											<td class="right">Дата от:</td>
											<td><input id="date_s" class="date" type="text" name="start_date" value="<?=$this->getGET()['start_date']?>" /></td>
											<td class="right">Дата до:</td>
											<td><input id="date_e" class="date" type="text" name="end_date" value="<?=$this->getGET()['end_date']?>" /></td>
											<td class="right">Выплачен процент:</td>
											<td><input style="width: 20px" class="filterInput" type="checkbox" name="paidPercent" value="1" <?= $this->getGET()['paidPercent'] ? 'checked' : ''?> /></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									Статус: </br>
									<select class="filterInput" name="statuses[]" multiple size="4">
										<option></option>
										<?php foreach ($objects->getStatuses() as $status):?>
										<option value="<?=$status->id?>" <?=$this->getGET()['statuses']?(in_array($status->id, $this->getGet()['statuses']))?'selected':'':''?>><?=$status->name?></option>
										<?php endforeach;?>
									</select>
									<div class="shiftCtrl" style="color: green;">Shift / Ctrl	</div>
								</td>
								<td colspan="3">
									<table>
										<tr>
											<td class="right">Номер счета:</td>
											<td><input class="filterInput" type="text" name="invoiceNr" value="<?=$this->getGET()['invoiceNr']?>" /></td>
											<td class="right">Номер плат. пор.:</td>
											<td><input class="filterInput" type="text" name="paymentOrderNr" value="<?=$this->getGET()['paymentOrderNr']?>" /></td>
											<td class="right">Номер заказа:</td>
											<td><input class="filterInput" type="text" name="nr" value="<?=$this->getGET()['nr']?>" /></td>
										</tr>
										<tr>
											<td class="right">Город:</td>
											<td><input class="filterInput" type="text" name="city" value="<?=$this->getGET()['city']?>" /></td>
											<td class="right">Улица:</td>
											<td><input class="filterInput" type="text" name="street" value="<?=$this->getGET()['street']?>" /></td>
											<td class="right">Дом:</td>
											<td><input class="filterInput" type="text" name="home" value="<?=$this->getGET()['home']?>" /></td>
										</tr>
										<tr>
											<td class="right">Дата счета c:</td>
											<td><input id="date_invoice_start" class="date" type="text" name="invoiceNrDateStart" value="<?=$this->getGET()['invoiceNrDateStart']?>" /></td>
											<td class="right">Дата счета по:</td>
											<td><input id="date_invoice_end" class="date" type="text" name="invoiceNrDateEnd" value="<?=$this->getGET()['invoiceNrDateEnd']?>" /></td>
											<td class="right">Описание:</td>
											<td><textarea style="width: 250px" name="description" cols="60"><?=$this->getGET()['description']?></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td></td>
								<td colspan="3">
									<table>
										<tr>
											<td class="right">Дата пл. пор. с:</td>
											<td><input id="date_paymentOrder_start" class="date" type="text" name="paymentOrderNrDateStart" value="<?=$this->getGET()['paymentOrderNrDateStart']?>" /></td>
											<td class="right">Дата пл. пор. по:</td>
											<td><input id="date_paymentOrder_end" class="date" type="text" name="paymentOrderNrDateEnd" value="<?=$this->getGET()['paymentOrderNrDateEnd']?>" /></td>
											<td class="right"></td>
											<td style="width: 320px"></td>
										</tr>
									</table>
								<td>
							</tr>
							<tr>
								<td class="right">Клиент:</td>
								<td>
									<script type="text/javascript" src="/modules/orders/js/autosuggest/autosuggestListPage.js"></script>
									<script type="text/javascript" src="/modules/orders/js/autosuggest/jquery.autoSuggest.js"></script>
									<link rel="stylesheet" type="text/css" href="/modules/orders/css/autoSuggest.css" />
									<input type="text" class="inputClient">
									<input type="hidden" class="getClientId" value="<?= $this->getGet()['clientId'] ? $this->getGet()['clientId'] : ''?>">
									<input type="hidden" class="getClientName" value="<?=   $this->getGet()['clientId']   ?   $this->getClientById($this->getGet()['clientId'])->getAllName().' - '.$this->getClientById($this->getGet()['clientId'])->city.' ('.$this->getClientById($this->getGet()['clientId'])->getLogin().')'   :   ''?>">
								</td>
								<td class="right">Товар:</td>
								<td>
									<input type="text" class="inputGood">
									<input type="hidden" class="getGoodId" value="<?=$this->getGet()['goodId'] ? $this->getGet()['goodId'] : ''?>">
									<input type="hidden" class="getGoodName" value="<?=$this->getGet()['goodId'] ? $this->getOrderGoodById($this->getGet()['goodId'])->getName() : ''?>">
								</td>
							</tr>
							<tr>
								<td colspan="4">
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
							<th>Статус<div class="additionalData">Менеджер / Партнер</div></th>
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
								<td align="center">

									<? if ( $object->getDaysFromRegistration() == 0): ?>
										<p class="green">Сегодня</p>
									<? elseif( $object->getDaysFromRegistration() == 1 ): ?>
										<p class="green">Вчера</p>
									<? else: ?>
										<?=\core\utils\Dates::toDatetime($object->date)?>
									<? endif; ?>

									<div class="additionalData">
										<? if ( !$object->deliveryDate ): ?>
											<p>- - - -</p>
										<? else: ?>
											<? if( $object->isDelivered() ): ?>
												<p class="green">Доставлен!</p>
											<? else: ?>
												<? if ( $object->getDaysToDelivery() > 5 ): ?>
													<p class="green"><?=$object->deliveryDate?></p>
												<? else: ?>
													<? if ( $object->getDaysToDelivery() > 0 ): ?>
														<p class="<?=($object->getDaysToDelivery()>3)?'blue':'red'?>"><?=\core\utils\Utils::declension($object->getDaysToDelivery(), array('Остался', 'Осталось', 'Осталось'))?> <?=$object->getDaysToDelivery()?> <?=\core\utils\Utils::declension($object->getDaysToDelivery(), array('день', 'дня', 'дней'))?></p>
													<? endif; ?>
													<? if ( $object->getDaysToDelivery() == 0 ): ?>
														<p class="red">Доставка сегодня!</p>
													<? endif; ?>
													<? if ( $object->getDaysToDelivery() < 0 ): ?>
														<p class="red"><?=\core\utils\Utils::declension($object->getDaysToDelivery(), array('Просрочен', 'Просрочено', 'Просрочено'))?> <?=$object->getDaysToDelivery()*-1?> <?=\core\utils\Utils::declension($object->getDaysToDelivery()*-1, array('день', 'дня', 'дней'))?></p>
													<? endif; ?>
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
									<div class="additionalData"><?=$object->managerId?$object->getManager()->getLogin():'не назначен'?> / <?=$object->getPartner()->name ? $object->getPartner()->name : 'Партнер не выбран' ?></div>
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
										<option value="paidPercent">Выплаченный процент</option>
										<?if($this->checkUserRight('order_groupProfit')):?>
										<option value="groupProfit">Посчитать прибыль</option>
										<?endif?>
										<?if($this->checkUserRight('order_groupDriver')):?>
										<option value="groupDriver">Сопроводительный лист</option>
										<?endif?>
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
							<tr class="groupAction paidPercent hide">
								<td class="first"><strong></strong></td>
								<td>
									<select id="paidPercent" name="paidPercent">
										<option value="">- Выплаченный процент -</option>
										<option value="0">Не выплачен</option>
										<option value="1">Выплачен</option>
									</select>
								<td>
									<div class="psevdoButton paidPercentButton hide">ок</div>
								</td>
							</tr>
							<tr class="groupProfit">
								<td class="first"><strong></strong></td>
								<td>
									<div class="psevdoButton groupProfitButton hide">ок</div>
								</td>
							</tr>
							<tr class="groupDriver">
								<td class="first"><strong></strong></td>
								<td>
									<div class="psevdoButton groupDriverButton hide">ок</div>
								</td>
							</tr>
							<tr class="groupAction groupRemove">
								<td class="first"></td>
								<td>
									<button class="remove button confirm active" name="removeButton" data-confirm="Удалить объекты?" data-action="/admin/orders/groupRemove/" data-data="input[name*=group]" data-callback="reloadPage">ок</button>
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

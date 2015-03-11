<div class="main_edit">
	<script type="text/javascript" src="/modules/orders/js/orderHandler.js"></script>
	<script type="text/javascript" src="/modules/orders/js/order.class.js"></script>
	<script type="text/javascript" src="/modules/orders/js/autosuggest/autosuggest.js"></script>
	<script type="text/javascript" src="/modules/orders/js/autosuggest/jquery.autoSuggest.js"></script>
	<link rel="stylesheet" type="text/css" href="/modules/orders/css/autoSuggest.css" />

	<input type="hidden" class="objectId" value="<?=$order->id?>">
	<input type="hidden" class="isAuthorisatedUserAnManager" value="<?= $this->isAuthorisatedUserAnManager() ? 'true' : 'false'?>">
	<input type="hidden" class="partnerNotifyConfirm" value="<?=end($_REQUEST)?>">

	<div class="orderHeader">
		Заказ № <input
					type="text"
					class="transformer editOrderInputs hide"
					name="nr" value="<?=$order->nr?>"
					data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
				/>
		от <input
				type="text"
				class="dateEdit transformer editOrderInputs hide"
				name="date" value="<?=$order->date?>"
				data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
			/>

		<div class="modules">
			<div><img src="/modules/orders/images/icons/domain_<?=$order->domain?>.png"/></div>
		</div>
	</div>
			<table width="100%">
				<tr>
					<td>
						<table class="orderInfo">
							<tr>
								<td>
									<h2>
										<?=$order->getClient()->surname?>
										<?=$order->getClient()->name?>
										<?=$order->getClient()->patronimic?>
										<? if ( $this->checkUserRight('client_edit') ): ?>
											<a href="/admin/clients/client/<?=$order->clientId?>/" target="_blank"><img src="/admin/images/buttons/info.png" height="12"></a>
										<? endif; ?>
									</h2>
									<p class="additionalData">
										Контакты:
										тел. <strong><input
											type="text"
											class="transformer contactsEdit editOrderInputs hide teledit"
											name="phone" value="<?=$order->getClient()->phone?>"
											data-action="/admin/clients/editClientById/"
											data-post="&id=<?=$order->clientId?>"
										/></strong>,
										моб. <strong><input
											type="text"
											class="transformer contactsEdit editOrderInputs hide teledit"
											name="mobile" value="<?=$order->getClient()->mobile?>"
											data-action="/admin/clients/editClientById/"
											data-post="&id=<?=$order->clientId?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать номер':'не указан'?>"
											/></strong>
										<br />
										E-mail:
										<?if($order->getClient()->haveTestMail()):?>
										-
										<?else:?>
										<input
											type="text"
											class="transformer contactsEdit editLogin hide"
											name="login" value="<?=$order->getClient()->getLogin()?>"
											data-action="/admin/clients/editLoginById/"
											data-post="&id=<?=$order->clientId?>"
										/>
										<?endif?>
									</p>
									<? if( $order->getClient()->company ): ?>
									<h2>
										<input
											type="text"
											class="transformer editOrderInputs hide"
											name="company" value="<?=$order->getClient()->company?>"
											data-action="/admin/clients/editClientById/"
											data-post="&id=<?=$order->clientId?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'Указать название компании':'название не указано'?>"
										/>
									</h2>
									<p class="additionalData">
										ИНН:
										<input
											type="text"
											class="transformer companyContacts editOrderInputs hide"
											name="inn" value="<?=$order->getClient()->inn?>"
											data-action="/admin/clients/editClientById/"
											data-post="&id=<?=$order->clientId?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать ИНН':'не указан'?>"
										/>
										КПП:
										<input
											type="text"
											class="transformer companyContacts editOrderInputs hide"
											name="kpp" value="<?=$order->getClient()->kpp?>"
											data-action="/admin/clients/editClientById/"
											data-post="&id=<?=$order->clientId?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать КПП':'не указан'?>"
										/>
										ОГРН:
										<input
											type="text"
											class="transformer companyContacts editOrderInputs hide"
											name="ogrn" value="<?=$order->getClient()->ogrn?>"
											data-action="/admin/clients/editClientById/"
											data-post="&id=<?=$order->clientId?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать ОГРН':'не указан'?>"
										/>
									</p>
									<? endif; ?>

									<div class="h2">
										<div class="deliveryContainer" data-source="/admin/orders/ajaxGetDeliveryTemplate/<?=$order->id?>/">
											<?=$this->getDeliveryTemplate($order->id)?>
										</div>
									</div>
									<div class="additionalData">
										<span>Доставить</span>
										<strong>
										<input
											type="text"
											class="transformer dateEdit companyContacts editOrderInputs hide"
											name="deliveryDate" value="<?=$order->deliveryDate?>"
											data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать дату':'дата не указана'?>"
										/>
										</strong>
										<strong>
										<input
											type="text"
											class="transformer timeEdit companyContacts editOrderInputs hide"
											name="deliveryTime" value="<?=$order->deliveryTime?>"
											data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать время':'время не указано'?>"
										/>
										</strong>
										<br/>
										Контактное лицо:
										<strong>
										<input
											type="text"
											class="transformer companyContacts editOrderInputs hide"
											name="person" value="<?=$order->person?>"
											data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать получателя':'не указан'?>"
										/>
										</strong>
										<? if($order->phone): ?>
										тел.
										<? endif; ?>
										<strong>
										<input
											type="text"
											class="transformer contactsEdit editOrderInputs hide teledit"
											name="phone" value="<?=$order->phone?>"
											data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
											data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать номер телефона':'не указан'?>"
										/>
										</strong>
									</div>

									<h2>Оплаты</h2>
									<div class="additionalData">
										Статус:
										<span class="payData">
											<select
												class="editOrderSelects partnerChoose transformer hide"
												data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												name="paymentStatusId"
											>
												<?if ($orders->getPaymentStatuses()): foreach($orders->getPaymentStatuses() as $paymentStatus):?>
												<option value="<?=$paymentStatus->id?>" <?= $paymentStatus->id==$order->paymentStatusId ? 'selected' : ''?>><?=$paymentStatus->name?></option>
												<?endforeach; endif?>
											</select>
										</span>
										<br />
										Способ оплаты:
										<span class="payData">
											<select
												class="editOrderSelects partnerChoose transformer hide"
												data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												name="cashPayment"
											>
												<option value="0" <?=$order->cashPayment==0 ? 'selected' : ''?>>Western Union</option>
												<option value="1" <?=$order->cashPayment==1 ? 'selected' : ''?>>Банковский перевод</option>
											</select>
										</span>
										<br />
										Счёт №
										<span class="payData">
											 <input
												type="text"
												class="transformer companyContacts editOrderInputs hide"
												name="invoiceNr" value="<?=$order->invoiceNr?>"
												data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать номер':'номер не указан'?>"
											/>
										</span> от <span class="payData">
											<input
												type="text"
												class="transformer companyContacts dateEdit editOrderInputs hide"
												name="invoiceNrDate" value="<?=$order->invoiceNrDate?>"
												data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать дату':'дата не указана'?>"
											/>
										</span>
										<br /> Платежное поручение №
										<span class="payData">
											<input
												type="text"
												class="transformer companyContacts editOrderInputs hide"
												name="paymentOrderNr" value="<?=$order->paymentOrderNr?>"
												data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать номер':'номер не указан'?>"
											/>
										</span> от <span class="payData">
											 <input
												type="text"
												class="transformer companyContacts dateEdit editOrderInputs hide"
												name="paymentOrderNrDate" value="<?=$order->paymentOrderNrDate?>"
												data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать дату':'дата не указана'?>"
											/>
										</span>
									</div>
								</td>
								<td class="additionalInfo">
									<table class="orderDetails">
										<tr>
											<td class="title">Статус:</td>
											<td>
												<link rel="stylesheet" type="text/css" href="/modules/orders/css/changeStatusId.css">
												<img class="statusIdLoader hide"  src="/images/loaders/loader.gif">
												<select
													name="statusId"
													class="<?= $order->getClient()->haveTestMail() ? 'editOrderSelects' : 'editOrderSelectStatusId'?> transformer hide"
													data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												>
													<?if ($orders->getStatuses()): foreach($orders->getStatuses() as $status):?>
													<option value="<?=$status->id?>" <?= $status->id==$order->statusId ? 'selected' : ''?>><?=$status->name?></option>
													<?endforeach; endif?>
												</select>
											</td>
										</tr>

										<tr class="herePartnerChangeStatusBlock">
										<?=$this->getPartnerChangeStatusBlock($order)?>
										</tr>

										<tr>
											<td class="title">Менеджер:</td>
											<td>
												<select
													name="managerId"
													class="editOrderSelects transformer hide"
													data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
													data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'назначить':'не назначен'?>"
												>
													<option></option>
													<?if ($activeManagers): foreach($activeManagers as $manager):?>
													<option value="<?=$manager->id?>" <?= $manager->id==$order->managerId ? 'selected' : ''?>><?=$manager->getAllName()?></option>
													<?endforeach; endif?>
												</select>
											</td>
										</tr>
										<tr>
											<td class="title">Трекинг код:</td>
											<td>
												<input
												type="text"
												class="transformer companyContacts editOrderInputs hide"
												name="tracking" value="<?=$order->tracking?>"
												data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'указать номер':'номер не указан'?>"
												/>
											</td>
										</tr>
									</table>
									<div class="orderPartner">
										<h2>
											<select
												name="partnerId"
												class="changePartner partnerChoose transformer hide"
												data-action="/admin/orders/changePartner/<?=$order->id?>/"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'выбрать':'не назначен'?>"
											>
												<option></option>
												<?if ($activePartners): foreach($activePartners as $partner):?>
												<option value="<?=$partner->id?>" <?= $partner->id==$order->partnerId ? 'selected' : ''?>><?=$partner->name?></option>
												<?endforeach; endif?>
											</select>
											<span class="title">[партнер]</span>
										</h2>
										<ul class="additionalData">
											<li>
												Процент обналички <input
																		type="text"
																		class="transformer changeCashRate hide"
																		name="cashRate" value="<?=$order->cashRate?>"
																		data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
																		data-default="0"
																	/>%
											</li>
											<li class="partnerConfirmedLi <?= $order->partnerConfirmed ? '' : 'orderNotConfirmedLi'?>">
												Заказ
												<select
													class="editOrderSelects transformer hide"
													data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
													name="partnerConfirmed"
												>
													<option value="0" <?=( $order->partnerConfirmed )?'':'selected=true'?>> не принял</option>
													<option value="1" <?=( $order->partnerConfirmed )?'selected=true':''?>> принял</option>
												</select>

												<div class="managerConfirmOrder"  style="display:<?= $this->isAuthorisatedUserAnManager() && !$order->partnerConfirmed ? 'inline-block' : 'none'?>; margin-left: -15px;">
													<span>
														<div class="action_buts">
															<a>Подтвердить заказ</a>
														</div>
													</span>
												</div>
											</li>
											<li>
												Процент
												<select
													class="editOrderSelects transformer hide"
													data-action="/admin/orders/editPaidPercent/?orderId=<?=$order->id?>"
													name="paidPercent"
												>
													<option value="1" <?=( $order->paidPercent )?'selected=true':''?>> выплачен</option>
													<option value="0" <?=( $order->paidPercent )?'':'selected=true'?>> не выплачен</option>
												</select>
												<div id="editPaidPercentDate" style='display:<?=($order->paidPercent) ?'inline': 'none'?>'>
												<strong><input
																		type="text"
																		class="transformer dateEdit companyContacts editOrderInputs hide"
																		name="paidPercentDate" value="<?=$order->paidPercentDate?>"
																		data-action="/admin/orders/editOrder/?orderId=<?=$order->id?>"
																		data-default="указать дату выплаты"
																		/></strong></div>
											</li>
										</ul>
										<div class="action_buts" style="float: left; margin: -20px 0px 10px 10px;">
											<a class="showOrderHistory <?= empty($order->partnerNotifyHistory) ? 'hide' : ''?>" data-action="/admin/orders/ajaxGetOrderHistory/<?=$order->id?>/">
												История сообщений партнеру
											</a>
										</div>
										<div class="clear"></div>
										<div class="additionalDataBlockNoMove">
										<h2>Заметки:</h2>
										<div class="editDescription">
											<textarea
												class="editOrderInputs transformerTextarea hide"
												name="description"
												data-action="/admin/orders/editDescription/?orderId=<?=$order->id?>"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'Кликни чтобы написать заметки по заказу...':'отсутствуют'?>"
											><?=$order->description?></textarea>
										</div>
										<h2>Примечания для водителя:</h2>
										<div class="editDescription">
											<textarea
												class="editOrderInputs transformerTextarea hide"
												name="driverNotice"
												data-action="/admin/orders/editDriverNotice/?orderId=<?=$order->id?>"
												data-default="<?=$this->checkUserRight('order_edit_from_view_mode')?'Кликни чтобы написать заметки по заказу...':'отсутствуют'?>"
											><?=$order->driverNotice?></textarea>
										</div>
										</div>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<h2>
							Список товаров: <? if($this->checkUserRight('orderGoods_add')): ?><a class="newOrderGood buttonInContent">Добавить</a><? endif; ?>
						</h2>
						<div class="newOrderGoodForm hide">
							<p class="title">Добавить товар:</p>
							<div class="addGoodBlock" data-action="/admin/orderGoods/ajaxAddOrderGood/">
								<input type="hidden" name="orderId" value="<?=$order->id?>">
								<table width="100%">
									<tr>
										<td class="first">Товар:</td>
										<td>
											<input type="text" class="inputGoodId">
											<img class="inputGoodLoader" style="margin: 5px 0px -10px 140px; display: none;" src="/images/loaders/loading-small.gif" />
										</td>
									</tr>
									<tr>
										<td class="first">Количество:</td>
										<td><input type="text" name="quantity" class="addedGoodQuantity" value="" style="width: 80px;" /></td>
									</tr>
									<tr>
										<td class="first">Цена:</td>
										<td><input type="text" name="price" class="addedGoodPrice" value="" style="width: 80px;" /></td>
									</tr>
									<tr>
										<td class="first">Базовая цена:</td>
										<td><input type="text" name="basePrice" class="addedGoodBasePrice" value="" style="width: 80px;" /></td>
									</tr>
									<tr>
										<td class="first">Заметки:</td>
										<td><textarea style="width: 306px; height: 60px;" name="goodDescription"></textarea></td>
									</tr>
									<tr>
										<td class="first"></td>
										<td><a id="addGoodToOrder" class="add-bottom pointer addGoodBlockSubmit" style="margin: 0px 0px 0px -20px; ">Добавить</a></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="goodsTableList" data-source="/admin/orders/ajaxGetOrderGoodsTable/?orderId=<?=$order->id?>">
							<? $this->getOrderGoodsTable($order->id) ?>
						</div>
					</td>
				</tr>
			</table>
	<div class="clear"></div>
</div><!--main_edit-->
<?$headerTitle = 'Изменение статуса заказа №'.$order->nr?>
<? include ('header.tpl'); ?>
	<table style="font-size: 12px;">
		<tr>
			<td>
				<p>Mенеджер <b><?=$this->getAuthorizatedUser()->getAllName()?></b> изменил статус заказа №<?=$order->nr?>:</p>
				<table style="font-size: 12px;">
					<tr>
						<td colspan="2">
							<span style="background-color: #E8DFCE;
										color: #999;
										float: left;
										font-size: 12px;
										font-weight: bold;
										padding: 13px 42px;
										text-transform: uppercase;"
							>
								Новый
							</span>
							<span style="background: url(<?=$this->setImageHere(DIR.'/modules/orders/images/modal.png')?>) 10px 10px no-repeat;
										display: block;
										float: left;
										font-weight: bold;
										height: 35px;
										margin: 0 30px 15px 35px;
										padding: 20px 0 0 16px;
										text-transform: uppercase;
										width: 36px;"
							>
								на
							</span>
							<span style="background-color: #005E96;
										color: white;
										float: left;
										font-size: 12px;
										font-weight: bold;
										padding: 13px 42px;
										text-transform: uppercase;"
							>
								Согласован
							</span>
						</td>
					</tr>
					<tr>
						<td style="padding: 0">
							<?if(!empty($statusData['partnersMessage'])):?>
							<h4 style="padding: 0; margin: 0; color: #222; font-weight: bold; font-size: 12px;">
								Примечания для партнера:
							</h4>
							<?endif?>
						</td>
						<td style="padding: 0">
							<?if(!empty($statusData['clientsMessage'])):?>
							<h4 style="padding: 0; margin: 0; color: #999; font-weight: bold; font-size: 12px;">
								Примечания отправленные клиенту:
							</h4>
							<?endif?>
						</td>
					</tr>
					<tr>
						<td style="font-size: 11px;">
							<?if(!empty($statusData['partnersMessage'])):?>
							<?=str_replace(array("\r\n", "\n"), '<br/>', $statusData['partnersMessage'])?>
							<br/>
							<?endif?>
						</td>
						<td style="font-size: 11px; color: #999">
							<?if(!empty($statusData['clientsMessage'])):?>
							<?=str_replace(array("\r\n", "\n"), '<br/>', $statusData['clientsMessage'])?>
							<br/>
							<?endif?>
						</td>
					</tr>
					<tr>
					<a href="<?=DIR_HTTP?>admin/orders/order/<?=$order->id?>/" style="padding: 5px 10px; border: 1px solid #00a336; background: #17a845; color: #ffffff; text-decoration: none; font-size: 13px; width: 300px; display: block; text-align: center;">
						Посмотреть заказ</a>
					</tr>
				</table>

				<table style="margin-top: 10px; font-size: 12px;">
					<tr>
						<? if( $order->getClient()->company ): ?>
						<td style="padding: 0; margin: 0; padding-top: 10px; padding-right: 10px; font-size:12px; font-weight: bold;" valign="top">
							Заказчик:
						</td>
						<td style="padding: 0; margin: 0; padding-top: 10px;" valign="top">
							<table style="font-size: 12px;">
								<tr>
									<td style="margin: 0; padding: 0; font-size: 12px; color: #333;" valign="top">
										Компания: <b><?=$order->getClient()->company?></b><br />
										<p style="color: #999; font-size: 11px; padding: 0; margin: 0;">
											<? if($order->getClient()->inn): ?>ИНН: <b><?=$order->getClient()->inn?></b>,<? endif; ?>
											<? if($order->getClient()->kpp): ?>КПП: <b><?=$order->getClient()->kpp?></b>,<? endif; ?>
											<? if($order->getClient()->ogrn): ?>ОГРН: <b><?=$order->getClient()->ogrn?></b><? endif; ?>
										</p>
									</td>
								</tr>
							</table>
						</td>
						<? endif; ?>
					</tr>
					<tr>
						<td style="padding: 0; margin: 0; padding-top: 10px; padding-right: 10px; font-size:12px; font-weight: bold;" valign="top">
							Контактное лицо:
						</td>
						<td style="padding: 0; margin: 0; padding-top: 10px;" valign="top">
							<table style="font-size: 12px;">
								<tr>
									<td style="padding: 0; margin: 0; color: #333; font-size: 12px;">
										<?=$order->getClient()->surname?>
										<?=$order->getClient()->name?>
										<?=$order->getClient()->patronimic?>
									</td>
								</tr>
								<tr>
									<td style="padding: 0; margin: 0">
										<p style="color: #000; font-size: 11px; padding: 0; margin: 0;">
											Контакты:
											тел. <strong><?=$order->getClient()->phone?></strong>,
											моб. <strong><?=$order->getClient()->mobile?$order->getClient()->mobile:'не указан'?></strong>
											<br />
											<p style="color: #999; font-size: 11px; padding: 0; margin: 0;">
											E-mail: <?= $order->getClient()->haveTestMail() ? '-' : $order->getClient()->getLogin()?>
											</p>
										</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<? if($order->deliveryId): ?>
						<td style="padding: 0; margin: 0; padding-top: 10px; padding-right: 10px; font-size:12px; font-weight: bold;" valign="top">
							Доставить:
						</td>
						<td style="padding: 0; margin: 0; padding-top: 10px;" valign="top">
							<?=$order->getDeliveryAddressString()?><br />
							<p style="color: #999; font-size: 11px; padding: 0; margin: 0;">
								Дата: <strong style="color: #000000;"><?=$order->deliveryDate?></strong>
								Время: <strong style="color: #5b5b5b;"><?=$order->deliveryTime?$order->deliveryTime:'не указано'?></strong>
							</p>
						</td>

						<? endif; ?>
					</tr>
					<tr>
						<? if($order->deliveryId): ?>
						<td style="padding: 0; margin: 0; padding-top: 10px; padding-right: 10px; font-size:12px; font-weight: bold;" valign="top">
							Получатель:
						</td>
						<td style="padding: 0; margin: 0; padding-top: 10px;  padding-right: 10px;" valign="top">
							<?=$order->person?><br>
							<p style="color: #000000; font-size: 11px; padding: 0; margin: 0;">
								Контакты:
								моб. <strong><?=$order->phone?></strong>
							</p>
						</td>

						<? endif; ?>
					</tr>
					<tr>
						<? if($order->deliveryId): ?>
						<td style="padding: 0; margin: 0; padding-top: 10px; padding-right: 10px; font-size:12px; font-weight: bold;" valign="top">
							Оплата:
						</td>
						<td style="padding: 0; margin: 0; padding-top: 10px;  padding-right: 10px;" valign="top">
							<?=$order->cashPayment?'Наличными':'Безналичный расчет'?><br>
							<p style="color: #999; font-size: 11px; padding: 0; margin: 0;">
								Cтатус: <strong><?=$order->getPaymentStatus()->name?></strong>
							</p>
						</td>

						<? endif; ?>
					</tr>
				</table>

				<table style="margin-top: 10px; font-size: 12px;">
					<tr>
						<td style="padding: 0; margin: 0;">
							<h4 style="padding: 0; margin: 0; color: #222; font-weight: bold; font-size: 12px;">Товары:</h4>
						</td>
					</tr>
					<tr>
						<td style="padding: 0; margin: 0; padding-top: 10px;">
							<table width="800" style="text-align:center; font-size: 12px;">
								<tr style="font-weight: bold;background-color: #DAE2FE;">
									<td>&nbsp;#&nbsp;</td>
									<td colspan="2">Товар</td>
									<td>Цена / Базовая цена</td>
									<td>Количество</td>
									<td>Стоимость</td>
									<td>Себестоимость</td>
									<td>Откат</td>
								</tr>
								<? $i=1; foreach($order->getOrderGoods() as $good):?>
									<? include $good->getGood()->getConfig()->orderGoodAdminTemplate.'.tpl'; ?>
								<? $i++; endforeach?>
								<?if ($this->isNotNoop($order->getPromoCode())):?>
								<tr>
									<td colspan="5" style="text-align: right;">Скидка по промо коду (<?=$order->getPromoCodeDiscount()?> %):</td>
									<td>- <?=$order->getOrderGoods()->getPromoCodeDiscountSum()?></td>
								</tr>
								<?endif?>
								<?if ($this->isNotNoop($order->getDelivery())):?>
								<tr>
									<td colspan="3" style="text-align: right;">
										Доставка -
										<?=$order->getDelivery()->getCategory()->name?>
										<?if ($this->isNotNoop($order->getDelivery())):?>
											(<?=$order->getDelivery()->getName()?>)
										<?endif;?>
										:
									</td>
									<td>
										<?if( $order->getDelivery()->id == (new \modules\deliveries\lib\DeliveryConfig)->getFloatPriceDeliveryId() ):?>
											договорная
										<?elseif ($this->isNotNoop($order->getDelivery())):?>
											<?=$order->deliveryPrice?> / <?=$order->deliveryBasePrice?>
										<?endif;?>
									</td>
									<td></td>
									<td>
										<?if( $order->getDelivery()->id == (new \modules\deliveries\lib\DeliveryConfig)->getFloatPriceDeliveryId() ):?>
											договорная
										<?elseif ($this->isNotNoop($order->getDelivery())):?>
											<?=$order->deliveryPrice?>
										<?endif;?>
									</td>
								</tr>
								<?endif?>
								<tr>
									<td colspan="4" style="text-align: right;"><strong>Итого:<strong></td>
									<td><?=$order->getOrderGoods()->getTotalGoodsQuantity()?></td>
									<td>
										<?if($order->getOrderGoods()->isZeroPriceGoods()):?>
										<strong>договорная</strong>
										<?else:?>
										<strong><?=$order->getTotalSum()?></strong>
										<?endif?>
										<?if( $order->getDelivery()->id == 223 ): ?>
										<br />+ стоимость доставки
										<?endif?>
									</td>
									<td>
										<strong>
										<?=\core\utils\Prices::standartPrice($order->getTotalBaseSum())?>
										</strong>
									</td>
									<td>
										<strong>
										<?=\core\utils\Prices::standartPrice($order->getIncome())?>
										<? if($order->getCashRatePrice()): ?>( -<?=$order->getCashRatePrice()?> )<? endif; ?>
										</strong>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<br/>
			</td>
		</tr>
	</table>
<? include ('footerAdmin_'.$order->domain.'.tpl'); ?>
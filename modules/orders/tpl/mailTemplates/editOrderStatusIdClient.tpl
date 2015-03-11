<?$headerTitle = 'Уважаемый клиент, у вашего заказа №'.$order->nr.' был изменен статус'?>
<? include ('headerClient.tpl'); ?>
	<table style="font-size: 12px;">
		<tr>
			<td>
				<p>Статус заказа был изменен:</p>
				<table style="font-size: 12px;">

					<?=$order->getNewStatusPhrase()?>

					<tr>
						<td style="padding: 0">
							<?if(!empty($statusData['clientsMessage'])):?>
							<h4 style="padding: 0; margin: 0; color: #222; font-weight: bold; font-size: 12px;">
								Примечания:
							</h4>
							<?endif?>
						</td>
					</tr>
					<tr>
					<a href="http://<?=$order->domain?>/cabinet/current/<?=$order->nr?>/" style="padding: 5px 10px; border: 1px solid #00a336; background: #17a845; color: #ffffff; text-decoration: none; font-size: 13px; width: 300px; display: block; text-align: center;">
							Посмотреть заказ
						</a>
					</tr>
					<tr>
						<td style="font-size: 11px; color: #999">
							<?if(!empty($statusData['clientsMessage'])):?>
							<?=str_replace(array("\r\n", "\n"), '<br/>', $statusData['clientsMessage'])?>
							<br/>
							<?endif?>
						</td>
					</tr>
				</table>

				<table style="margin-top: 10px; font-size: 12px;">
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
									<td>Цена</td>
									<td>Количество</td>
									<td>Стоимость</td>
								</tr>
								<? $i=1; foreach($order->getOrderGoods() as $good):?>
									<? include $good->getGood()->getConfig()->orderGoodClientTemplate.'.tpl'; ?>
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
											<?=$order->deliveryPrice?>
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
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<br/>
			</td>
		</tr>
	</table>
<? include ('footerClient_'.$order->domain.'.tpl'); ?>
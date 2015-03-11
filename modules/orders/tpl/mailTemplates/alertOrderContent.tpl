<table border="0" cellpadding="0" cellspacing="0" style="font-size: 12px;">
	<tr>
		<td style="padding-left: 10px;">
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
							<? if($order->deliveryDate): ?>
							Дата: <strong style="color: #000000;"><?=$order->deliveryDate?></strong>
							<? endif; ?>
							<? if($order->deliveryTime): ?>
							Время: <strong style="color: #5b5b5b;"><?=$order->deliveryTime?></strong>
							<? endif; ?>
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
		</td>
	</tr>
	<tr>
		<td style="padding-left: 10px;" colspan="3">
			<?if($order->getOrderGoods()->count()):?>

			<table width="800" style="text-align:center; font-size: 12px;">
				<tr style="font-weight: bold;background-color: #f2f5ff;">
					<td>&nbsp;№&nbsp;</td>
					<td colspan="2">Товар</td>
					<td>Цена / Себестоимость<br />(за шт.)</td>
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
				<? if( $order->deliveryId ): ?>
				<tr>
					<td colspan="3" style="text-align: right;">
						Доставка -
						<?=$order->getDelivery()->getCategory()->name?>
						:
					</td>
					<td>
						<?=$order->deliveryPrice?> / <?=$order->deliveryBasePrice?>
					</td>
					<td></td>
					<td>
						<?=$order->deliveryPrice?>
					</td>
				</tr>
				<? endif; ?>
				<tr>
					<td colspan="4" style="text-align: right;"><strong>Итого:<strong></td>
					<td><?=$order->getOrderGoods()->getTotalGoodsQuantity()?></td>
					<td>
						<?if($order->getOrderGoods()->isZeroPriceGoods()):?>
						<strong>договорная</strong>
						<?else:?>
						<strong><?=$order->getTotalSum()?><strong>
						<?endif?>
					</td>
					<td>
						<strong><?=\core\utils\Prices::standartPrice($order->getOrderGoods()->getTotalGoodsBaseSum())?><strong>
					</td>
					<td>
						<strong><?=\core\utils\Prices::standartPrice($order->getOrderGoods()->getIncome())?><strong>
					</td>
				</tr>
			</table>

			<?endif?>

			<?if($order->getFilesByCategory(1)->count()):?>
			<br />
			Прикрепленные файлы:
			<br />
			<? $i=1; if($order->getFilesCategories()) foreach($order->getFilesCategories() as $item): ?>
				<?foreach ( $order->getFilesByCategory($item->id) as $file ): ?>
				<?=$i; $i++?>. <?=$file->getTitle()?> (<?=$file->name?>)<br />
				<?endforeach?>
			<?endforeach?>
			<?endif?>

			<input class="time" type="hidden" value="<?=$time?>" />
		</td>
	</tr>
</table>
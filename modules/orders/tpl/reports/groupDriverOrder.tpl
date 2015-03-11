<div class="orderPage">

				<div class="header">
					<a href="/admin/" class="logo"><img src="/admin/images/logo/logo.png" alt=""></a>
					<h2>Сопроводительный лист для заказа №: <?=$object->nr?></h2>
					<span class="headerOrderData">
						Дата: <?=$object->date?><br/>
						Менеджер: <?=($this->isNoop($object->getManager()->getAllName()))?'не назначен':$object->getManager()->getAllName()?>
					</span>

				</div>

				<table class="driverDeliveryData">
					<tr>
						<td><h3>Заказчик:</h3></td>
						<td class="valueText">
							<? if( $object->getClient()->company ): ?>
							<strong><?=$object->getClient()->company ? $object->getClient()->company: ''?></strong><br/>
							<?=$object->getClient()->inn ? 'ИНН '.$object->getClient()->inn : ''?><?=$object->getClient()->kpp ? ', КПП '.$object->getClient()->kpp : ''?><br/>

							<?=$object->getClient()->surname?>
							<?=$object->getClient()->name?>
							<?=$object->getClient()->patronimic?>,
							<?=$object->getClient()->phone?'тел. '.$object->getClient()->phone:''?>
							<?=$object->getClient()->mobile?'моб. '.$object->getClient()->mobile:''?>

							<? else: ?>
								<strong><?=$object->getClient()->surname?>
								<?=$object->getClient()->name?>
								<?=$object->getClient()->patronimic?></strong>,
								<?=$object->getClient()->phone?'тел. '.$object->getClient()->phone:''?>
								<?=$object->getClient()->mobile?', моб. '.$object->getClient()->mobile:''?>
							<? endif; ?>
						</td>
					</tr>
					<tr>
						<td><h3>Принимающий:</h3></td>
						<td class="valueText">
							<strong><?=$object->person?$object->person.',':''?></strong>
							<strong><?=$object->phone ? 'тел. '.$object->phone : ''?></strong>
						</td>
					</tr>
					<tr>
						<td><h3>Оплата:</h3></td>
						<td class="valueText">
							<strong><?= $object->cashPayment==0 ? 'Безналичный' : 'Наличный'?> расчет</strong> (<strong style="color: <?=$object->getPaymentStatus()->color?>"><?=$object->getPaymentStatus()->name?></strong>)
						</td>
					</tr>
					<tr>
						<td><h3>Доставка:</h3></td>
						<td class="valueText">
							<span class="mapAddress"><?=$object->getDeliveryAddressString()?></span><br/>
							<strong><?= $object->deliveryTime ? $object->deliveryTime : ''?></strong>
			<!--				<a href="http://maps.yandex.ru/?text=<?=$object->getDeliveryAddressString()?>&z=12" target="_blank">на Яндекс-карте</a>-->
						</td>
					</tr>
				</table>
				<?if($object->driverNotice):?>
				<table class="driverNotice">
					<tr>
						<td style="padding: 10px;"><h3 style="font-weight: bold; font-size: 17px;">Примечание для водителя:</h3></td>
					</tr>
					<tr>
						<td class="valueText" style="padding: 10px;">
							<?=str_replace("\r\n", '<br>', $object->driverNotice)?>
						</td>
					</tr>
				</table>
				<?endif?>



				<div class="table_edit viewMode">
				<?if($object->getOrderGoods()):?>
					<table width="100%" class="goodsList">
						<tr class="first_line" style="border-bottom: 2px solid #E5E5E5;border-top: 2px solid #E5E5E5;text-align: center;">
							<th class="first" style="font-weight:normal;"><strong>№</strong></th>
							<th><strong>Наименование</strong></th>
							<th><strong>Количество</strong> (шт.)</th>
							<th><strong>Цена за шт.</strong> (руб.)</th>
							<th><strong>Стоимость</strong> (руб)</th>
						</tr>
						<? $i=1; foreach($object->getOrderGoods() as $good):?>
						<tr class="line">
							<td><?=$i?></td>
							<td style="text-align: center;">
								<?=$good->getGood()->getName()?>
								<div class="additionalInfo"><strong><?=$good->goodDescription?></strong></div>
							</td>
							<td style="text-align: center"><?=$good->quantity?></td>
							<td><?=\core\utils\Prices::standartPrice($good->getPrice())?></td>
							<td><?=\core\utils\Prices::standartPrice($good->getPrice() * $good->quantity)?></td>
						</tr>
						<? $i++; endforeach?>
						<tr class="line resultLine">
							<td colspan="2" style="text-align: right; padding-right: 10px;"><b>Всего:</b></td>
							<td  style="text-align: center"><b><?=\core\utils\Prices::standartPrice($object->getTotalQuantity())?></b></td>
							<td></td>
							<td><b><?=\core\utils\Prices::standartPrice($object->getTotalSumForGoods())?></b></td>
						</tr>
						<?if($object->getDelivery()->getCategory()->name):?>
						<tr class="line resultLine">
							<td colspan="4" style="text-align: right; padding-right: 10px;">
								<b>Доставка (<?=$object->getDelivery()->getCategory()->name?>: <?=$object->getDelivery()->getName()?>):</b>
							</td>
							<td>
								<b><?=\core\utils\Prices::standartPrice($object->deliveryPrice)?></b>
							</td>
						</tr>
						<?endif?>
						<tr class="line resultLine">
							<td colspan="4" style="text-align: right; padding-right: 10px;"><b>Итого:</b></td>
							<td>
								<b><?=\core\utils\Prices::standartPrice($object->getTotalSum())?></b>
							</td>
						</tr>
					</table>
				<?endif?>
				</div>
				<? if($object->getOrderGoods()->count() <6): ?>
				<p class="mapsDescription">Внимание! Координаты указаные на карте являются приблизительными и требуют уточнений.</p>
				<div id="map<?=$object->nr?>" class="maps" style="height: <?=600-($object->getOrderGoods()->count()*48)?>px;"></div>
				<? endif; ?>

				<h2 class="thanks"> Благодарим за сотрудничество! Хорошей дороги! </h2>

				<div class="footer">
					<div class="max_width">

						<div class="foot_in">

							<div class="foot_logo">
								<p>Разработка и поддержка</p>
								<a href="http://www.webdelo.org/" class="design" title="Компания WEBDELO"></a>
							</div>

							<div class="footer_box">
								<p class="title">По всем вопросам связанным с заказом обращайтесь по телефону и email:</p>
								<div class="contact">
									<p style="background-image:url(/images/bg/f2.png);"><strong>+7(495)789-36-48</strong></p>
								</div>
								<div class="contact">
									<p style="background-image:url(/images/bg/f3.png)">info@go-informator.ru</p>
								</div>
								<div class="contact">

								</div>
							</div>

							<div class="clear"></div>
						</div>
					</div>
				</div>

</div>
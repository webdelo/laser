<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="/css/go-techno/printCash.css" type="text/css">
	<title>Товарный чек № <?=$order->nr?></title>
</head>
<body onload="window.print();">
	<table style="width: 100%;">
		<tr>
			<td><img src="/images/techno-img/logoWithCaption.png" alt="" height="100"/></td>
			<td style="text-align: center">
				<h2>Товарный чек № <?=$order->nr?> от <?=date('d/m/Y') ?></h2>
			</td>
			<td>
				<table>
					<tr>
						<td colspan="2" align="right">
							<b>Контактные данные:</b>
						</td>
					</tr>
					<tr>
						<td align="right">Сайт: </td>
						<td><b>http://go-techno.ru</b></td>
					</tr>
					<tr>
						<td align="right">E-mail: </td>
						<td><b>info@go-techno.ru</b></td>
					</tr>
					<tr>
						<td align="right">Телефон: </td>
						<td><b>+7 (495) 789-36-48</b></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<h3>Список товаров и услуг:</h3>
	<table class="goodsList">
		<tr>
			<td class="header">Наименование товара</td>
			<td class="header" align="right">Цена</td>
			<td class="header" align="center" width="50">Количество</td>
			<td class="header" width="70">Сумма</td>
		</tr>
		<?foreach($orderGoods as $good):?>
		<tr>
			<td><?=$good->getGood()->getName()?></td>
			<td align="right"><?=$good->price?> руб.</td>
			<td align="center"><?=$good->quantity?>шт.</td>
			<td><?=$good->price * $good->quantity?> руб.</td>
		</tr>
		<?endforeach;?>
		<tr>
			<td style="border: none;"></td>
			<td colspan="2"  align="right"><b>Стоимость товаров:</b></td>
			<td><?=$orderGoods->getTotalGoodsSum()?> руб.</td>
		</tr>
		<?if ($order->promoCodeDiscount):?>
		<tr>
			<td style="border: none;"></td>
			<td colspan="2" align="right">
				<b>
				<?if ($this->isNotNoop($order->getPromoCode())):?>
					<font color="grey">(<?=$order->getPromoCode()->getName()?>)</font>
				<?endif;?>
				Промо-скидка: <?=$order->getPromoCodeDiscount()?>% :</b>
			</td>
			<td>-<?=$orderGoods->getPromoCodeDiscountSum()?> руб.</td>
		</tr>
		<? endif;?>
		<? if ( $this->isNotNoop($order->getDelivery()) ): ?>
		<tr>
			<td colspan="3" align="right">Доставка (<?=$order->getDelivery()->getCategory()->name?>, <?=$order->getDelivery()->getName()?>):</td>
			<td><?=$order->deliveryPrice?> руб.</td>
		</tr>
		<? endif; ?>
		<tr>
			<td style="border: none;"></td>
			<td colspan="2" align="right"><b>Итого:</b></td>
			<td><b><?=$order->getTotalSum()?> руб.</b></td>
		</tr>
	</table>
	<table class="goodsList">
		<tr>
			<td class="bottomRow">
				Товар выдал: ________________________________________
			</td>
			<td class="bottomRow"></td>
			<td class="bottomRow" colspan="2" align="right">
				Товар получил: ________________________________________
			</td>
		</tr>
	</table>
	<div>
		<h3>Гарантийные обязательства:</h3>
		<ul>
			<li>
				При доставке покупатель имеет право в присутствии курьера проверить внешний вид товара и комплектность поставки. После
				момента передачи товара покупателю претензии по внешнему виду и комплектации не принимаются (согласно со ст. 458 и 459 ГК
				РФ). Приобретая товар Вы имеете право на замену на аналогичную модель в течение 14 дней, только в случае неисправности
				данного товара. Мы настоятельно не рекомендуем Вам снимать защитные плёнки с экрана и корпуса устройства в течение 14
				дней со дня приобретения.
			</li>
			<li>
				Изделие обеспечивается бесплатным сервисным обслуживанием в течении 24 месяцев со дня даты продажи (при отсутствии
				нарушений условий гарантии бесплатного сервисного обслуживания):
				<ul>
					<li>Первые 12 месяцев бесплатное сервисное обслуживание включает проведение бесплатного ремонта с заменой необходимых запчастей;
					<li>
						По истечении 12 месяцев и до конца гарантийного срока гарантия распространяется только на выполненную работу без
						использования комплектующих.
					</li>
				</ul>
			</li>
			<li>Бесплатное сервисное обслуживание не распространяется на:
				<ul>
					<li>Аккумуляторные батареи, элементы питания, внешние блоки питания, зарядные устройства, переходники;</li>
					<li>Программное вмешательство, привлекшее к блокировке устройства, изменение операционной системы.</li>
				</ul>
			</li>
			<li>
				Бесплатное сервисное обслуживание не производиться в следующих случаях:
				<ul>
					<li>
						В случае не правильно или не полностью заполненного гарантийного талона и\или товарного чека, при отсутствии
						гарантийного талона, товарного чека, исправлений в товарном чеке, отсутствии подписи продавца и\или покупателя,
						несовпадений серийного номера изделий с серийным номером указанном в гарантийном талоне;
					</li>
					<li>Если изделие имеет следы попыток неквалифицированного ремонта, вскрытия корпуса;</li>
					<li>Если дефект вызван механическим повреждением изделия;</li>
					<li>Если дефект вызван несоответствием стандартам телекоммуникационных питающих кабельных сетей, и\или другими внешними
					факторами;</li>
					<li>Если дефект вызван использованием нестандартных и\или не качественных аксессуаров, принадлежностей, носителей
					информации;</li>
				</ul>
			</li>
			<li>
				Покупатель изделия своими силами или за свой счет осуществляет доставку изделия для его гарантийного
				обслуживания в сервисный центр
			</li>
		</ul>
	</div>
	<h2 style="text-align: center">Благодарим вас за покупку в нашем интернет-магазине!</h2>
	<div class="bottomDescription" style="text-align: center">
		Необходимо сохранить данный чек до окончания гарантийного периода.
	</div>
</body>
</html>
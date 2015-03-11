<table width="100%" style="border-top: 2px solid #E5E5E5;text-align: center;">
	<tr class="first_line" style="border-bottom: 2px solid #E5E5E5;border-top: 2px solid #E5E5E5;text-align: center;">
		<th class="first" style="font-weight:normal;">#</th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">№ заказа <div style="font-size: 11px;color: #9E9E9E;margin-top: 3px;">(дата)</div></th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">№ счета <div style="font-size: 11px;color: #9E9E9E;margin-top: 3px;">(дата)</div></th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">№ пл. пор. <div style="font-size: 11px;color: #9E9E9E;margin-top: 3px;">(дата)</div></th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">Товары и услуги</th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">Стоимость / <span style="color:#9E9E9E">Себестоимость</span><br /><b>товаров</b>, руб</th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">Стоимость / <span style="color:#9E9E9E">Себестоимость</span><br /><b>доставки</b>, руб</th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">Стоимость / <span style="color:#9E9E9E">Себестоимость</span><br /><b>общая</b>, руб</th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">Прибыль, руб</th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">% обналички, руб</th>
		<th style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">Чистая прибыль, руб</th>
		<th class="last" style="background: url(<?=DIR_HTTP?>/admin/images/backgrounds/bdr.png) no-repeat left center;padding: 0 10px;font-weight:normal;">Открыть</th>
	</tr>
	<? $i=1; foreach ($objects as $object):?>
	<tr>
		<td style="border-top: 1px solid #E5E5E5;"><?=$i?></td>
		<td style="border-top: 1px solid #E5E5E5;">
			<?=$object->nr?>
			<div style="font-size: 11px;color: #9E9E9E;margin-top: 3px;"><?=\core\utils\Dates::toDatetime($object->date)?></div>
		</td>
		<td style="border-top: 1px solid #E5E5E5;">
			<?= $object->invoiceNr ? $object->invoiceNr : '-'?>
			<div style="font-size: 11px;color: #9E9E9E;margin-top: 3px;"><?= $object->invoiceNrDate ? \core\utils\Dates::toDatetime($object->invoiceNrDate) : '-'?></div>
		</td>
		<td style="border-top: 1px solid #E5E5E5;">
			<?= $object->paymentOrderNr ? $object->paymentOrderNr : '-'?>
			<div style="font-size: 11px;color: #9E9E9E;margin-top: 3px;"><?= $object->paymentOrderNrDate ? \core\utils\Dates::toDatetime($object->paymentOrderNrDate) : '-'?></div>
		</td>
		<td style="border-top: 1px solid #E5E5E5;">
			<div style="font-size: 11px;color: #9E9E9E;margin-top: 3px;">
				<ul style="margin: 0px;padding:0px;">
				<?if($object->getOrderGoods()): foreach($object->getOrderGoods() as $good):?>
				<li style="margin: 0px;list-style-type: none;"><?=$good->getGood()->getName()?> - <?=$good->quantity?>шт.</li>
				<?endforeach; endif;?>
				<?if($object->getDelivery()->id):?>
				<li style="margin: 0px;list-style-type: none;"><?=$object->getDelivery()->getCategory()->name?> (<?=$object->getDelivery()->getName()?>)</li>
				<?endif;?>
				</ul>
			</div>
		</td>
		<td style="border-top: 1px solid #E5E5E5;">
			<?=\core\utils\Prices::standartPrice($object->getOrderGoods()->getTotalGoodsSum())?>
			/
			<span style="color:#9E9E9E"><?=\core\utils\Prices::standartPrice($object->getOrderGoods()->getTotalGoodsBaseSum())?></span>
		</td>
		<td style="border-top: 1px solid #E5E5E5;">
			<?if($object->getDelivery()->id):?>
			<?=\core\utils\Prices::standartPrice($object->deliveryPrice)?>
			/
			<span style="color:#9E9E9E"><?=\core\utils\Prices::standartPrice($object->deliveryBasePrice)?></span>
			<?else:?>
			-
			<?endif?>
		</td>
		<td style="border-top: 1px solid #E5E5E5;">
			<?=\core\utils\Prices::standartPrice($object->getTotalSum())?>
			/
			<span style="color:#9E9E9E"><?=\core\utils\Prices::standartPrice($object->getTotalBaseSum())?></span>
		</td>
		<td style="border-top: 1px solid #E5E5E5;"><?=\core\utils\Prices::standartPrice($object->getIncomeWithoutCashRate())?></td>
		<td style="border-top: 1px solid #E5E5E5;"><?= $object->getCashRatePrice()==0 ? '' : '-'.\core\utils\Prices::standartPrice($object->getCashRatePrice())?>
			<?= $object->getCashRatePrice()==0 ? $object->cashRate.'%' : '&nbsp;&nbsp;&nbsp;('.$object->cashRate.'%)'?>
		</td>
		<td style="border-top: 1px solid #E5E5E5;"><?=\core\utils\Prices::standartPrice($object->getIncome())?></td>
		<td style="border-top: 1px solid #E5E5E5;"><a href="<?=DIR_HTTP?>admin/orders/order/<?=$object->id?>/" target="_blank" style="border:none;"><img src="<?=DIR_HTTP?>admin/images/buttons/edit_pen_simple.png" style="border:none;"></a></td>
	</tr>
	<? $i++; endforeach?>
	<tr>
		<td colspan="5" style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;text-align: right; padding-right: 10px;"><b>Итого:</b></td>
		<td style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;">
			<b>
				<?=\core\utils\Prices::standartPrice($objects->getTotalGoodsSum())?>
				/
				<span style="color:#9E9E9E"><?=\core\utils\Prices::standartPrice($objects->getTotalGoodsBaseSum())?></span>
			</b>
		</td>
		<td style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;">
			<b>
				<?=\core\utils\Prices::standartPrice($objects->getTotalDeliverySum())?>
				/
				<span style="color:#9E9E9E"><?=\core\utils\Prices::standartPrice($objects->getTotalDeliveryBaseSum())?></span>
			</b>
		</td>
		<td style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;">
			<b>
				<?=\core\utils\Prices::standartPrice($objects->getTotalSum())?>
				/
				<span style="color:#9E9E9E"><?=\core\utils\Prices::standartPrice($objects->getTotalBaseSum())?></span>
			</b>
		</td>
		<td style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;"><b><?=\core\utils\Prices::standartPrice($objects->getIncomeWithoutCashRate())?></b></td>
		<td style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;"><b>- <?=\core\utils\Prices::standartPrice($objects->getCashRatePrice())?></b></td>
		<td style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;"><b><?=\core\utils\Prices::standartPrice($objects->getIncome())?></b></td>
		<td style="border-top: 2px solid #E5E5E5;border-bottom: 2px solid #E5E5E5;"></td>
	</tr>
</table>
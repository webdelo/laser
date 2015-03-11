<table class="goodsList">
	<tr class="top">
		<td>Товар</td>
		<td class="borderLeft">Цена / Старая цена</td>
		<td class="borderLeft">Минимальное<br />количество</td>
	</tr>
	<tr class="line">
		<td>
			<a href="<?=$good->getFirstPrimaryImage()->getImage('800x600')?>" class="lightbox noNextPrev">
				<img src="<?=$good->getFirstPrimaryImage()->getImage('40x40')?>" />
			</a>
			<a href="<?=$good->getAdminUrl()?>/" target="blank">
				<?=$good->getName()?> (<?=$good->getCode()?>)
			</a>
			<br />
			<div class="comment"><?=$good->goodDescription?></div>
		</td>
		<td>
			<b><?=$good->getPrices()->getPriceByQuantity($good->getPrices()->getMinQuantity())->getPrice()?></b> /
			<span style="color: #666;">
				<?=$good->getPrices()->getPriceByQuantity($good->getPrices()->getMinQuantity())->getOldPrice()?>
				( -<?=$good->getPrices()->getPriceByQuantity($good->getPrices()->getMinQuantity())->getDiscount()?> )
			</span>
		</td>
		<td>
			<?=$good->getPrices()->getMinQuantity()?>
		</td>
	</tr>
</table>

<?
	$primaryGood = $good->getGood()->getPrimaryGood();
	$secondaryGoods = $good->getGood()->getSecondaryGoods();
?>
<tr>
	<td style="border-bottom: 1px dotted #007fb9;"><?=$i?></td>
	<td style="border-bottom: 1px dotted #007fb9;">
		<a href="http://<?=$order->domain?><?=$primaryGood->getPath()?>" target="_blank">
			<? if( $this instanceof core\mail\MailBase ): ?>
			<img border="0" src="<?=$this->setImageHere($good->getGood()->getFirstPrimaryImage()->getPath())?>" height="50" width="50">
			<? else: ?>
			<img border="0" src="http://<?=$order->domain?><?=$primaryGood->getGood()->getFirstPrimaryImage()->getImage('50x50')?>" height="50" width="50">
			<? endif; ?>
		</a>
	</td>
	<td style="border-bottom: 1px dotted #007fb9;" align="center">
		<table style="width: 100%;">
			<tr>
				<td valign="top">
					<strong>Комплект: <?=$good->getGood()->getName()?> ( <?=$good->getGood()->getCode()?> )</strong>
					<ul>
						<li><a href="http://<?=$order->domain?><?=$primaryGood->getGood()->getPath()?>" target="_blank"><?=$primaryGood->getGood()->getName()?> (<?=$primaryGood->getGood()->getCode()?>)</a></li>
						<?foreach($secondaryGoods as $secondaryGood):?>
						<li><a href="http://<?=$order->domain?><?=$secondaryGood->getGood()->getPath()?>" target="_blank"><?=$secondaryGood->getGood()->getName()?> (<?=$secondaryGood->getGood()->getCode()?>)</a></li>
						<?endforeach?>
					</ul>
				</td>
			</tr>
		</table>
	</td>
	<td style="border-bottom: 1px dotted #007fb9;"><?=$good->price?> / <font color="#999"><?=$good->basePrice?></font></td>
	<td style="border-bottom: 1px dotted #007fb9;"><?=$good->quantity?></td>
	<td style="border-bottom: 1px dotted #007fb9;"><?=$good->price * $good->quantity?></td>
	<td style="border-bottom: 1px dotted #007fb9;">
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<?=\core\utils\Prices::standartPrice($good->getBasePrice())?>
		<?endif?>
	</td>
	<td style="border-bottom: 1px dotted #007fb9;">
		<? if($this->checkUserRight('order_access_to_income')): ?>
		<?=\core\utils\Prices::standartPrice($good->getIncome())?>
		<?endif?>
	</td>
</tr>
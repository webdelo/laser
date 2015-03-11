<tr>
	<td style="border-bottom: 1px dotted #007fb9;"><?=$i?></td>
	<td style="border-bottom: 1px dotted #007fb9;">
		<a href="http://<?=$order->domain?><?=$good->getGood()->getPath()?>" target="_blank">
			<? if( $this instanceof core\mail\MailBase ): ?>
			<img border="0" src="<?=$this->setImageHere($good->getGood()->getFirstPrimaryImage()->getPath())?>" height="50" width="50">
			<? else: ?>
			<img border="0" src="http://<?=$order->domain?><?=$good->getGood()->getFirstPrimaryImage()->getImage('50x50')?>" height="50" width="50">
			<? endif; ?>
		</a>
	</td>
	<td align="left" style="padding-left: 20px; border-bottom: 1px dotted #007fb9;">
		<a href="http://<?=$order->domain?><?=$good->getGood()->getPath()?>" target="_blank"><?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)</a>
		<br />
		<?=$good->goodDescription?>
	</td>
	<td style="border-bottom: 1px dotted #007fb9;"><?=$good->price?></font></td>
	<td style="border-bottom: 1px dotted #007fb9;"><?=$good->quantity?></td>
	<td style="border-bottom: 1px dotted #007fb9;">
		<?if($good->getGood()->isZeroPrice()):?>
		договорная
		<?else:?>
		<?=$good->price * $good->quantity?>
		<?endif?>
	</td>

</tr>
<? include ('headerOneClick.tpl'); ?>
	<table>
		<tr>
			<td valign="top" colspan="2">
				<p>
					Позвонить клиенту по номеру: <b style="font-size: 20px;"><?=$clientPhoneNumber?></b>
				</p>
			</td>
		</tr>
		<tr>
			<td style="width: 35%;">
				<b style="font-size: 14px;">Выбранный товар:</b>
				<table width="150" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="font-size: 12px; padding: 0; width: 40%;">
							Название:
						</td>
						<td style="font-size: 13px; padding: 0;">
							<b><?=$good->getName()?></b>
						</td>
					</tr>
					<tr>
						<td style="font-size: 13px; padding: 0;">
							Код:
						</td>
						<td style="font-size: 12px; padding: 0;">
							<b><?=$good->getCode()?></b>
						</td>
					</tr>

					<tr>
						<td style="font-size: 13px; padding: 0;">
							Цена:
						</td>
						<td style="font-size: 12px; padding: 0;">
							<b><?=$good->getPrices()->getPriceByQuantity($good->getPrices()->getMinQuantity())->getPrice()?> руб.</b>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="font-size:12px; padding: 8px 0px 0px 10px;">
							<a href="<?=$good->getPath()?>" style="padding: 5px 10px; border: 1px solid #0072BC; background: #198cd3; color: #ffffff; text-decoration: none; font-size: 13px; width: 300px; display: block; text-align: center;">Открыть товар</a>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="font-size:12px; padding: 8px 0px 0px 10px;">
							<a href="<?=$good->getAdminPath()?>/" style="padding: 5px 10px; border: 1px solid #00a336; background: #17a845; color: #ffffff; text-decoration: none; font-size: 13px; width: 300px; display: block; text-align: center;">Редактировать товар</a>
						</td>
					</tr>
				</table>
			</td>
			<td colspan="2" style="padding: 0;">
				<img style="margin: -20px 0px -38px 0px;" src="<?=DIR_HTTP?><?=$good->getFirstPrimaryImage()->getImage('165x165')?>">
			</td>
		<tr>
			<td valign="top" colspan="2">
				<?if($managers):?>
				Менеджеры :&nbsp;&nbsp;&nbsp;&nbsp;
				<?foreach($managers as $manager):?>
				<?=$manager?>&nbsp;&nbsp;&nbsp;&nbsp;
				<?endforeach?>
				<br /><br />
				<?endif?>
			</td>
		</tr>
	</table>
<? include ('footerAdmin_'.$this->getCurrentDomainAlias().'.tpl'); ?>
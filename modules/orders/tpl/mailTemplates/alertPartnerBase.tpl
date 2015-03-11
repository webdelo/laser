<?
	$clientName = $order->getClient()->company?$order->getClient()->company:$order->getClient()->getAllName();
	$headerTitle = ' Новый заказ для '.$order->getPartner()->name.' от '.$clientName;
?>
<? include ('header.tpl'); ?>
				<table border="0" width="800">
					<tr>
						<td colspan="2">
							<?if(isset($aditionalMessage)):?>
							<b>Примечание:</b>
							<br />
							<?=$aditionalMessage?>
							<br />
							<?endif?>
						</td>
					</tr>
					<tr>
						<td width="0">
							<a
								href="<?=DIR_HTTP?>admin/orders/order/<?=$order->id?>/partnerNotifyConfirm/"
								style="padding: 5px 10px; border: 1px solid #0072BC; background: #198cd3; color: #ffffff; text-decoration: none; font-size: 13px; width: 300px; display: block; text-align: center;"
							>Подтвердить заказ</a>
						</td>
						<td>
							<a
								href="<?=DIR_HTTP?>admin/orders/order/<?=$order->id?>/"
								style="padding: 5px 10px; border: 1px solid #00a336; background: #17a845; color: #ffffff; text-decoration: none; font-size: 13px; width: 300px; display: block; text-align: center;"
							>Посмотреть заказ</a>
						</td>
					</tr>
				</table>

				<?include(DIR.'/modules/orders/tpl/mailTemplates/alertOrderContent.tpl')?>

<? include ('footerAdmin_'.(isset($order->domain) ? $order->domain : $this->getCurrentDomainAlias()).'.tpl'); ?>
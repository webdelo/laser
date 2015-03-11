<? $headerTitle = 'Новый заказ на сайте '.SEND_FROM ?>
<? include ('header.tpl'); ?>
	<table style="font-size: 12px;">
		<tr>
			<td style="padding-left: 10px;">
				<p>
					<br />
					На сайте <?SEND_FROM?> был оформлен новый заказ!
					<br />
					Заказ оформлен пользователем с логином <?=$order->addedBy?>
					<br />
					<?include(DIR.'/modules/orders/tpl/mailTemplates/alertOrderContent.tpl')?>
				</p>
			</td>
		</tr>
		<tr>
			<td>
				<?if($managers):?>
				<br /><br />
				Адресаты сообщения:&nbsp;&nbsp;&nbsp;&nbsp;
				<?foreach($managers as $manager):?>
				<?=$manager?>&nbsp;&nbsp;&nbsp;&nbsp;
				<?endforeach?>
				<br /><br />
				<?endif?>
			</td>
		</tr>
	</table>
<? include ('footerAdmin_'.(isset($order->domain) ? $order->domain : $this->getCurrentDomainAlias()).'.tpl'); ?>
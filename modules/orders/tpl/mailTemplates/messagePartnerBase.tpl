<? $client_name = $order->getClient()->company ? $order->getClient()->company : $order->getClient()->getAllName(); ?>
<?$headerTitle = ' Новое сообщение по заказу №'.$order->nr . ' (' . $client_name . ')' ?>
<? include ('header.tpl'); ?>

				<table border="0" width="800">
					<tr>
						<td width="400">
							<a
								href="<?=DIR_HTTP?>admin/orders/order/<?=$order->id?>/"
								style="padding: 5px 10px; border: 1px solid #00a336; background: #17a845; color: #ffffff; text-decoration: none; font-size: 13px; width: 300px; display: block; text-align: center;"
							>Посмотреть заказ</a>
						</td>
					</tr>
					<?if(isset($aditionalMessage)):?>
					<tr style="border-bottom: 1px solid #ccc; margin-bottom: 20px;">
						<td style="font-size: 14px;">
							<strong>Текст сообщения:</strong><br />
							<?=$aditionalMessage?>
						</td>
					</tr>
					<?endif?>
				</table>
				<?include(DIR.'/modules/orders/tpl/mailTemplates/alertOrderContent.tpl')?>

<? include ('footerAdmin_'.$order->domain.'.tpl'); ?>
<? $headerTitle = 'Таблица прибыли заказов' ?>
<? include ('headerSimple.tpl'); ?>
	<table>
		<tr>
			<td style="padding-left: 10px; font-size: 9px;">
				<p style="font-size: 15px;">
					<?=$data['text']?>
					<br /><br /><br />
					<?=$data['table']?>
					<br />
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
<? include ('footerAdmin_'.$data['domain'].'.tpl'); ?>
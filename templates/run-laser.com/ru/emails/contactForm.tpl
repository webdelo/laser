<? include ('header.tpl'); ?>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<h2 class="subject" style="width: 100%;text-align: center;color: #24667B;">Новое сообщение с сайта <?=SEND_FROM?></h2>
					<p>
						Здравствуйте.
						<br /><br />
						С сайта <?=SEND_FROM?> было отправлено новое сообщение.
						<br /><br />
						Данные клиента:
						<br />
						Имя: <strong><?=$data['msgName']?></strong>
						<br />
						Емайл: <strong><?=$data['email']?></strong>
						<br /><br />
						Текст: <strong><?=$data['msgText']?></strong>
						<br /><br />
					</p>
				</td>
			</tr>
<? include ('footerAdmin.tpl'); ?>
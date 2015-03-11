<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="ru">
		<title>Drivers Report</title>
		<link rel="stylesheet" type="text/css" href="/admin/css/style.css">
		<link rel="stylesheet" type="text/css" href="/modules/orders/css/driver.css">
			
		<link rel="stylesheet" type="text/css" href="/admin/js/base/actions/styles/errors.css">
		<script type="text/javascript" src="/admin/js/jquery/jquery.js"></script>
		
		<script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
		<script src="/modules/orders/js/driver.js" type="text/javascript"></script>
		
		<script type="text/javascript" src="/admin/js/base/actions/form.class.js"></script>
		<script type="text/javascript" src="/admin/js/base/actions/loader.class.js"></script>
		<script type="text/javascript" src="/admin/js/base/actions/errors.class.js"></script>
		<script type="text/javascript" src="/admin/js/base/actions/error.class.js"></script>
		<script type="text/javascript" src="/modules/orders/js/mailGroupDriver.js"></script>
	</head>
	<body>
		<div class="root">
			<div class="main single">
				<div class="max_width" style="max-width: none; width: 1130px;">
					<?=$table?>
				</div>
				<div class="max_width" style="margin-top:20px;">
					<a class="show_table" style="border-bottom: 1px dashed;">Рассылка ↓</a>
					<br /><br />
					<table class="mail_table hide">
						<tr>
							<td style="padding:15px 0px 0px 0px; border: 1px solid #E5E5E5;border-bottom: none;text-align: center;font-size: 18px">
								Рассылка таблицы водителям
							</td>
						</tr>
						<tr>
							<td style="padding:20px; border: 1px solid #E5E5E5;border-top: none;">
								<form class="action_buts mailGroupDriver" action="/admin/orders/mailGroupDriver/" method="post" style="float: left; margin: 0px 0px 0px 20px; text-align: center;">
									<input type="text" placeholder="От" name="email" value="info@go-informator.ru" style="width:404px;font-size: 12px;color: #333;">
									<br /><br />
									<input type="text" placeholder="Скрытые копии (email через запятую)" name="bcc" style="width:404px;font-size: 12px;color: #333;">
									<br /><br />
									<input type="text" placeholder="Тема сообщения" name="subject" value="Таблица заказов для водителей с сайта <?=SEND_FROM?>" style="width:404px;font-size: 12px;color: #333;">
									<br /><br />
									<textarea placeholder="Emails (через запятую)" name="emails" class="to_clear" style="width: 400px; height: 50px;"></textarea>
									<br /><br />
									<textarea placeholder="Сообщение" name="text" class="to_clear" style="width: 400px; height: 50px;"></textarea>
									<input type="hidden" name="ids" value="<?=$this->getGet()['ids']?>">
									<br /><br />
									<a class="pointer mailGroupDriverSubmit"><img src="/admin/images/buttons/but_resend.png" alt=""> Отправить таблицу и сообщение</a>
									<div class="okBlock hide" style="margin: 10px auto 0px auto;color: green;font-size: 16px;background-color: #F0F2F2;padding: 5px;text-align: center;width: 217px;border: 1px solid #D6D9DA;border-radius: 3px;">
										Сообщение и таблица отправлены.
										<br />
										<a class="pointer okButton" style="width: 80px;margin: 5px 0px 0px 0px"> ОК</a>
									</div>
								</form>
							</td>
						</tr>
					</table>
				</div>
			</div><!--main-->
			<div class="vote"></div>
		</div><!--root-->
	</body>
</html>
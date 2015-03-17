<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Authorization</title>
		<link href="/admin/css/style.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/admin/js/jquery/jquery.js"></script>
		<script type="text/javascript" src="/admin/js/base/system/js.js"></script>
	</head>
	<body onload="document.getElementById('user').focus();">
		<div class="root authorization">
			<div class="auth_box">
				<div class="max_width">
					<div class="login_box">
						<div class="login_in">
							<p class="logo"><a href="http://webdelo.org"><img src="/admin/images/logo/auth_logo.png" alt="" /></a></p>
							<form action="<?=$this->link?>" method="post">
								<table width="100%">
									<tr>
										<td>Логин:</td>
										<td><input type="text" id="user" name="<?=$this->postLoginKey ?>" style="background-image:url(/admin/images/backgrounds/i1.png)" /></td>
									</tr>
									<tr>
										<td>Пароль:</td>
										<td><input type="password" name="<?=$this->postPasswordKey ?>" style="background-image:url(/admin/images/backgrounds/i2.png)" /></td>
									</tr>
									<tr>
										<td></td>
										<td><input type="checkbox" checked name="cookie" /> - запомнить на этом компьютере</td>
									</tr>
									<tr>
										<td></td>
										<td align="right">
											<input type="hidden" name="<?=$this->postSubmitKey ?>" value="Login" />
											<button onclick="submit();"><span>Авторизоваться</span></button>
										</td>
									</tr>
									<tr>
										<td></td>
										<td align="right">
											<font color="red"><?echo $this->errorMessage?></font>
										</td>
									</tr>
								</table>
							</form>
						</div>
					</div><!--login_box-->
					<div class="text">
						<div class="col_in">
							<p class="title">Авторизация в система администрирования</p>
							<p>Для авторизации в системе администрирования, введите логин и пароль</p>
							<p>Так же можно вернуться на <a href="<?=DIR_HTTP?>">главную страницу сайта</a>.</p>
							<p>Возникли вопросы? Задайте по приведённым ниже контактам.</p>
							<div class="foot_in">
								<div class="f_right">
									<p><a href="http://webdelo.org"><img src="/admin/images/logo/foot_logo.png" alt="" /></a></p>
									<p>Developed by WebDelo</p>
									<p>Copyright © 2007-<?=date("Y")?></p>
								</div>
								<div class="block">
									<p class="title">Коммерческий отдел webdelo:</p>
									<p>Р. Молдова, г.Тигина,<br />
									ул. Ленина, д. 31, оф. 77<br />
									тел./факс +7 (495) 789-36-48<br />
									skype: webdelo.org</p>
								</div>
								<div class="block">
									<p class="title">Технический отдел webdelo:</p>
									<p>skype: webdelo.support<br />
									e-mail: support@webdelo.org<br />
									webdelo.worksection.com</p>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div><!--root-->
	</body>
</html>
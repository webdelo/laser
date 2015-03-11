<?include(TEMPLATES_ADMIN.'meta.tpl');?>
	<body>
		<div class="root">
			<div class="pop">
				<div class="pop_bg"></div>
				<div class="pop_rel">
					<div class="login_box">
						<div class="login_in">
							<p class="logo"><a href="#"><img src="/admin/images/logo/auth_logo.png" alt="" /></a></p>
							<table width="100%">
								<tr>
									<td>Логин:</td>
									<td><input type="text" value="" style="background-image:url(images/bg/i1.png)" /></td>
								</tr>
								<tr>
									<td>Пароль:</td>
									<td><input type="text" value="" style="background-image:url(images/bg/i2.png)" /></td>
								</tr>
								<tr>
									<td></td>
									<td><input type="checkbox" checked /> - запомнить на этом компьютере</td>
								</tr>
								<tr>
									<td></td>
									<td align="right"><button><span>Авторизоваться</span></button></td>
								</tr>
							</table>
						</div>
					</div><!--login_box-->
				</div>
			</div>
			<div class="top_line">
				<div class="max_width">
					<div class="max_width">
						<div class="logout">
<!--							<a href="#" style="background-image:url(/admin/images/buttons/lock.png)">Блокировка</a>-->
							<a href="/admin/?logout=1" class="pointer" style="background-image:url(/admin/images/buttons/one_way.png)">Выход</a>
						</div>
						<?include(TEMPLATES_ADMIN.'menu.tpl');?>
						<div class="clear"></div>
					</div>
				</div>
			</div><!--top_line-->
			<div class="head_line">
				<div class="max_width">
					<p class="logo"><a href="/admin/"><img src="/admin/images/logo/logo.png" alt="" /></a></p>
					<?$this->getController('Notifier')->getTopNotices()?>
					<div class="clear"></div>
				</div>
			</div><!--head_line-->

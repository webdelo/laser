<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="ru">
		<title>Profit Report</title>
		<link rel="stylesheet" type="text/css" href="/admin/css/style.css">
		<link rel="stylesheet" type="text/css" href="/admin/js/base/actions/styles/errors.css">
		<link rel="stylesheet" type="text/css" href="/modules/orders/tpl/reports/css/style.css">
		<script type="text/javascript" src="/admin/js/jquery/jquery.js"></script>
		<script type="text/javascript" src="/admin/js/base/actions/form.class.js"></script>
		<script type="text/javascript" src="/admin/js/base/actions/loader.class.js"></script>
		<script type="text/javascript" src="/admin/js/base/actions/errors.class.js"></script>
		<script type="text/javascript" src="/admin/js/base/actions/error.class.js"></script>
		<script type="text/javascript" src="/js/ajaxLoader.class.js"></script>
		<script type="text/javascript" src="/modules/orders/js/mailGroupProfit.js"></script>
	</head>
	<body>
		<div class="root">
			<div class="head_line" style="padding: 2px 0;">
				<div class="max_width">
					<p class="logo"><a href="/admin/"><img src="/admin/images/logo/logo.png" alt=""></a></p>
					<div style="float: left; margin: 13px 0px 5px 150px; text-align: center; font-size: 16px; font-weight: bold;">Сводная таблица прибыли заказов</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="main single">
				<div class="max_width" style="max-width: none; width: 1130px;">
					<div class="table_edit">
						<?=$table?>
					</div>
				</div>
				<div class="max_width" style="margin-top:20px;">

					<a class="persentPayed" title='назначить "выплачен процент" для всех выбранных заказов'>назначить<br />"выплачен процент"</a>
					<div class="persentPayedOk hide" style="margin: 10px auto 0px auto;color: green;font-size: 16px;background-color: #F0F2F2;padding: 5px;text-align: center;width: 217px;border: 1px solid #D6D9DA;border-radius: 3px;">
						Для всех заказов в таблице отмечен<br />"выплачен процент".
					</div>

					<div class="clear"></div>

					<a class="show_table" style="border-bottom: 1px dashed;">Рассылка ↓</a>
					<br /><br />
					<table class="mail_table hide">
						<tr>
							<td style="padding:15px 0px 0px 0px; border: 1px solid #E5E5E5;border-bottom: none;text-align: center;font-size: 18px">
								Рассылка таблицы партнерам
							</td>
						</tr>
						<tr>
							<td style="padding:20px; border: 1px solid #E5E5E5;border-top: none;">
								<div style="float: left;">
									<link rel="stylesheet" type="text/css" href="/admin/js/jquery/tree/styles/jQuery.Tree.css" />
									<script type="text/javascript" src="/admin/js/jquery/tree/jQuery.Tree.js"></script>
									<script type="text/javascript" src="/modules/orders/js/chartsHandler.js"></script>
									 <script type="text/javascript">
										$(document).ready(function(){
											$('.tree ul:first').Tree();
										});
									</script>
									<div class="table_edit">
										<div class="left tree checkboxes" style="float: none;">
											<ul>
												<li>
													<label>
														Партнеры
													</label>
													<ul>
														<?
														if($partners):
														foreach($partners as $partner):
														?>
														<li>
															<label>
																<?=$partner->name?>
															</label>
															<?
															if($partner->getManagers()):
															foreach($partner->getManagers() as $manager):
															?>
															<ul>
																<li>
																	<label class="manager" value="<?=$manager->getLogin()?>">
																		<input type="checkbox"> <?=$manager->name?> (<?=$manager->getLogin()?>)
																	</label>
																</li>
															</ul>
															<?endforeach; endif?>
														</li>
														<?endforeach; endif?>
													</ul>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<form class="action_buts mailGroupProfit" action="/admin/orders/mailGroupProfit/" method="post" style="float: left; margin: 0px 0px 0px 20px; text-align: center;">
									<input type="text" placeholder="От" name="email" value="info@go-informator.ru" style="width:404px;font-size: 12px;color: #333;">
									<br /><br />
									<input type="text" placeholder="Скрытые копии (email через запятую)" name="bcc" style="width:404px;font-size: 12px;color: #333;">
									<br /><br />
									<input type="text" placeholder="Тема сообщения" name="subject" value="Таблица прибыли заказов с сайта <?=SEND_FROM?>" style="width:404px;font-size: 12px;color: #333;">
									<br /><br />
									<textarea placeholder="Emails (через запятую)" name="emails" class="to_clear" style="width: 400px; height: 50px;"></textarea>
									<br /><br />
									<textarea placeholder="Сообщение" name="text" class="to_clear" style="width: 400px; height: 50px;"></textarea>
									<input type="hidden" name="ids" value="<?=$this->getGet()['ids']?>">
									<br /><br />
									<a class="pointer mailGroupProfitSubmit"><img src="/admin/images/buttons/but_resend.png" alt=""> Отправить таблицу и сообщение</a>
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
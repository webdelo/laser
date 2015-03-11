<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<style type="text/css">
			<?include($this->templates."Mail404Error/style.css")?>
		</style>
	</head>
	<body>
		<div class="logo">
			<img src="http://go-informator.ru/modules/orders/tpl/mailTemplates/Mail404Error/logo.png" height="45" alt=""/>
		</div>
		<div class="bigTitle">
			<p>Обнаружена ошибка 404</p>
			<p class="website"><?=$this->getCurrentDomainAlias()?></p>
		</div>
		<div class="tech">
			<p>Техническая информация :</p>
		</div>
		
		<div class="content">
			<table>
			  <col width="130">
			  <col width="800">
			  <tr>
				  <td><b>Время :</b></td>
				<td><?=$data[0][1];?></td>
			  </tr>
			  <tr>
				<td><b>IP :</b></td>
				<td><?=$data[1][1];?> (<?=$data[1][2];?>)</td>
			  </tr>
			  <tr>
				<td><b>Запрос :</b></td>
				<td><?=$data[2][1];?></td>
			  </tr>
			  <tr>
				<td><b>Referer :</b></td>
				<td><?=$data[3][1];?></td>
			  </tr>
			  <tr>
				<td><b>User-Agent :</b></td>
				<td><?=$data[4][1];?></td>
			  </tr>
			</table>
		</div>
		
		<div class="footer">
			<table>
				<col width="400">
				<col width="400">
				<col width="400">
				<tr>
					<td><b>Связаться с нами :</b></td>
					<td><b>Адрес :</b></td>
					<td>Copyright © 2007 - 2014</td>
				</tr>
				<tr>
					<td>тел. <a href="tel:%2B7%20%28495%29%20789-36-48" target="_blank">+7 (495) 789-36-48</a><br>email :<a href="mailto:info@go-informator.ru" target="_blank">info@go-informator.ru</a></td>
					<td>117292, г. Москва,<br>ул. Кржижановского, д. 1/19</td>
					<td></td>
				</tr>
			</table>
			
		</div>

	</body>
</html>
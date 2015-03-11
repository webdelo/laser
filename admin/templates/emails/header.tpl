<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<style type="text/css">
			<?php include($this->templates."css/style.css");?>
		</style>
	</head>
	<body style="width: 98%;border-left: 5px solid #24667B;font-family: Verdana; font-size: 12px;padding: 0px;margin: 0px;">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding-left: 10px;">
					<img src="<?=DIR_HTTP?>images/bg/<?=$this->getCurrentDomainAlias()?>_logo.png">
				</td>
				<td class="mainHead" style="text-align: right; font-size: 9px; color: #414141; padding-left: 10px;">
					<?=date('d M Y H:i:s T')?><br />
					Order ID: <?=$info['text_id']?>
				</td>
			</tr>
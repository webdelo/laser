<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<style type="text/css">
			<?
				include($this->templates."css/style.css");
				$fontFamily = 'font-family: Helvetica Neue,Helvetica,Helvetica,Arial,sans-serif';
				$fontSize = 'font-size: 17px';
				$fontWeight = 'font-weight: 100';
			?>
		</style>
	</head>
	<body style="width: 100%; border-left: none; <?=$fontFamily?>; <?=$fontSize?>; padding: 0px; margin: 0px; background: #f4f4f4; text-shadow: 0px 1px 0px #fff;">
		<table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
			<tr>
				<td>
					<table>
						<tr>
							<td style="width: 30%;"></td>
							<td style="text-align: center;">
								<? if( $this instanceof core\mail\MailBase ): ?>
								<img border="0" src="<?=$this->setImageHere(DIR.'/images/content/logo_ru.png')?>">
								<? else: ?>
								<img border="0" src="<?=DIR_HTTP?>/images/content/logo_ru.png" alt="">
								<? endif; ?>
							</td>
							<td style="width: 30%;"></td>
						</tr>
					</table>
					<table>
					<tr>
						<td style="width: 30%;"></td>
						<td style="text-align: center;">
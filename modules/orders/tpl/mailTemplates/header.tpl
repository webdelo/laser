<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<style type="text/css">
			<?include($this->templates."css/style.css")?>
		</style>
	</head>
	<body style="width: 900px; border-left: none; font-family: Verdana; font-size: 12px; padding: 0px;margin: 0px;">
		<table border="0" cellpadding="0" cellspacing="0" style="border-left: 5px solid #24667B; width: 900px;">
			<tr>
				<td style="padding: 0px 0px 0px 10px; width: 60px;">
					<? if( $this instanceof core\mail\MailBase ): ?>
					<img border="0" src="<?=$this->setImageHere(DIR.'/images/bg/'.(isset($order->domain) ? $order->domain : $this->getCurrentDomainAlias()).'_logo.png')?>" width="50">
					<? else: ?>
					<img border="0" src="<?=DIR_HTTP?>/images/bg/<?= isset($order->domain) ? $order->domain : $this->getCurrentDomainAlias()?>_logo.png" height="20" alt="">
					<? endif; ?>
				</td>
				<td style="width: 500px;">
					<h2 class="subject" style="text-align: center; color: #24667B; font-size: 14px; width:500px; padding:0px"><?=$headerTitle?></h2>
				</td>
				<td class="mainHead" style="text-align: right; font-size: 11px; color: #414141; padding: 0px 0px 0px 10px;">
					<?=date('d ')?><?=$this->getObject('\core\utils\DateMonth')->getMonthFullGenetive(date('m'))?><?=date(' Y H:i:s (T)')?><br/>
					<? if($order->nr): ?>Заказ №: <a href="<?=DIR_HTTP?>admin/orders/order/<?=$order->id?>/"><?=$order->nr?></a><br/><? endif; ?>
					<? if($order->getStatus()->name): ?>Статус: <b style="color: <?=$order->getStatus()->color?>"><?=$order->getStatus()->name?></b><br /> <? endif; ?>
					<? if($this->isNotNoop($order->getManager())): ?>Менеджер: <b><?=$order->getManager()->getAllName()?></b><? endif; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding-left: 10px; font-size: 12px;">
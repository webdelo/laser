				</td>
			</tr>
			<tr style="background-color: #f2f5ff;">
				<td colspan="3" style="padding:0; margin:0;">
					<table>
						<tr>
							<td style="width: 43%; border-top: 1px dotted #00CCFF; padding: 0px; text-align: center;" class="topBorder zeroPadding menuBack center">

							</td>
							<td style="width: 33%; border-top: 1px dotted #00CCFF; padding: 0px; text-align: center;" class="topBorder zeroPadding menuBack center">
								<table class="menu" style="display: block; width: auto;">
									<tr>
										<td><a href="http://<?=$order->domain?>" style="font-size: 12px"><?=$order->domain?></a></td>
									</tr>
								</table>
							</td>
							<td style="width: 23%; border-top: 1px dotted #00CCFF; padding: 0px; text-align: center;" class="topBorder zeroPadding menuBack center">
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding: 0px;">
					<table width="800">
						<tr>
							<td valign="top" style="padding: 5px; text-align: left; padding-left: 50px;">
								<strong style="color: #666; padding: 0; margin: 0; font-size: 10px; font-family: Arial, Helvetica, sans-serif;">Коммерческий отдел:</strong>
								<p style="color: #666; padding: 0; margin: 0; font-size: 11px; font-family: Arial, Helvetica, sans-serif;">
									тел. +7 (495) 789-36-48<br>
									e-mail: info@go-techno.ru
								</p>
							</td>
							<td valign="top" style="padding: 5px; text-align: left; width:350px;">
								<strong style="color: #666; padding: 0; margin: 0; font-size: 10px; font-family: Arial, Helvetica, sans-serif;">Отдел доставок:</strong>
								<p style="color: #666; padding: 0; margin: 0; font-size: 11px; font-family: Arial, Helvetica, sans-serif;">
									skype: delivery@go-techno.ru
								</p>
							</td>
							<td valign="top" style="padding: 5px; text-align: left; width: 150px;">
								<div class="f_right" style="color: #666; font-size: 11px; margin-right: 10px; font-family: Arial, Helvetica, sans-serif; text-align: center;">
									<a href="http://<?=$order->domain?>" border="0" style="text-decoration: none;">
										<? if( $this instanceof core\mail\MailBase ): ?>
										<img border="0" src="<?=$this->setImageHere(DIR.'/images/bg/foot_logo_'.$order->domain.'.png')?>" height="20" alt="">
										<? else: ?>
										<img border="0" src="<?=DIR_HTTP?>images/bg/foot_logo_<?= isset($order->domain) ? $order->domain : $this->getCurrentDomainAlias()?>.png" height="20" alt="">
										<? endif; ?>
									</a>
									<br>
									Copyright © 2007 - <?=date('Y')?>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
<? include ($this->templates.'header.tpl'); ?>
			<div style="margin: 0 auto; width: 600px;">
				<table style="width: 100%;">
					<tr>
						<td style="text-align: left; padding: 0; margin: 0; <?=$fontFamily?>; <?=$fontSize?>; <?=$fontWeight?>; color: #3d3d3d;" valign="top">
							Здравствуйте, <?=$object->getName()?>!
							
							<p style="text-align: left; <?=$fontFamily?>; <?=$fontSize?>; <?=$fontWeight?>;  color: #3d3d3d;">
								Указанный ниже код подтвердит e-mail. Пожалуйста скопируйте его, или просто кликните на него:
							</p>
							<p style="font-weight: bold; font-size: 22px; text-align: center; padding: 0; margin: 0;"> 
								<a style="color:#2b95bb" href="<?=DIR_HTTP?>cabinet/profile/?code=<?=$object->getCodeFromEmail()?>"><?=$object->getCodeFromEmail()?></a>
							</p>
							
						</td>
					</tr>
				</table>
			</div>
<? include ($this->templates.'footer.tpl'); ?>
<? include ($this->templates.'header.tpl'); ?>
			<div style="margin: 0 auto; width: 600px;">
				<table style="width: 100%;">
					<tr>
						<td style="text-align: left; padding: 0; margin: 0; <?=$fontFamily?>; <?=$fontSize?>; <?=$fontWeight?>; color: #3d3d3d;" valign="top">
							Hello, <?=$object->getName()?>!

							<p style="text-align: left; <?=$fontFamily?>; <?=$fontSize?>; <?=$fontWeight?>;  color: #3d3d3d;">
								You have requested change of your password.
								Please copy it, or just click on it:
							</p>
							<p style="font-weight: bold; font-size: 22px; text-align: center; padding: 0; margin: 0;">
								<a style="color:#2b95bb" href="<?=DIR_HTTP?>recovery/changePassword/<?=$object->getLogin()?>/<?=$object->getCodeFromPassword()?>/"><?=$object->getCodeFromPassword()?></a>
							</p>

						</td>
					</tr>
				</table>
			</div>
<? include ($this->templates.'footer.tpl'); ?>
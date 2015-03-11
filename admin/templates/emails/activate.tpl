<? include ('header.tpl'); ?>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<h2 class="subject" style="width: 100%;text-align: center;color: #24667B;">Activation Notification</h2>
					<p>
						Dear <strong><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></strong>,
						<br /><br />
						Your SIM card was successfully activated.
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<table class="simData customer" style="width: 100%">
						<tr>
							<td colspan="2" class="header" style="text-align: center; width: auto;text-align: center;color: #347D98;font-weight: bold;font-size: 14px;padding: 2px;border: none;">Sim Data:</td>
						</tr>
						<tr>
							<td class="head" style="text-align: center; color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Phone number: </td>
							<td class="head" style="text-align: center; color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">SIM card serial number: </td>
						</tr>
						<? $counter = 0; ?>
						<?php foreach ($info['products'] as $key=>$product): 
							if(!empty($product['mobile_num'])):?> 
						<tr<?=($counter%2)?' style="background-color:#f2f7ff;"':''?>>
							<td style="text-align: center;">
							<?php if($planInfo['price_per_text_message_na'] && $planInfo['price_per_minute_na'] ):?>
							<?php echo $product['mobile_num']?>
							<?else:?>
							###############
							<?endif;?>
							</td>
							<td style="text-align: center;"><?php echo $product['serial'] ?></td>
						</tr>
						<? $counter++ ?>
						<?php endif?>	
						<?php endforeach;?>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<br/>
					Your Deactivation Date is: <strong><?php echo \core\utils\Dates::convertDate($info['arr_date'], 'simple')?></strong> <br/>
				</td>
			</tr>
<? include ('footer.tpl'); ?>
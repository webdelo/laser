<? include ('header.tpl'); ?>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<h2 class="subject" style="width: 100%;text-align: center;color: #24667B;">Payment Notification</h2>
					<p>
						Dear <strong><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></strong>,<br />
						Please find the attached invoice for your voice and texts usage. 
						Itemized bills are attached to this email. 
						There are no additional costs for the internet usage.
					<br />
					<br />
					</p>
					<br />
					<table class="customer">
						<tr>
							<td colspan="2" class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Customer Sale Transaction:</td>
						</tr>
						<tr>
							<td class="title">Order ID:</td>
							<td><?php echo $info['text_id'] ?></td>
						</tr>
						<tr class="even" style="background-color:#f2f7ff;">
							<td class="title">Customer name:</td>
							<td><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></td>
						</tr>
						<tr>
							<td class="title">Destination Country:</td>
							<td><?php echo $info['dest_title']?></td>
						</tr>
						<tr class="even" style="background-color:#f2f7ff;">
							<td class="title">SIM card:</td>
							<td><?php echo $info['plan_title']?></td>
						</tr>
						<tr>
							<td class="title">Activation Date:</td>
							<td><?php echo \core\utils\Dates::convertDate($info['dep_date'], 'simple')?></td>
						</tr>
						<tr class="even" style="background-color:#f2f7ff;">
							<td class="title">Deactivation Date:</td>
							<td><?php echo \core\utils\Dates::convertDate($info['arr_date'], 'simple')?></td>
						</tr>
						<tr>
							<td class="title">Total Days:</td>
							<td><?php echo ceil(($info['arr_date']-$info['dep_date'])/86400)+1; ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;" class="tables">
					<table style="width: 100%;">
						<tr>
							<td colspan="3" class="header" style="text-align: center; width: auto;text-align: center;color: #347D98;font-weight: bold;font-size: 14px;padding: 2px;border: none;"> Services </td>
						</tr>
						<tr>
							<td class="nameTitle" style="color: #347D98;font-weight: bold;font-size: 12px;width: 60%;">Name</td>
							<td class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Price</td>
							<td class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Amount</td>
						</tr>
						<tr>
							<td>Voice Usage:</td>
							<td>$<?=($planInfo['price_per_minute'])?$planInfo['price_per_minute']:'0.00'?></td>
							<td>$<?=($info['prices']['billing_price_voice'])?$info['prices']['billing_price_voice']:'0.00'; ?></td>
						</tr>
						<tr class="even" style="background-color:#f2f7ff;">
							<td>SMS Usage:</td>
							<td>$<?=($planInfo['price_per_text_message'])?$planInfo['price_per_text_message']:'0.00'?></td>
							<td>$<?=($info['prices']['billing_price_data'])?$info['prices']['billing_price_data']:'0.00'; ?></td>
						</tr>
						<?php if(!empty($info['billing_add'])): ?>
						<?php foreach ($info['billing_add'] as $key=>$add_info):?>
						<tr>
							<td class="noBorder"></td>
							<td class="title2"><?=$add_info['cause']?>:</td>
							<td>$<?=$add_info['price']?></td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
						<tr>
							<td class="noBorder"></td>
							<td class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Total due:</td>
							<td style="font-weight: bold;">$<?php echo $info['prices']['billing_price']?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><br /></td>
			</tr>
<? include ('footer.tpl'); ?>
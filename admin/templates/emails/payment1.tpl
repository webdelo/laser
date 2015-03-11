<? include ('header.tpl'); ?>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<h2 class="subject" style="width: 100%;text-align: center;color: #24667B;">Receipt for Your Payment to iPhoneTrip.com</h2>
					<p>Dear <strong><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></strong>,<br />
					Please find the attached invoice:<br />
					</p>
					<br />
					<table class="customer" style="width: 300px;">
						<tr>
							<td colspan="2" class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Customer Sale Transaction:</td>
						</tr>
						<tr>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;" class="title">Order ID:</td>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;"><?php echo $info['text_id'] ?></td>
						</tr>
						<tr class="even" style="background-color:#f2f7ff;">
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;" class="title">Customer name:</td>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;"><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></td>
						</tr>
						<tr>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;" class="title">Destination Country:</td>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;"><?php echo $info['dest_title']?></td>
						</tr>
						<tr class="even" style="background-color:#f2f7ff;">
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;" class="title">SIM card:</td>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;"><?php echo $info['plan_title']?></td>
						</tr>
						<tr>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;" class="title">Activation Date:</td>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;"><?php echo \core\utils\Dates::convertDate($info['dep_date'], 'simple')?></td>
						</tr>
						<tr class="even" style="background-color:#f2f7ff;">
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;" class="title">Deactivation Date:</td>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;"><?php echo \core\utils\Dates::convertDate($info['arr_date'], 'simple')?></td>
						</tr>
						<tr>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;" class="title">Total Days:</td>
							<td style="padding: 2px;border-bottom: 1px dotted #7AA8BA;"><?php echo ceil(($info['arr_date']-$info['dep_date'])/86400)+1; ?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;" class="tables">
					<table style="width: 100%">
						<tr>
							<td colspan="4" class="header" style="text-align: center; width: auto;text-align: center;color: #347D98;font-weight: bold;font-size: 14px;padding: 2px;border: none;"> Products and Services </td>
						</tr>
						<tr>
							<td class="nameTitle" style="color: #347D98;font-weight: bold;font-size: 12px;width: 60%;">Name</td>
							<td class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Price</td>
							<td class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Qty</td>
							<td class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Amount</td>
						</tr>
						<?php foreach ($info['products'] as $product):?>
						<tr>
							<td><?php echo $product['title']?>:</td>
							<td>$<?php echo $product['price']?></td>
							<td><?=$product['count']?></td>
							<td>$<?=$product['fullPrice']?></td>
						</tr>
						<?php endforeach;?>				
						<?php if (!empty($info['addons'])) foreach ($info['addons'] as $product):?>
						<tr>
							<td><?php echo $product['title']?>:</td>
							<td>$<?php echo $product['price']?></td>
							<td><?=$product['count']?></td>
							<td>$<?=$product['fullPrice']?></td>
						</tr>
						<?php endforeach;?>
						<?php if (!empty($info['prices']['prod_price'])):?>
						<tr class="even" style="background-color:#f2f7ff;">
							<td class="title"><b>Total:</b></td>
							<td></td>
							<td></td>
							<td style="font-weight: bold;">$<?php echo $info['prices']['prod_price'];?></td>
						</tr>
						<?php endif;?>
						<tr>
							<td class="noBorder"></td>
							<td colspan="2" class="title2">Shipment & Processing:</td>
							<td>$<?php echo $info['prices']['ship_price']?></td>
						</tr>
						<?php if(!empty($info['billing_add_prepay'])): ?>
						<?php foreach ($info['billing_add_prepay'] as $key=>$add_info):?>
						<tr>
							<td class="noBorder"></td>
							<td colspan="2" class="title2"><?php echo $add_info['cause']?>:</td>
							<td>$<?php echo $add_info['price']?></td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
						<?php if (!empty($info['prices']['california_tax'])):?>
						<tr>
							<td class="noBorder"></td>
							<td colspan="2" class="title2">CA Tax (<?php echo $info['prices']['california_tax']?>%):</td>
							<td>$<?php echo $info['prices']['california_tax_value']?></td>
						</tr>
						<?php endif;?>
						<?php if (!empty($info['prices']['discount'])):?>
						<tr>
							<td class="noBorder"></td>
							<td colspan="2" class="title2">Discount (<?php echo $info['prices']['discount']?>%) :</td>
							<td>-$<?php echo $info['prices']['discount_value']?></td>
						</tr>
						<?php endif;?>
						<tr>
							<td class="noBorder"></td>
							<td colspan="2" class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Total due:</td>
							<td style="font-weight: bold;">$<?php echo $info['prices']['total']?></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;" class="thanks">
					Thank you for your order, <br />
					<span>iPhoneTrip Customer Support</span> <br />
				</td>
			</tr>
<? include ('footer.tpl'); ?>
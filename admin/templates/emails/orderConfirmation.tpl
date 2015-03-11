<? include ('header.tpl'); ?>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<h2 class="subject" style="width: 100%;text-align: center;color: #24667B;">Order Confirmation</h2>
					<p>
						Dear <strong><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></strong>,<br />
						<br />
						Thank you for your order with iPhoneTrip;<br />
						<br />
						
						Your receipt, tracking information and User Manual will be emailed to <?php echo $info['bill_email']?> once your order is processed and shipped.<br />
						Orders are usually shipped within a few days after the order is placed. <br />

					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tables first" valign="top">
								<table>
									<tr>
										<td colspan="2" class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">Order details:</td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Order ID:</td>
										<td><?php echo $info['text_id'] ?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">Customer name:</td>
										<td><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Destination Country:</td>
										<td><?php echo $info['dest_title']?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">SIM card:</td>
										<td><?php echo $info['plan_title']?></td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Activation Date:</td>
										<td><?php echo \core\utils\Dates::convertDate($info['dep_date'], 'simple')?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">Deactivation Date:</td>
										<td><?php echo \core\utils\Dates::convertDate($info['arr_date'], 'simple')?></td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Total Days:</td>
										<td><?php echo ceil(($info['arr_date']-$info['dep_date'])/86400)+1; ?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">Delivery date:</td>
										<td><?php echo \core\utils\Dates::convertDate($info['delivery_date'], 'simple')?></td>
									</tr>
								</table>	
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tables first" valign="top">
								<table>
									<tr>
										<td colspan="2" class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">The order will be shipped to:</td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Name</td>
										<td><?php echo (empty($info['ship_firstname'])) ? $info['bill_firstname'] : $info['ship_firstname']?> <?php echo (empty($info['ship_lastname'])) ? $info['bill_lastname'] : $info['ship_lastname']?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">Address</td>
										<td><?php echo (empty($info['ship_addr'])) ? $info['bill_addr'] : $info['ship_addr']?></td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">City</td>
										<td><?php echo (empty($info['ship_city'])) ? $info['bill_city'] : $info['ship_city']?> <?php if ($info['ship_country']=="USA") { echo (empty($info['ship_state'])) ? $info['bill_state'] : $info['ship_state']; } ?> <?php echo (empty($info['ship_zip'])) ? $info['bill_zip'] : $info['ship_zip']?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">Country</td>
										<td><?php echo (empty($info['ship_country'])) ? $info['bill_country'] : $info['ship_country']?></td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Phone</td>
										<td><?php echo (empty($info['ship_phone'])) ? $info['bill_phone'] : $info['ship_phone']?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">Mobile</td>
										<td><?php echo (empty($info['ship_mobile'])) ? $info['bill_mobile'] : $info['ship_mobile']?></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<table border="0" cellpadding="0" cellspacing="0" style="width: 100%">
						<tr>
							<td class="tables first">
								<table border="0" cellpadding="0" cellspacing="0" style="width: 30%">
									<tr>
										<td colspan="2" class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;"> Devices: </td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Name</td>
										<td class="title" style="color: #347D98;width: 20%;">Qnt</td>
									</tr>
									<? $counter = 0; ?>
									<?php foreach ($info['products'] as $product):?>
									<tr<?=($counter%2)?' style="background-color:#f2f7ff;"':''?>>
										<td><?php echo $product['title']?></td>
										<td><?php echo $product['count']?></td>
										<? $counter++ ?>
									</tr>
									<?php endforeach;?>				
									<?php if (!empty($info['addons'])) foreach ($info['addons'] as $product):?>
									<tr<?=($counter%2)?' style="background-color:#f2f7ff;"':''?>>
										<td><?php echo $product['title']?></td>
										<td><?php echo $product['count']?></td>
										<? $counter++ ?>
									</tr>
									<?php endforeach;?>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr>
				<td colspan="2" style="padding-left: 10px; text-align: left;font-size: 14px;color: #008bc6;" class="thanks">
					<br /><br />
					We thank you for your business, <br />
					<span style="color: #000000;font-size: 12px;">iPhoneTrip Customer Support Team</span> <br />
				</td>
			</tr>
<? include ('footer.tpl'); ?>
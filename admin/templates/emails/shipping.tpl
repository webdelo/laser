<? include ('header.tpl'); ?>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<h2 class="subject" style="width: 100%;text-align: center;color: #24667B;">Shipping Notification</h2>
					<p>
						Dear <strong><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></strong>,<br />
						<br />
						Your order has been processed and will be shipped shortly. <br />
						An email with tracking number will follow. <br />
						<br /><br />
						<b>Shipment information:</b> <br />
						You will receive two SIM cards: one for activation and one for back up. <br />
						The back-up SIM card can be used if the original SIM is out of order or for your future travels. <br />
						If you want to use the back-up SIM in the future, please state that upon placing a new order, to save your shipping charges.<br /> 
						Both SIM cards will be supplied in dissimilar envelopes, and marked correspondingly.<br />
						<br />
						<b>Activation Information:</b> <br />
						You will receive your order with a deactivated SIM cards.<br />
						Main SIM card will be activated on your Activation Date, 12:01am CST.<br />
						Please mind that activation requires updating servers of network carriers worldwide, so activation delay may occur (up to several hours).<br /> 
						You will receive a notification email with your SIM phone number at once the chip is activated.<br />
						<br />
						<b>Return Information:</b> <br />
						All SIM cards are disposable and do not require return shipping.<br />
						If your order includes 3G Device, than it has to be sent back to iPhoneTrip up to 24 hours after your Deactivation Date, using our pre-paid label and its original package.
						Please note that you will be charged delay fees of $50 per day of delay if the device is not returned on time. <br />
						If you lost our return label, please contact our customer service and we will send you a new label for free. Do not ship the device using your own labels. <br />
						<br />
						<b>Activation Manual:</b> <br /> 
						In order to use our SIM cards for data, you have to change devices APN settings.<br /> 
						For iPads and iPhones:<br /> 
						       - Connect your device to Wi-Fi<br />
							- Open the following site on your iPad/iPhone Safari Browser: apn.iphonetrip.com<br /> 
							- Click Automatically<br /> 
							- Click on Install Profile and install it on your device<br /> 
						<br /> 
						For other smartphones, modems and MiFi devices you have to change the APN settings manually. Please enter the following information:<br /> 
						<br /> 
						APN: wap.cingular (lower case)<br /> 
						User Name: WAP@CINGULARGPRS.COM (All upper case)<br /> 
						Password: CINGULAR1 (All upper case)<br /> 
						For all devices both Cellular Data and Data Roaming should be "ON" <br /> 
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<table style="width: 100%">
						<tr>
							<td class="tables first" style="width: 100%">
								<table style="width: 100%">
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
									<tr class="even">
										<td class="title" style="background-color:#f2f7ff;">Deactivation Date:</td>
										<td><?php echo \core\utils\Dates::convertDate($info['arr_date'], 'simple')?></td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Total Days:</td>
										<td><?php echo ceil(($info['arr_date']-$info['dep_date'])/86400)+1; ?></td>
									</tr>
									<tr>
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
					<table style="width: 100%">
						<tr>
							<td class="tables first">
								<table style="width: 100%">
									<tr>
										<td colspan="2" class="head" style="color: #347D98;font-weight: bold;font-size:13px;padding: 2px;border-top: none;border-left: none;border-right: none;">The order will be shipped to:</td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Name</td>
										<td><?php echo (empty($info['ship_firstname'])) ? $info['bill_firstname'] : $info['ship_firstname']?> <?php echo (empty($info['ship_lastname'])) ? $info['bill_lastname'] : $info['ship_lastname']?></td>
									</tr>
									<tr class="even" style="background-color:#f2f7ff;">
										<td class="title" style="color: #347D98;width: 50%;">Adress</td>
										<td><?php echo (empty($info['ship_addr'])) ? $info['bill_addr'] : $info['ship_addr']?></td>
									</tr>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">City</td>
										<td><?php echo (empty($info['ship_city'])) ? $info['bill_city'] : $info['ship_city']?></td>
									</tr>
									<?php if ($info['ship_country']=="USA"): ?>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">State</td>
										<td><?php echo (empty($info['ship_state'])) ? $info['bill_state'] : $info['ship_state'];?></td>
									</tr>
									<? endif; ?>
									<tr>
										<td class="title" style="color: #347D98;width: 50%;">Zip</td>
										<td><?php echo (empty($info['ship_zip'])) ? $info['bill_zip'] : $info['ship_zip']?></td>
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
					<br/><br/>
					We thank you for your business, <br/>
					iPhoneTrip Customer Support. <br/>
				</td>
			</tr>
<? include ('footer.tpl'); ?>
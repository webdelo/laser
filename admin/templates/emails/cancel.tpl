<? include ('header.tpl'); ?>
			<tr>
				<td colspan="2" style="padding-left: 10px;">
					<h2 class="subject" style="width: 100%;text-align: center;color: #24667B;">Cancellation Notification</h2>
					<p>
						Dear <strong><?php echo $info['bill_firstname']?> <?php echo $info['bill_lastname']?></strong>,
						<br /><br />
						Your order has been cancelled. <br />
						Your credit card won't be charged and we will not use any details that were submitted during the order process. <br />
						We will be glad to assist you in the future.
						<br /><br />

					</p>
				</td>
			</tr>
<? include ('footer.tpl'); ?>
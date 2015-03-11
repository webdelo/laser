<?include(TEMPLATES_ADMIN.'top.tpl');?>
			<tr>
				<td id="main-content">
					<!-- <a href="?action=calendar"><strong>Month</strong></a> | <a href="?action=calendar&mode=week&week=0"><strong>Week</strong></a>--><br />
					<div class="admin-ttl"><?php echo $month?> / <?php echo $year?></div>
					<div class="admin-calendar"><?php include(TEMPLATES_ADMIN.$template)?></div>
					<div class="admin-pager"><?php echo $pager?></div>
				</td>
			</tr>
		</table>
<?php include(TEMPLATES_ADMIN.'footer.tpl')?>
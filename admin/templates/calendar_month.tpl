<table>
<tr>
	<?php foreach ($days as $day):?>
	<td class="reg_day week_day <?php if ($day=='Sunday') {echo "sunday";} ?>"><?php echo $day?></td>
	<?php endforeach;?>
</tr>
<?php foreach ($infos as $info):?>
<tr>
	<?php for ($i = 1; $i < 8; $i++):?>
	<td class="reg_day <?php if (!empty($info[$i]['data'])):?>act_day<?php endif ?><?php if (empty($info[$i]['day'])):?>empty_day<?php endif ?><?php if (!empty($info[$i]['today'])):?>today<?php endif ?>" >
		<span><?php echo $info[$i]['day']?></span>
		<?php if (!empty($info[$i]['data'])):?>
		<br /><a href="?action=orders&dep_start_date=<?php echo $info[$i]['date1']?>&dep_end_date=<?php echo $info[$i]['date2']?>"><?php echo $info[$i]['data']?> Order(s)</a>
		<?php endif;?>
	</td>
	<?php endfor;?>
</tr>
<?php endforeach;?>
</table>
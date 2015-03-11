<table class="fastFilters">
	<tr>
		<td class="td_title">Fast filters:</td>
	</tr>
	<tr>
		<td class="borderRight">
			<h3>Categories:</h3>
			<div style="height: 100px; overflow-x: auto">
				<table>
				<?php foreach ($categories as $category):?>
					<tr><td><a href="?category=<?=$category->id?>"><?=$category->name?></a></td></tr>
				<? endforeach; ?>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td class="borderRight">
			<h3>Statuses:</h3>
			<div style="height: 100px; overflow-x: auto">
				<ul>
				<?php foreach ($statuses as $status):?>
					<li><a href="?category=<?=$status->id?>"><?=$status->name?></a></li>
				<? endforeach; ?>
				</ul>
			</div>
		</td>
	</tr>
</table>
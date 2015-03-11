<div class="update_info">
	<a href="javasript:" class="prev">&#9668;</a>
	<a href="javasript:" class="next">&#9658;</a>
	<div id="scr">
		<table>
			<tr>
				<?if($noticesItems): foreach($noticesItems as $item):?>
				<td>
					<p><?=$item['title']?>: ( <a href="<?=$item['href']?>"><?=$item['quantity']?></a> )</p>
					<p class="morenotice"><a href="<?=$item['href']?>">просмотреть</a></p>
				</td>
				<?endforeach; endif?>
			</tr>
		</table>
	</div>
</div>
<table class="goodsList table_edit">
	<tr class="top">
		<td class="borderLeft">№</td>
		<td class="borderLeft">Товар</td>
		<td class="borderLeft">Время</td>
		<td class="borderLeft">Модуль</td>
		<td class="borderLeft">Менеджер</td>
		<td class="borderLeft"></td>
	</tr>
	<? $count=0; foreach($orders as $order): ?>
	<tr class="line dblclick" data-url="/admin/orderProcessing/continueOrder/<?=$order->id?>/">
		<td><?=++$count?></td>
		<td> 
			<a href="/admin/orderProcessing/continueOrder/<?=$order->id?>/">
				<? foreach($order->getGoods() as $good): ?> 
				<?=$good->getGood()->getName()?><br/>
				<? endforeach; ?>	
			</a>
		</td>
		<td>
			<span class="time"><?=$order->getTimeAgo()?> назад</span>
		</td>
		<td>
			<span><?=$order->getModule()->name?></span>
		</td>
		<td>
			<span><?=$order->getManager()->getLogin()?></span>
		</td>
		<td>
			<a class="del pointer deleteOrder confirm" data-confirm="Remove the item?" data-action="/admin/orderProcessing/ajaxDeleteOrder/<?=$order->id?>/"></a>
		</td>
	</tr>
	<? endforeach; ?>
</table>
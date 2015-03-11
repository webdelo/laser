<div>
	<?=$client->surname?> <?=$client->name?> <?=$client->patronimic?> | (<?=$client->phone?> <?=$client->email?> <?=$client->company?>)
</div>
<a class="deleteClient" data-action="/admin/orderProcessing/ajaxDeleteClient/?orderId=<?=$order->id?>"></a>
<h1 class="headerFirstLevel rightMenuRow" data-menu="Доставка">Доставка:</h1>
<div id="tabs">
	<div class="tab_page">
		<ul>
			<? foreach($deliveriesCategories as $delivery): ?>
			<li>
				<a href="#delivery<?=$delivery->id?>"><?=$delivery->getName()?></a>
			</li>
			<? endforeach; ?>
		</ul>
	</div>
	<? foreach($deliveriesCategories as $deliveryCategory): ?>
	<div id="delivery<?=$deliveryCategory->id?>">
		<? $this->getDeliveryContentByDeliveryCategoryId($deliveryCategory->id, $order->id) ?>
	</div>
	<? endforeach; ?>
</div>
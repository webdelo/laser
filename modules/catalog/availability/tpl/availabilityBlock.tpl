<link rel="stylesheet" type="text/css" href="/modules/catalog/availability/css/styles.css">
<div class="numbers-price" id="availablePrices">
	<h2>Наличие товара:</h2>
	<div class="out-of-stock">
<?if ($object->getAvailabilityList()->getTotalQuantity()):?>
		В наличии товара: <span><?=$object->getAvailabilityList()->getTotalQuantity()?></span> шт.
<?else:?>
		Нет в наличии
<?endif;?>
	</div>
	<div class="top-suppliers">
		<p class="title-s1">Поставщик</p>
		<p class="title-s2">Наличие</p>
		<p class="title-s3">Поступления / Производство</p>
		<div class="clear"></div>
	</div>
<?foreach ($partners as $partner):
	$availability = $object->getAvailabilityList()->getAvailabilityByPartnerId($partner->id);
?>
	<div class="line-suppliers editing">
		<div id="viewAvailabilityBlock_<?=$partner->id?>">
			<p class="edit-s1"><a class="pointer editAvailabilityForPartner" data-partner_id="<?=$partner->id?>"></a></p>
			<p class="title-s1"><?=$partner->name?></p>
			<p class="title-s2"><?=$availability->getQuantity()?></p>
			<p class="title-s3">
				<?if ($availability->isManufacturer()):?>
					<img src="/admin/images/bg/icon6.png"/>
				<?else:?>
					<img src="/admin/images/bg/icon2.png"/>
				<?endif;?>
			</p>
                        <div class="clear"></div>
                        <span class="last-user"><?=$availability->lastUpdate?> <?=( isset($availability->getUser()->firstname) && isset($availability->getUser()->name) )? ' - '.$availability->getUser()->name.' '.$availability->getUser()->firstname : ' - '.$availability->getUser()->getLogin() ?></span>
                        <? if ($availability->comment): ?>
			<div class="comments" id="comment_<?=$partner->id?>">
				<?=$availability->comment?>
				<a class="pointer viewComment" data-partner_id="<?=$partner->id?>"></a>
			</div>
                        <? endif; ?>
			<div class="clear"></div>
		</div>
		<div id="editAvailabilityBlock_<?=$partner->id?>" class="hide">
			<p class="edit-s1">
				<a class="pointer save saveAvailabilityForPartner" data-partner_id="<?=$partner->id?>"></a>
				<input id="" type="hidden" class="partnerId" value="<?=$partner->id?>" />
			</p>
			<p class="title-s1"><?=$partner->name?></p>
			<p class="title-s2">
				<input id="" type="text" class="quantity" value="<?=$availability->getQuantity()?>" />
			</p>
			<p class="title-s3">
				<input class="manufacture" type="checkbox" value="1" id="" <?if ($availability->isManufacturer()):?>checked<?endif;?>/>
			</p>
			<div class="editing-comment">
				<textarea class="comment" placeholder="Коментарий"><?=$availability->comment?></textarea>
			</div>
			<div class="clear"></div>
		</div>
	</div>
<?endforeach;?>

<!--	<a href="#" class="consider">Редактировать</a>						-->
</div>
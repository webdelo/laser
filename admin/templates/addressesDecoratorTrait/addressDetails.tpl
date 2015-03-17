<div class="addressDetails">
	<a 
		class="deleteAddress confirm" 
		data-action="/admin/<?=$this->getREQUEST()['controller']?>/ajaxDeleteAddress/<?=$object->id?>/"
		data-confirm="Изменить адрес?"
	>изменить адрес</a>
	<p class="addressPlace"><?=$object->getAddress()->getAddressString()?></p>
	<? if($object->getAddress()->hasCoordinates()):?>
	<div class="remark">Маркер на карте установлен в соответствии с введенными координатами: <?=$object->getAddress()->getCoordinates()?></div>
	<? endif; ?>
	<span class="hide dragEndSuccess" style="display: none;">Сохранено!</span>
	<div id="googleMaps" <? if($object->getAddress()->hasCoordinates()):?>data-coordinates="<?=$object->getAddress()->getCoordinates()?>"<? endif; ?>></div>
	<div class="markerWindow" style="display: none;">
		<div style="width: 150px; overflow: hidden;">
			Вы можете перетащить этот маркер, чтобы точнее указать место на карте.
		</div>
	</div>
</div>
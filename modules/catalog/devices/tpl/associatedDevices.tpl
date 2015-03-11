<? if ( $object->colorGroupId ): ?>
<a 
	class="deassociateDevice buttonInContent"
	data-action="/admin/devices/ajaxDeassociateDevice/?id=<?=$object->id?>"
>
	<img src="/admin/images/buttons/delete.png" alt=""> Отказаться от всех связей по цвету
</a>
<? endif; ?>
<? foreach($object->getColors() as $otherColor): ?>
	<? if($otherColor->id !== $object->id): ?>
	<div class="otherGoodItem">
		<a href="/admin/devices/device/<?=$otherColor->id?>/#colors">
			<img src="<?=$otherColor->getFirstPrimaryImage()->getImage('200x150');?>" alt="" />
		</a>
		<p>
			<a href="/admin/devices/device/<?=$otherColor->id?>/#colors"><?=$otherColor->getName()?></a>
		</p>
		<a 
			class="deassociateDevice buttonInContent"
			data-action="/admin/devices/ajaxDeassociateDevice/?id=<?=$otherColor->id?>"
		>
			<img src="/admin/images/buttons/delete.png" alt=""> Удалить связь
		</a>
	</div>
	<? endif; ?>
<? endforeach; ?>
<script type="text/javascript" src="/modules/catalog/js/selectBySelect.js"></script>
<script type="text/javascript" src="/modules/catalog/js/parametersRelation.js"></script>
<div class="parametersInDevices">
	<? if($parameters->count()>0): ?>
	<table>
		<? foreach($parameters as $parameter): ?>
		<tr>
			<td class="title">
				<?=$parameter->name?>
				<a
					href="/admin/parameters/parameter/<?=$parameter->id?>/"
					title="Изменить название, или редактировать возможные значения"
					target="_blank"
				>
					<img src="/admin/images/buttons/but_edit.png" width="12px;" alt="" />
				</a>
			</td>
			<td>
				<select name="parametersValues[<?=$parameter->id?>]" style="width:100px;">
					<option></option>
					<? foreach($parameter->getParameterValues() as $value): ?>
						<option value="<?=$value->id?>"
							<? if(isset($object->getParametersArray()[$parameter->id])): ?>
								<?=($object->getParametersArray()[$parameter->id]===$value->id)?'selected':'';?>
							<? endif; ?>
						><?=$value->getValue()?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<? endforeach; ?>
		<tr>
			<td>
				<?=$this->getFormToNewParameterAdding($object);?>
			</td>
		</tr>
	</table>
	<? else: ?>
		<h5>Для "<?=$object->getCategory()->name?>" не указана категория характеристик</h5>
		<a href="/admin/devices/category/<?=$object->getCategory()->id?>/#categoriesParameters">назначить категорию характеристик</a>
	<? endif; ?>
</div>

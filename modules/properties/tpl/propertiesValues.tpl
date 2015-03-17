<link rel="stylesheet" type="text/css" href="/modules/properties/css/properties.css">
<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
<script type="text/javascript" src="/modules/properties/js/editFromViewMode.js"></script>
<table class="propertyValuesTable" data-sortUrlAction="/admin/properties/changePropertiesValuesPriority/?">
		<tr>
			<td colspan="3">
				<div
					class="newPropertyForm"
					data-action="/admin/properties/addPropertyValue/"
					data-method="post"
				>
					<input type="hidden" name="propertyId" value="<?=$property->id?>" />
					<input name="value" type="text" value="" placeholder="Имя свойства"/>
					<select name="measureCategoryId">
						<? foreach($measures->getCategories() as $measure): ?>
							<option value="<?=$measure->id?>"><?=$measure->getName()?></option>
						<? endforeach; ?>
					</select>
					<a class="newPropertyFormSubmit addInContent"></a>
					<div class="additionalInfo">
						<textarea class="transformer" data-default="добавить описание" name="description"></textarea>
					</div>
				</div>
			</td>
		</tr>
		<? $count=1; foreach( $property->getPropertyValues() as $value ): ?>
		<tr data-id="<?=$value->id?>" data-priority="<?=$value->priority?>">
			<td class="sortHandle">
				<?=$count++?>
			</td>
			<td>
				<input
					class="values transformer"
					name="value"
					type="text"
					value="<?=$value->getValue()?>"
					data-action="/admin/properties/editPropertyValue/"
					data-post="&id=<?=$value->id?>"
					style="width: 200px;"
				/>
				<div class="measurementsTransformed">
					(<select
						name="measureCategoryId"
						class="measurements"
						data-action="/admin/properties/editPropertyValue/"
						data-post="&id=<?=$value->id?>"
					>
						<? foreach($measures->getCategories() as $measure): ?>
							<option value="<?=$measure->id?>" <?=($value->measureCategoryId==$measure->id)?'selected=selected':''?>><?=$measure->getName()?></option>
						<? endforeach; ?>
					</select>)
				</div>
				<div class="additionalInfo">
					<textarea
						class="values transformer"
						name="description"
						data-action="/admin/properties/editPropertyValue/"
						data-post="&id=<?=$value->id?>"
						data-default="добавить описание"
					><?=$value->description?></textarea>
				</div>
			</td>
			<td>
				<a
					class="deletePropertyValue confirm pointer deleteInContent"
					data-confirm="Удалить значение?"
					data-action="/admin/properties/deletePropertyValue/<?=$value->id?>/"
				>
				</a>
			</td>
		</tr>
		<? endforeach;?>
</table>
<script type="text/javascript" src="/modules/catalog/items/js/propertiesAdding.js"></script>
<link rel="stylesheet" type="text/css" href="/modules/catalog/items/css/properties.css" />
<div id="partsPropertiesSortable" data-action="/admin/properties/changePriority/">
	<? foreach( $properties as $property ): ?>
	<div class="propertiesParts" data-id="<?=$property->id?>">
		<h3>
			<input
				type="text"
				name="name"
				value="<?=$property->getName()?>"
				class="transformer formEditExclude editPropertyPart"
				data-action="/admin/properties/editField/"
				data-post="&id=<?=$property->id?>"
			/>
			<span class="additionalInfo actions">
				<span class="preText">Режим редактирования</span>
				<a href="#" class="editModeToPropertyValuesEnable">включить</a>
				<a href="#" class="editModeToPropertyValuesDisable hide">отключить</a>
			</span>
		</h3>
		<div class="propertiesSortable" data-action="/admin/properties/changePropertiesValuesPriority/">
			<? foreach($property->getPropertyValues() as $value): ?>
				<div class="property" data-id="<?=$value->id?>">
					<div class="propertyDetails viewMode">
						<div class="propertyValue">
							<span class="transformed" title="<?=$value->getValue()?>"><?=$value->getValue()?></span>
							<div class="assigned">
								<? if ( $object->getPropertyValueById($value->id)->value  ||  $object->getPropertyValueById($value->id)->value==0 ): ?>
									<span class="value"><?=$object->getPropertyValueById($value->id)->value?></span>
									<span class="measure"><?=$object->getPropertyValueById($value->id)->getMeasure()->shortName?></span>
								<? else: ?>
									<div class="value">указать значение</div>
									<span class="measure"></span>
								<? endif; ?>
							</div>
						</div>
					</div>
					<div class="propertyDetails editPropertyRelation hide">
						<div class="propertyValue">
							<span class="transformed"><?=$value->getValue()?></span>
						</div>
						<div class="propertyRelation saveRelation" data-action="/admin/<?=$_REQUEST['controller']?>/ajaxEditPropertyRelation/">
							<input type="hidden" name="id" class="formEditExclude" value="<?=$object->getPropertyValueById($value->id)->id?>" />
							<input type="hidden" name="ownerId" class="formEditExclude" value="<?=$object->id?>" />
							<input type="hidden" name="propertyValueId" class="formEditExclude" value="<?=$value->id?>" />
							<a class="saveRelationSubmit hide">Невидимая кнопка submit, нажимается скриптом.</a>

							<input style="<?= isset($noMeasures) ? 'width:150px' : ''?>" class="editRelation saveRelationImportant" name="value" value="<?=$object->getPropertyValueById($value->id)->value?>" placeholder="Значение" type="text" />
							<?if(!isset($noMeasures)):?>
							<select class="measurements saveRelationImportant" name="measureId" data-notJumpStep="true">
								<? foreach( $value->getMeasuresByCategory() as $measure ): ?>
								<option value="<?=$measure->id?>" <?=$object->getPropertyValueById($value->id)->measureId==$measure->id?'selected=selected':''?> ><?=$measure->getShortName()?></option>
								<? endforeach; ?>
							</select>
							<?endif?>
						</div>
					</div>
					<div class="propertyDetails editProperty hide">
						<div class="propertyValue">
							<input
								type="text"
								name="value"
								value="<?=$value->getValue()?>"
								class="editPropertyValue formEditExclude"
								data-action="/admin/properties/editPropertyValue/"
								data-post="&id=<?=$value->id?>"
							/>
							<select
								name="measureCategoryId"
								class="measurementsCategories formEditExclude"
								data-action="/admin/properties/changeMeasureCategoryInValue/"
								data-post="&id=<?=$value->id?>"
							>
								<? foreach($measures->getCategories() as $measure): ?>
									<option value="<?=$measure->id?>" <?=($value->measureCategoryId==$measure->id)?'selected=selected':''?>><?=$measure->name?></option>
								<? endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			<? endforeach ?>
			<table class="notSortable hide deletePropertyValue" data-confirm="Объект будет удален!">
				<tr>
					<td></td>
					<td>Перемести сюда чтобы удалить</td>
				</tr>
			</table>
			<table class="notSortable addPropertyValue actions">
				<tr>
					<td></td>
					<td>
						<input
							style="width: 120px;"
							name="value"
							type="text"
							value=""
							class="newProperty formEditExclude transformer"
							data-action="/admin/properties/addPropertyValue/"
							data-post="&propertyId=<?=$property->id?>"
							data-default="Новое значение"
						/>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<? endforeach; ?>
<!--	<div class="propertiesParts addingMode notSortable">
		<input
			type="text"
			name="name"
			class="newPropertyPart formEditExclude"
			data-action="/admin/properties/add/"
			data-default="Новый раздел"
			data-post="&additionalCategories[]=1"
		/>
	</div>-->
	<div class="propertiesParts removeMode hide notSortable">
		Чтобы удалить раздел переместите его сюда
	</div>
	<div class="clear"></div>
</div>
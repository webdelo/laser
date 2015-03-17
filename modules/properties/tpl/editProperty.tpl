<script type="text/javascript" src="/modules/parameters/js/additionalCategories.js"></script>
<script type="text/javascript" src="/admin/js/jquery/multi-select/multi-select.js"></script>
<link rel="stylesheet" type="text/css" href="/admin/js/jquery/multi-select/multi-select.css" />
<script type="text/javascript" src="/core/i18n/js/langFieldWrapper.js"></script>

<div class="main_edit property">
	<input type="hidden" class='objectId' name="id" value="<?=$property->id?>" />
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="title" width="180">Имя:</td>
					<td class="title">Алиас:</td>
					<td class="title">Категории:</td>
				</tr>
				<tr>
					<td>
						<? \core\i18n\LangFieldWrapper::printInputs($property, 'getName', 'name') ?>
					</td>
					<td><input type="text" size="20" name="alias" value="<?=$property->alias?>"/></td>
					<td>
						<select name="additionalCategories[]" multiple="multiple" class="additionalCategories" >
						<? if ($objects->getMainCategories()->count()): foreach($objects->getMainCategories() as $categoryObject): ?>
							<option value="<?=$categoryObject->id?>" <?=(in_array($categoryObject->id, $object->getAdditionalCategoriesArray()?$object->getAdditionalCategoriesArray():array())) ? 'selected' : ''; ?>><?=$categoryObject->getName()?></option>
							<?php if ($categoryObject->getChildren()): foreach($categoryObject->getChildren() as $children):?>
							<option value="<?=$children->id?>" <?=(in_array($children->id, $object->getAdditionalCategoriesArray()?$object->getAdditionalCategoriesArray():array())) ? 'selected' : ''; ?>><?=$children->getName()?></option>
								<?php if ($children->getChildren()): foreach($children->getChildren() as $children2):?>
							<option value="<?=$children2->id?>" <?=(in_array($children2->id, $object->getAdditionalCategoriesArray()?$object->getAdditionalCategoriesArray():array())) ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children2->getName()?></option>
								<?php endforeach; endif;?>
							<?php endforeach; endif;?>
						<?php endforeach; endif;?>
						</select>
					</td>
				</tr>
			</table>
			<br/>
			<? if( $property->id ): ?>
				<div class="propertyValues">
					<p class="title">Возможные значения для "<?=$property->getName()?>":</p>
					<script type="text/javascript" src="/modules/properties/js/properties.js"></script>
					<div class="propertyValuesBlock" data-source="/admin/properties/ajaxGetPropertiesValuesBlock/<?=$property->id?>">
						<?=$this->getPropertyValuesBlock($property->id)?>
					</div>
				</div>
			<? endif; ?>
		</div>
	</div><!--main_param-->
	<div class="dop_param">
		<div class="col_in">
			<p class="title">Дополнительные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description"><?=$property->description?></textarea>
				</tr>
				<tr>
					<td class="first">Изображение:</td>
					<td><input type="text" name="imagePath" value="<?=$property->getImagePath(); ?>" /></td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
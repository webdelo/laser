<script type="text/javascript" src="/modules/parameters/js/additionalCategories.js"></script>
<script type="text/javascript" src="/admin/js/jquery/multi-select/multi-select.js"></script>
<link rel="stylesheet" type="text/css" href="/admin/js/jquery/multi-select/multi-select.css" />
<script type="text/javascript" src="/core/i18n/js/langFieldWrapper.js"></script>

<div class="main_edit parameter">
	<input type="hidden" class='objectId' name="id" value="<?=$parameter->id?>" />
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Имя:</td>
					<td>
						<? \core\i18n\LangFieldWrapper::printInputs($parameter, 'getName', 'name') ?>
					</td>
				</tr>
				<tr>
					<td class="first">Alias:</td>
					<td>
						
						<input type="text" size="20" name="alias" value="<?=$parameter->alias?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Метод установки параметра к объекту:</td>
					<td>
						<select name="chooseMethodId">
							<? if ($chooseMethods): foreach($chooseMethods as $chooseMethod): ?>
							<option value="<?=$chooseMethod->id?>" <?= $chooseMethod->id==$parameter->chooseMethodId ? 'selected' : ''?>><?=$chooseMethod->name?></option>
							<? endforeach; endif ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Категории:</td>
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
			<p class="title">Дополнительные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="10"><?=$parameter->description?></textarea>
				</tr>
				<tr>
					<td class="first">Изображение:</td>
					<td><input type="text" name="imagePath" value="<?=$parameter->imagePath; ?>" /></td>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="dop_param">
		<div class="col_in">
			<? if( $parameter->id ): ?>
				<div class="parameterValues">
					<p class="title">Возможные значения для "<?=$parameter->getName()?>": </p>
					<script type="text/javascript" src="/modules/parameters/js/parameters.js"></script>
					<div class="parameterValuesBlock" data-source="/admin/parameters/ajaxGetParametersValuesBlock/<?=$parameter->id?>">
						<?=$this->getParameterValuesBlock($parameter->id)?>
					</div>
				</div>
			<? endif; ?>
		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
<? if (!isset($this->getGET()['categoryId'])): ?>
	Вы можете назначить характеристики одновременно, только одной категории товаров. <br />
	Пожалуйста выберите категорию<br />
	<select class="filterGroupActionByCategory" name="categoryId">
		<option></option>
		<?php if ($objects->getMainCategories()->count() != 0): foreach($objects->getMainCategories() as $categoryObject):?>
		<option value="<?=$categoryObject->id?>" <?=($categoryObject->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
			<?php if ($categoryObject->getChildren()): foreach($categoryObject->getChildren() as $children):?>
			<option value="<?=$children->id?>" <?=($children->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
				<?php if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
				<option value="<?=$children2->id?>" <?=($children2->id==$this->getGET()['categoryId']) ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
				<?php endforeach; endif;?>
			<?php endforeach; endif;?>
		<?php endforeach; endif;?>
	</select>
<? else: ?>
	<? if ($parameters->count()===0): ?>
		Для выбраной категории не указана категория параметров 
		[ <a href="/admin/devices/category/<?=$this->getGET()->categoryId?>#categoriesParameters">указать категорию параметров</a> ]
	<? else: ?>
		<table>
		<? foreach($parameters as $parameter): ?>
		<tr>
			<td class="title">
				<?=$parameter->name?>
			</td>
			<td>
				<select name="parametersValues[<?=$parameter->id?>]" style="width:100px;">
					<option></option>
					<? foreach($parameter->getParameterValues() as $value): ?>
						<option value="<?=$value->id?>"><?=$value->getValue()?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<? endforeach; ?>
		</table>
		<button  class="ok groupArraySubmit">
			<span>ок</span>
		</button>
	<? endif; ?>
<? endif; ?>
<script type="text/javascript" src="/modules/catalog/js/selectBySelect.js"></script>
<script type="text/javascript" src="/modules/catalog/js/parametersRelation.js"></script>
<div class="catalogCategoryParameters">
	<div>
		Пожалуйста выберите категорию характеристик и затем <b>выберите</b> характеристики которые будут <b>"основными"</b> для данной категории товаров.
	</div>
	<table>
		<tr>
			<td class="title">Категория:</td>
		</tr>
		<tr>
			<td>
				<select 
					name="parametersCategoryId" 
					class="parametersCategories"
					data-action="/admin/devices/ajaxGetParametersSelect/?objectId=<?=$object->id?>"
					data-method="post"
					data-type="html"
					style="width: 150px;"
				>
					<option></option>
					<? foreach($parametersCategories as $category):?>
					<option value="<?=$category->id?>" <?=($object->getParametersCategory()->id===$category->id)?'selected':''?>><?=$category->name?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="title">Характеристики:</td>
		</tr>
		<tr>
			<td>
				<select class="parameters" name="parameters[]" multiple="multiple" style="width: 150px; height: 200px;">
					<?=$this->includeParametersSelect($parameters, $object)?>
				</select>
			</td>
		</tr>
	</table>
</div>
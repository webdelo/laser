<script type="text/javascript" src="/modules/catalog/js/selectBySelect.js"></script>
<script type="text/javascript" src="/modules/catalog/js/parametersRelation.js"></script>
<div class="catalogCategoryParameters">
	<table>
		<tr>
			<td class="title">Категория:</td>
		</tr>
		<tr>
			<td>
				<select 
					id="parametersCategoryId"
					name="parametersCategoryId" 
					class="parametersCategories"
					data-action="/admin/devices/ajaxGetParametersSelect/"
					data-method="post"
					data-type="html"
					style="width: 150px;"
				>
					<option></option>
					<? foreach($parametersCategories as $category):?>
					<option value="<?=$category->id?>"><?=$category->name?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="title">Характеристики [назначить основные]:</td>
		</tr>
		<tr>
			<td>
				<select class="parameters" name="parameters[]" multiple="multiple">
				</select>
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript" src="/modules/catalog/catalog/js/parametersAdding.js"></script>
<script type="text/javascript" src="/modules/catalog/catalog/js/parametersSave.js"></script>
<link rel="stylesheet" type="text/css" href="/modules/catalog/catalog/css/parameters.css" />
<div id="partsSortable" data-action="/admin/parameters/changePriority/">
	<? foreach( $parameters as $parameter ): ?>
	<div class="parametersParts" data-id="<?=$parameter->id?>">
		<h3>
			<input
				type="text"
				name="name"
				value="<?=$parameter->getName()?>"
				class="transformer formEditExclude editParameterPart"
				data-action="/admin/parameters/editField/"
				data-post="&id=<?=$parameter->id?>"
			/>
			<span class="additionalInfo actions">
				<span class="preText">Режим редактирования</span>
				<a href="#" class="editModeToParameterValuesEnable">включить</a>
				<a href="#" class="editModeToParameterValuesDisable hide">отключить</a>
				| <span class="preText">Можно выбрать</span>
				<select
					name="chooseMethodId"
					class="formEditExclude editParameterPartChooseMode"
					data-action="/admin/parameters/editField/"
					data-post="&id=<?=$parameter->id?>"
					data-default=""
				>
					<? foreach( $methods as $method ): ?>
					<option value="<?=$method->id?>" <?=($method->id==$parameter->chooseMethodId)?'selected="selected"':''?>><?=$method->title?></option>
					<? endforeach; ?>
				</select>
			</span>
		</h3>
		<div class="parametersSortable" data-action="/admin/parameters/changeParametersValuesPriority/">
			<? foreach($parameter->getParameterValues() as $value): ?>
			<table data-id="<?=$value->id?>">
				<tr>
					<td>
						<input
							id="<?=$value->id?>"
							class="parameterInput"
							type="<?=$parameter->getChooseMethod()->name?>"
							name="parametersValues[<?=$parameter->isCheckbox()?'':$parameter->alias?>]"
							value="<?=$value->id?>"
							<?=in_array($value->id, $object->getParametersArray())?'checked=checked':''?>
						/>
					</td>
					<td>
						<label for="<?=$value->id?>">
							<input
								type="text"
								name="value"
								value="<?=$value->getValue()?>"
								class="editParameterValue"
								data-action="/admin/parameters/editParameterValue/"
								data-post="&id=<?=$value->id?>"
							/>
						</label>
					</td>
				</tr>
			</table>
			<? endforeach ?>
			<table class="notSortable hide deleteParameterValue" data-confirm="Объект будет удален!">
				<tr>
					<td></td>
					<td>Перемести сюда чтобы удалить</td>
				</tr>
			</table>
			<table class="notSortable addParameterValue actions">
				<tr>
					<td></td>
					<td>
						<input
							style="width: 120px;"
							name="value"
							type="text"
							value=""
							class="newParameter formEditExclude transformer"
							data-action="/admin/parameters/addParameterValue/"
							data-post="&parameterId=<?=$parameter->id?>"
							data-default="Новое значение"
						/>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<? endforeach; ?>
	<div class="parametersParts addingMode notSortable">
		<input
			type="text"
			name="name"
			class="newParameterPart formEditExclude"
			data-action="/admin/parameters/add/"
			data-default="Новый раздел"
			data-post="&additionalCategories[]=11"
		/>
	</div>
	<div class="parametersParts removeMode hide notSortable">
		Чтобы удалить раздел переместите его сюда
	</div>
	<div class="clear"></div>
</div>
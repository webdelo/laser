<link rel="stylesheet" type="text/css" href="/modules/parameters/css/parameters.css">
<script type="text/javascript" src="/admin/js/base/system/sorting.js"></script>
<script type="text/javascript" src="/modules/parameters/js/editFromViewMode.js"></script>
<table class="parameterValuesTable" data-sortUrlAction="/admin/parameters/changeParametersValuesPriority/?">
		<tr>
			<td>
			</td>
			<td colspan="2">
				<div
					class="newParameterForm"
					data-action="/admin/parameters/addParameterValue/"
					data-method="post"
				>
					<input type="hidden" name="parameterId" value="<?=$parameter->id?>" />
					
					<input name="value" type="text" value=""/>
					
					<a class="newParameterFormSubmit addInContent"></a>
					<div class="additionalInfo">
						<textarea class="transformer" data-default="добавить описание" name="description"></textarea>
					</div>
				</div>
			</td>
		</tr>
		<? $count=1; foreach( $parameter->getParameterValues() as $value ): ?>
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
					data-action="/admin/parameters/editParameterValue/" 
					data-post="&id=<?=$value->id?>"
				/>
				<div class="additionalInfo">
					<textarea 
						class="values transformer" 
						name="description" 
						data-action="/admin/parameters/editParameterValue/" 
						data-post="&id=<?=$value->id?>"
						data-default="добавить описание"
					><?=$value->description?></textarea>
				</div>
			</td>
			<td>
				<a 
					class="deleteParameterValue confirm pointer deleteInContent" 
					data-confirm="Удалить значение?" 
					data-action="/admin/parameters/deleteParameterValue/<?=$value->id?>/"
				>
				</a>
			</td>
		</tr>
		<? endforeach;?>
</table>
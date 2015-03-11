<div class="detailActionsBlock">
	<h3>Detail actions</h3>
	<form action="/admin/deliveries/groupCategoriesActions/" class="formDialog" method="post">
	<table>
		<tr>
			<td colspan="2">
				<select name="statusId">
					<option value="">- Status -</option>
					<?php foreach ($statuses as $status):?>
					<option value="<?=$status->id?>"><?=$status->name?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button class="remove button confirm" data-confirm="Remove categories?" data-action="/admin/deliveries/groupCategoriesRemove/" data-data="input[name*=group]" data-callback="postRemoveGroupCategories">remove</button>
			</td>
		</tr>
		<tr>
			<td><button class="save formDialogSubmit">Save</button></td>
			<td><button class="cancel closeForm">Cancel</button></td>
		</tr>
	</table>
	</form>
</div>
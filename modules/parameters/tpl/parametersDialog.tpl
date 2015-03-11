<div class="detailActionsBlock">
	<h3>Detail actions</h3>
	<form action="/admin/articles/groupActions/" class="formDialog" method="post" data-callback="reloadPage">
	<table>
		<tr>
			<td>
				<select name="categoryId">
					<option value="">- Category -</option>
					<?php foreach ($categories as $category):?>
					<option value="<?=$category->id?>"><?=$category->name?></option>
					<?php endforeach;?>
				</select>
			</td>
			<td>
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
				<button class="remove button confirm" data-confirm="Remove articles?" data-action="/admin/articles/groupRemove/" data-data="input[name*=group]" data-callback="postRemoveGroupArticle">remove</button>
			</td>
		</tr>
		<tr>
			<td><button class="save formDialogSubmit">Save</button></td>
			<td><button class="cancel closeForm">Cancel</button></td>
		</tr>
	</table>
	</form>
</div>
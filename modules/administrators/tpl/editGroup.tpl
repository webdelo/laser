<div class="main_edit">
	<div class="main_param">
		<div class="col_in">
			<table width="100%">
				<tr>
					<td colspan="2"><p class="title">Основные параметры:</p></td>
				</tr>
				<tr>
					<td class="first">Название:</td>
					<td><input type="text" name="name" value="<?=$object->name?>" /></td>
				</tr>
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="10"><?=$object->description?></textarea>
				</tr>
				<tr>
					<td class="td_right">Права:</td>
					<td>
						<div class="left tree checkboxes">
						<?=$tree?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="dop_param">
		<div class="col_in">
			<p class="title">Дополнительные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Alias:</td>
					<td>
						<input type="hidden" name="id" value="<?=$object->id?>" />
						<input size="20" name="alias" value="<?=$object->alias?>"/>
					</td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
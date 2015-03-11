<div class="main_edit">
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Имя:</td>
					<td>
						<?=$object->id ? '<input type="hidden" name="id" value="'.$object->id.'"/>' : '';?>
						<input type="text" name="name" value="<?=$object->name?>" />
					</td>
				</tr>
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="10"><?=$object->description?></textarea>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="dop_param">
		<div class="col_in">
			<p class="title">Дополнительные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Status:</td>
					<td>
						<select name="statusId" style="width:150px;">
							<?if ($statuses): foreach($statuses as $status):?>
							<option value="<?=$status->id?>" <?= $status->id==$object->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дата:</td>
					<td><input class="date" name="date" title="Date" value="<?=$object->date?>"/></td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td><input name="priority" value="<?=$object->priority; ?>" /></td>
				</tr>
				<tr>
					<td class="first">Процент обналички:</td>
					<td><input name="cashRate" value="<?=$object->cashRate?>" /></td>
				</tr>
				<tr>
					<td class="first">Поставщик в:</td>
					<td>
                                            <ul>
                                            <? foreach($modules as $module): ?>
                                                <li><input class="checkbox" id="module_<?=$module->id?>" type="checkbox" name="modulesDomain[]" value="<?=$module->id?>" <?=(isset($object->modulesDomainArray))?(in_array($module->id, $object->modulesDomainArray)) ? 'checked' : '':''; ?> /> <label for="module_<?=$module->id?>"> - <?=$module->name?> </label></li>
                                            <? endforeach; ?>
                                            </ul>
                                        </td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
<div class="main_edit">
    <input type='hidden' class='objectId' value='<?=$measure->id?>'>
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Название:</td>
					<td><input type="text" name="name" value="<?=$measure->name?>" /></td>
				</tr>
				<tr>
					<td class="first">Короткое название:</td>
					<td><input type="text" name="shortName" class="short" value="<?=$measure->shortName?>" /></td>
				</tr>
				<tr>
					<td class="first">Варианты:</td>
					<td>
						<input type="text" name="declension1" class="short" value="<?=$measure->declension1?>" placeholder="квартира" />
						<input type="text" name="declension2" class="short" value="<?=$measure->declension2?>" placeholder="квартиры" />
						<input type="text" name="declension3" class="short" value="<?=$measure->declension3?>" placeholder="квартир" />
						<div class="additionalInfo">
							<? if ( $measure->declension1 && $measure->declension2 && $measure->declension3 ): ?>
								Варианты: <?=$measure->declension1?>, <?=$measure->declension2?>, <?=$measure->declension3?> <br/>
								результат: 1 <?=$measure->getDeclension(1)?>, 2 <?=$measure->getDeclension(2)?>, 5 <?=$measure->getDeclension(5)?>
							<? else: ?>
								Варианты: квартира, квартиры, квартир <br />
								результат: 1 квартира, 2 квартиры, 5 квартир
							<? endif; ?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="first">Категория:</td>
					<td>
						<select name="categoryId" style="width:250px;">
							<option></option>
							<? if ($mainCategories->count()): foreach($mainCategories as $categoryObject):?>
							<option value="<?=$categoryObject->id?>" <?= $categoryObject->id==$measure->categoryId ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
								<?if ($categoryObject->getChildren() != NULL): foreach($categoryObject->getChildren() as $children):?>
								<option value="<?=$children->id?>" <?= $children->id==$measure->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
									<? if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
									<option value="<?=$children2->id?>" <?= $children2->id==$measure->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
									<?endforeach; endif?>
								<?endforeach; endif?>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="20"><?=$measure->description?></textarea></td>
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
						<input type="hidden" name="id" value="<?=$measure->id?>" />
						<input size="20" name="alias" value="<?=$measure->alias?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Status:</td>
					<td>
						<select name="statusId" style="width:150px;">
							<?if ($statuses): foreach($statuses as $status):?>
							<option value="<?=$status->id?>" <?= $status->id==$measure->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дата:</td>
					<td><input class="date" name="date" title="Date" value="<?=$measure->date?>"/></td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td><input name="priority" value="<?=$measure->priority; ?>" /></td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
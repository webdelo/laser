<div class="main_edit">
    <input type='hidden' class='objectId' value='<?=$fabricator->id?>'>
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Имя:</td>
					<td><input type="text" name="name" value="<?=$fabricator->name?>" /></td>
				</tr>
				<tr>
					<td class="first">Заголовок (h1):</td>
					<td><input type="text" name="h1" value="<?=$fabricator->h1?>" /></td>
				</tr>
				<tr>
					<td class="first">Категория (страна):</td>
					<td>
						<select name="categoryId" style="width:250px;">
							<option></option>
							<? if ($mainCategories->count()): foreach($mainCategories as $categoryObject):?>
							<option value="<?=$categoryObject->id?>" <?= $categoryObject->id==$fabricator->categoryId ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
								<?if ($categoryObject->getChildren() != NULL): foreach($categoryObject->getChildren() as $children):?>
								<option value="<?=$children->id?>" <?= $children->id==$fabricator->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
									<? if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
									<option value="<?=$children2->id?>" <?= $children2->id==$fabricator->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
									<?endforeach; endif?>
								<?endforeach; endif?>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дата основания:</td>
					<td><input type="text" name="foundDate" value="<?=$fabricator->foundDate?>" style="width: 238px" />
				</tr>
				<tr>
					<td class="first">Meta Title:</td>
					<td><input type="text" name="metaTitle" value="<?=$fabricator->metaTitle?>" />
				</tr>
				<tr>
					<td class="first">Meta Description:</td>
					<td><input type="text" name="metaDescription" value="<?=$fabricator->metaDescription?>" /></td>
				</tr>
				<tr>
					<td class="first">Meta Keywords:</td>
					<td><input type="text" name="metaKeywords" value="<?=$fabricator->metaKeywords?>" /></td>
				</tr>
				<tr>
					<td class="first">Текст для шапки сайта:</td>
					<td><input type="text" name="headerText" value="<?=$fabricator->headerText?>" /></td>
				</tr>
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="10"><?=$fabricator->description?></textarea>
				</tr>
				<tr>
					<td class="first">Текст:</td>
					<td><textarea name="text" cols="95" rows="20"><?=$fabricator->text?></textarea></td>
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
						<input type="hidden" name="id" value="<?=$fabricator->id?>" />
						<input size="20" name="alias" value="<?=$fabricator->alias?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Status:</td>
					<td>
						<select name="statusId" style="width:150px;">
							<?if ($statuses): foreach($statuses as $status):?>
							<option value="<?=$status->id?>" <?= $status->id==$fabricator->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дата:</td>
					<td><input class="date" name="date" title="Date" value="<?=$fabricator->date?>"/></td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td><input name="priority" value="<?=$fabricator->priority; ?>" /></td>
				</tr>
			</table>
			<p class="title">Sitemap XML:</p>
			<table width="100%">
				<tr>
					<td class="first">Посл. обновление:</td>
					<td>
						<input class="date" name="lastUpdateTime" title="Время последнего обновления" value="<?= \core\utils\Dates::toSimpleDate($fabricator->getLastUpdateTime())?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td>
						<select name="sitemapPriority">
<?foreach (\core\seo\sitemap\SitemapValues::getPriorityValues() as $value):?>
							<option value="<?=$value?>" <?=($fabricator->getSitemapPriority() == $value) ? 'selected' : ''?>><?=$value?></option>
<?endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Частота обновлений:</td>
					<td>
						<select name="changeFreq">
<?foreach (\core\seo\sitemap\SitemapValues::getChangeFreqValues() as $key => $value):?>
							<option value="<?=$key?>" <?=($fabricator->getChangeFreq() == $key) ? 'selected' : ''?>><?=$value?></option>
<?endforeach;?>
						</select>
					</td>
				</tr>
			</table>

		</div>
	</div><!--dop_param-->
	<?$this->includeTemplate('images')?>
	<div class="clear"></div>
</div><!--main_edit-->
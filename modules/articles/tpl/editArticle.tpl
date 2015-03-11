<div class="main_edit">
    <input type='hidden' class='objectId' value='<?=$article->id?>'>
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Имя:</td>
					<td><input type="text" name="name" value="<?=$article->name?>" /></td>
				</tr>
				<tr>
					<td class="first">Заголовок (h1):</td>
					<td><input type="text" name="h1" value="<?=$article->h1?>" /></td>
				</tr>
				<tr>
					<td class="first">Категория:</td>
					<td>
						<select name="categoryId" style="width:250px;">
							<option></option>
							<? if ($mainCategories->count()): foreach($mainCategories as $categoryObject):?>
							<option value="<?=$categoryObject->id?>" <?= $categoryObject->id==$article->categoryId ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
								<?if ($categoryObject->getChildren() != NULL): foreach($categoryObject->getChildren() as $children):?>
								<option value="<?=$children->id?>" <?= $children->id==$article->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
									<? if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
									<option value="<?=$children2->id?>" <?= $children2->id==$article->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
									<?endforeach; endif?>
								<?endforeach; endif?>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Meta Title:</td>
					<td><input type="text" name="metaTitle" value="<?=$article->metaTitle?>" />
				</tr>
				<tr>
					<td class="first">Meta Description:</td>
					<td><input type="text" name="metaDescription" value="<?=$article->metaDescription?>" /></td>
				</tr>
				<tr>
					<td class="first">Meta Keywords:</td>
					<td><input type="text" name="metaKeywords" value="<?=$article->metaKeywords?>" /></td>
				</tr>
				<tr>
					<td class="first">Текст для шапки сайта:</td>
					<td><input type="text" name="headerText" value="<?=$article->headerText?>" /></td>
				</tr>
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="10"><?=$article->description?></textarea>
				</tr>
				<tr>
					<td class="first">Текст:</td>
					<td><textarea name="text" cols="95" rows="20"><?=$article->text?></textarea></td>
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
						<input type="hidden" name="id" value="<?=$article->id?>" />
						<input size="20" name="alias" value="<?=$article->alias?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Status:</td>
					<td>
						<select name="statusId" style="width:150px;">
							<?if ($statuses): foreach($statuses as $status):?>
							<option value="<?=$status->id?>" <?= $status->id==$article->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дата:</td>
					<td><input class="date" name="date" title="Date" value="<?=$article->date?>"/></td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td><input name="priority" value="<?=$article->priority; ?>" /></td>
				</tr>
			</table>
			<p class="title">Sitemap XML:</p>
			<table width="100%">
				<tr>
					<td class="first">Посл. обновление:</td>
					<td>
						<input class="date" name="lastUpdateTime" title="Время последнего обновления" value="<?= \core\utils\Dates::toSimpleDate($article->getLastUpdateTime())?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td>
						<select name="sitemapPriority">
<?foreach (\core\seo\sitemap\SitemapValues::getPriorityValues() as $value):?>
							<option value="<?=$value?>" <?=($article->getSitemapPriority() == $value) ? 'selected' : ''?>><?=$value?></option>
<?endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Частота обновлений:</td>
					<td>
						<select name="changeFreq">
<?foreach (\core\seo\sitemap\SitemapValues::getChangeFreqValues() as $key => $value):?>
							<option value="<?=$key?>" <?=($article->getChangeFreq() == $key) ? 'selected' : ''?>><?=$value?></option>
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
<?if ($object->id):?><input type="hidden" name="id" value="<?=$object->id?>"/><?endif;?>
<div class="main_edit">
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Имя:</td>
					<td><input type="text" name="name" value="<?=$object->getName()?>" /></td>
				</tr>
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="10"><?=$object->description?></textarea>
				</tr>
				<tr>
					<td class="first">Текст:</td>
					<td><textarea name="text" cols="95" rows="40" style="height: 440px;"><?=$object->text?></textarea>
				</tr>
				<tr>
					<td class="first">Цена доставки:</td>
					<td><input type="text" name="deliveryPrice" placeholder="Укажите цену доставки если она отличается от стандартной."/>
				</tr>
				<tr>
					<td class="first">Деффекты и особенности изделия:</td>
					<td><textarea name="defects" cols="95" rows="7"><?=$object->defects?></textarea>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="dop_param">
		<div class="col_in">
			<p class="title">Дополнительные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Код:</td>
					<td><input name="code" value="<?=$object->getCode()?>" /></td>
				</tr>
				<tr>
					<td class="first">Статус:</td>
					<td>
						<select name="statusId" style="width:150px;">
							<?if ($objects->getStatuses()): foreach($objects->getStatuses() as $status):?>
							<option value="<?=$status->id?>" style="color:<?=$status->color?>; font-weight:bold;" <?= $status->id==$object->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Категория:</td>
					<td>
						<select name="categoryId" style="width:150px;">
						<option></option>
						<?if ($objects->getMainCategories()->count()): foreach($objects->getMainCategories() as $categoryObject):?>
						<option value="<?=$categoryObject->id?>" <?=($categoryObject->id==$object->categoryId) ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
							<?php if ($categoryObject->getChildren()): foreach($categoryObject->getChildren() as $children):?>
							<option value="<?=$children->id?>" <?=($children->id==$object->categoryId) ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
								<?php if ($children->getChildren()): foreach($children->getChildren() as $children2):?>
								<option value="<?=$children2->id?>" <?=($children2->id==$object->categoryId) ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
								<?php endforeach; endif;?>
							<?php endforeach; endif;?>
						<?php endforeach; endif;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Модель:</td>
					<td><input name="model" value="<?=$object->model?>" /></td>
				</tr>
				<tr>
					<td class="first">Состояние:</td>
					<td>
						<select name="condition">
							<option value="1" <?=($object->condition == 1) ? 'selected' : '' ?>>Новая</option>
							<option value="2" <?=($object->condition == 2) ? 'selected' : '' ?>>Б/У</option>
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
			</table>

			<p class="title">Sitemap XML:</p>
			<table width="100%">
				<tr>
					<td class="first">Посл. обновление:</td>
					<td>
						<input class="date" name="lastUpdateTime" title="Время последнего обновления" value="<?= \core\utils\Dates::toSimpleDate( \core\utils\Dates::dateTimeToTimestamp($object->getLastUpdateTime()) )?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td>
						<select name="sitemapPriority">
<?foreach (\core\seo\sitemap\SitemapValues::getPriorityValues() as $value):?>
							<option value="<?=$value?>" <?=($object->getSitemapPriority() == $value) ? 'selected' : ''?>><?=$value?></option>
<?endforeach;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Частота обновлений:</td>
					<td>
						<select name="changeFreq">
<?foreach (\core\seo\sitemap\SitemapValues::getChangeFreqValues() as $key => $value):?>
							<option value="<?=$key?>" <?=($object->getChangeFreq() == $key) ? 'selected' : ''?>><?=$value?></option>
<?endforeach;?>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<div style="float : left;">
		<?$this->includeTemplate('images')?>
	</div>
	<div class="clear"></div>
</div><!--main_edit-->
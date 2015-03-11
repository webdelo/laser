<div class="main_edit">
	<script type="text/javascript" src="/modules/catalog/js/additionalCategories.js"></script>
	<script type="text/javascript" src="/admin/js/jquery/multi-select/multi-select.js"></script>
	<link rel="stylesheet" type="text/css" href="/admin/js/jquery/multi-select/multi-select.css" />
	<script type="text/javascript" src="/modules/orders/js/autosuggest/jquery.autoSuggest.js"></script>
	<script type="text/javascript" src="/modules/catalog/offers/js/autosuggest/autosuggestOffer.js"></script>
	<script type="text/javascript" src="/modules/catalog/offers/js/autosuggest/autosuggestListPageOffer.js"></script>
	<link rel="stylesheet" type="text/css" href="/modules/orders/css/autoSuggest.css" />
	<script type="text/javascript" src="/modules/catalog/offers/js/offerHandler.js"></script>
	<link rel="stylesheet" type="text/css" href="/modules/catalog/offers/css/style.css" />
	<input type="hidden" class="objectId" value="<?=$offer->id?>">
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Название<br />товара по акции:</td>
					<td>
						<input type="text" name="name" value="<?=$object->name?>">
					</td>
				</tr>
				<tr>
					<td class="first">Заголовок:</td>
					<td>
						<input type="text" name="title" value="<?=$object->title?>">
					</td>
				</tr>
				<tr>
					<td class="first">Тип:</td>
					<td>
						<select name="type" style="width:150px;">
							<option></value>
							<?if($objects->getTypes()):  foreach ($objects->getTypes() as $key=>$value):?>
							<option value="<?=$value?>" <?= $object->type==$value ? 'selected': ''?>><?=$key?></value>
							<?endforeach; endif;?>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input class="date type" name="time" value="<?=$offer->time?>" style="display:<?= $object->type=='time' ? 'inline-block' : 'none'?>;"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="text" class="type" name="quantity" value="<?=$object->quantity?>" style="width:150px; display:<?= $object->type=='quantity' ? 'inline-block' : 'none'?>;" />
					</td>
				</tr>
				<tr>
					<td class="first">Товар:</td>
					<td>
						<input type="text" class="inputGood">
						<input type="hidden" class="goodId" value="<?= $object->goodId ? $object->goodId : ''?>">
						<input type="hidden" class="goodName" value="<?= $object->goodId ? \modules\catalog\CatalogFactory::getInstance()->getGoodById($object->goodId)->getName() : ''?>">
						<img class="inputGoodLoader" style="margin: 5px 0px -10px 140px; display: none;" src="/images/loaders/loading-small.gif" />
					</td>
				</tr>
				<tr class="offerGoodTalbeCell" <?= $object->goodId ? '' : 'style="display: none;"'?>>
					<td class="first"></td>
					<td>
						<div class="offerGoodTalbePlace">
							<?if($object->goodId)
								$this->getGoodTable($object->goodId);
							?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="first">Скидка:</td>
					<td><input type="text" name="discount" value="<?=$object->discount?>" style="width:150px;" /></td>
				</tr>
				<tr>
					<td class="first">Заметки:</td>
					<td><textarea name="description" cols="95" rows="5"><?=$offer->description?></textarea></td>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="dop_param" style="width: 325px">
		<div class="col_in">
			<p class="title">Дополнительные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Код:</td>
					<td><input name="code" value="<?=$object->getCode()?>" /></td>
				</tr>
				<tr>
					<td class="first">Модуль:</td>
					<td>
						<select name="moduleId" style="width:150px;">
							<option></option>
							<?if ($this->getController('ModulesDomain')->getModules()->current()): foreach($this->getController('ModulesDomain')->getModules() as $module):?>
							<option value="<?=$module->id?>" <?= $module->id==$offer->moduleId ? 'selected' : ''?>><?=$module->name?></option>
							<?endforeach; endif?>
						</select>
					<td>
				</tr>
				<tr>
					<td class="first">Домен:</td>
					<td>
						<select name="domain" style="width:150px;">
							<option></option>
							<?if ($this->getController('ModulesDomain')->getAllDomains()): foreach($this->getController('ModulesDomain')->getAllDomains() as $domain=>$value):?>
							<option value="<?=$domain?>" <?= $domain==$offer->domain ? 'selected' : ''?>><?=$domain?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Статус:</td>
					<td>
						<input type="hidden" name="id" value="<?=$offer->id?>" />
						<select name="statusId" style="width:150px; <?= $this->isAuthorisatedUserAnManager() ? 'boffer: 3px solid blue;' : ''?>">
							<?if ($offers->getStatuses()): foreach($offers->getStatuses() as $status):?>
							<option value="<?=$status->id?>" <?= $status->id==$offer->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Категория:</td>
					<td>
						<select name="categoryId" style="width:150px;">
							<option></option>
							<? if ($mainCategories->count()): foreach($mainCategories as $categoryObject):?>
							<option value="<?=$categoryObject->id?>" <?= $categoryObject->id==$offer->categoryId ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
								<?if ($categoryObject->getChildren() != NULL): foreach($categoryObject->getChildren() as $children):?>
								<option value="<?=$children->id?>" <?= $children->id==$offer->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;|-&nbsp;<?=$children->name?></option>
									<? if ($children->getChildren() != NULL): foreach($children->getChildren() as $children2):?>
									<option value="<?=$children2->id?>" <?= $children2->id==$offer->categoryId ? 'selected' : ''; ?>>&nbsp;&nbsp;&nbsp;&nbsp;|-&nbsp;<?=$children2->name?></option>
									<?endforeach; endif?>
								<?endforeach; endif?>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дополнительные категории:</td>
					<td>
						<select name="additionalCategories[]" multiple="multiple" class="additionalCategories" style="width:150px;">
							<?if ($objects->getMainCategories()->count()): foreach($objects->getMainCategories() as $categoryObject):?>
								<?if($categoryObject->id != $object->categoryId):?>
								<option value="<?=$categoryObject->id?>" <?=(in_array($categoryObject->id, $object->additionalCategoriesArray)) ? 'selected' : ''; ?>><?=$categoryObject->name?></option>
								<?endif;?>
							<?php endforeach; endif;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дата:</td>
					<td><input class="date" name="date" title="Date" value="<?=$offer->date?>"/></td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td><input class="date" name="priority" title="priority" value="<?=$offer->priority?>"/></td>
				</tr>
				<tr class="hide">
					<td class="first">Приоритет:</td>
					<td><input name="priority" value="<?=$offer->priority; ?>" /></td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<?$this->includeTemplate('images')?>
	<div class="clear"></div>
</div><!--main_edit-->
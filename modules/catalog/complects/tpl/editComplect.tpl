<div class="main_edit">
	<script type="text/javascript" src="/modules/catalog/complects/js/complectHandler.js"></script>
	<script type="text/javascript" src="/modules/catalog/complects/js/complect.class.js"></script>
	<script type="text/javascript" src="/js/ajaxLoader.class.js"></script>
	<script type="text/javascript" src="/modules/catalog/complects/js/autosuggest/autosuggest.js"></script>
	<script type="text/javascript" src="/modules/catalog/complects/js/autosuggest/jquery.autoSuggest.js"></script>
	<link rel="stylesheet" type="text/css" href="/modules/catalog/complects/css/autoSuggest.css" />
	<input type="hidden" class="objectId" value="<?=$complect->id?>">
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Название:</td>
					<td><input type="text" name="name" value="<?=$complect->getName()?>" /></td>
				</tr>
				<tr>
					<td class="first">Описание:</td>
					<td><textarea name="description" cols="95" rows="5"><?=$complect->description?></textarea></td>
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
					<td><input name="code" value="<?=$complect->getCode()?>" /></td>
				</tr>
				<tr>
					<td class="first">Модуль:</td>
					<td>
						<select name="moduleId" style="width:150px;">
							<option></option>
							<?if ($this->getController('ModulesDomain')->getModules()->current()): foreach($this->getController('ModulesDomain')->getModules() as $module):?>
							<option value="<?=$module->id?>" <?= $module->id==$complect->moduleId ? 'selected' : ''?>><?=$module->name?></option>
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
							<option value="<?=$domain?>" <?= $domain==$complect->domain ? 'selected' : ''?>><?=$domain?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Статус:</td>
					<td>
						<input type="hidden" name="id" value="<?=$complect->id?>" />
						<select name="statusId" style="width:150px; <?= $this->isAuthorisatedUserAnManager() ? 'bcomplect: 3px solid blue;' : ''?>">
							<?if ($complects->getStatuses()): foreach($complects->getStatuses() as $status):?>
							<option value="<?=$status->id?>" <?= $status->id==$complect->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Дата:</td>
					<td><input class="date" name="date" title="Date" value="<?=$complect->date?>"/></td>
				</tr>
				<tr>
					<td class="first">Приоритет:</td>
					<td><input name="priority" value="<?=$complect->priority; ?>" /></td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
<div class="main_edit">
	<input type='hidden' class='objectId' value='<?=$locationCity->id?>'>
	<script type="text/javascript" src="/core/i18n/js/langFieldWrapper.js"></script>
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Название:</td>
					<td>
						<? \core\i18n\LangFieldWrapper::printInputs($locationCity, 'getName', 'name') ?>
					</td>
				</tr>
				<tr>
					<td class="first">Алиас:</td>
					<td>
						<input type="hidden" name="id" value="<?=$locationCity->id?>" />
						<input size="40" name="alias" value="<?=$locationCity->alias?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Страна:</td>
					<td>
						<select name="countryId" style="width: 260px;">
							<option></option>
							<?foreach($locationCities->getLocationsCountries() as $locationCountry):?>
							<option value="<?=$locationCountry->id?>" <?= isset($locationCity->id) ? ($locationCountry->id==$locationCity->countryId ? 'selected' : '') : $locationCountry->id==$bulgaryCountry->id ? 'selected' : ''?>><?=$locationCountry->getName()?></option>
							<?endforeach?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="first">Регион:</td>
					<td>
						<div class="regionsBlocks" data-source="">
							<?=$this->getRegionSelect(isset($locationCity->id) ? $locationCity->countryId : $bulgaryCountry->id)?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="dop_param">
		<div class="col_in">
			<p class="title"></p>
			<table width="100%">
				<tr>
					<td class="first"></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
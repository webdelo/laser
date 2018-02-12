<div class="main_edit">
	<input type='hidden' class='objectId' value='<?=$locationRegion->id?>'>
	<script type="text/javascript" src="/core/i18n/js/langFieldWrapper.js"></script>
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Название:</td>
					<td>
						<? \core\i18n\LangFieldWrapper::printInputs($locationRegion, 'getName', 'name') ?>
					</td>
				</tr>
				<tr>
					<td class="first">Алиас:</td>
					<td>
						<input type="hidden" name="id" value="<?=$locationRegion->id?>" />
						<input size="40" name="alias" value="<?=$locationRegion->alias?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">Страна:</td>
					<td>
						<select name="countryId" style="width: 260px;">
							<option></option>
							<?foreach($locationRegions->getLocationsCountries() as $locationCounry):?>
							<option value="<?=$locationCounry->id?>" <?= $locationCounry->id==$locationRegion->countryId ? 'selected' : ''?>><?=$locationCounry->getName()?></option>
							<?endforeach?>
						</select>
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
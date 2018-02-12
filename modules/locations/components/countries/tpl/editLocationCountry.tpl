<div class="main_edit">
	<input type='hidden' class='objectId' value='<?=$locationCountry->id?>'>
	<script type="text/javascript" src="/core/i18n/js/langFieldWrapper.js"></script>
	<div class="main_param">
		<div class="col_in">
			<p class="title">Основные параметры:</p>
			<table width="100%">
				<tr>
					<td class="first">Название:</td>
					<td>
						<? \core\i18n\LangFieldWrapper::printInputs($locationCountry, 'getName', 'name') ?>
					</td>
				</tr>
				<tr>
					<td class="first">Алиас:</td>
					<td>
						<input type="hidden" name="id" value="<?=$locationCountry->id?>" />
						<input size="40" name="alias" value="<?=$locationCountry->alias?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">alpha2:</td>
					<td>
						<input size="15" name="alpha2" value="<?=$locationCountry->alpha2?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">alpha3:</td>
					<td>
						<input size="15" name="alpha3" value="<?=$locationCountry->alpha3?>"/>
					</td>
				</tr>
				<tr>
					<td class="first">iso:</td>
					<td>
						<input size="15" name="iso" value="<?=$locationCountry->iso ? $locationCountry->iso : ''?>"/>
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
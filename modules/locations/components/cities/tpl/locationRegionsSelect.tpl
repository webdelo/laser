<?if($regions->count()):?>
<select name="regionId" style="width: 260px;">
	<option></option>
	<?foreach($regions as $locationRegion):?>
	<option value="<?=$locationRegion->id?>" <?= isset($locationCity) ? ($locationRegion->id==$locationCity->regionId ? 'selected' : '') : ''?>><?=$locationRegion->getName()?></option>
	<?endforeach?>
</select>
<?else:?>
	<select name="regionId" style="width: 260px;">
		<option></option>
	</select>
	<br />
	У данной страны нет регионов.
<?endif?>
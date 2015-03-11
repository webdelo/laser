<select name="seriaId" data-error-position="right" style="width:150px;">
	<option value="0">-- нет серии --</option>
	<?if (isset($serias) && $serias): foreach($serias as $seria):?>
	<option value="<?=$seria->id?>" <?= (isset($object)&&$seria->id==$object->seriaId) ? 'selected' : ''?>><?=$seria->getValue()?></option>
	<?endforeach; endif?>
</select>
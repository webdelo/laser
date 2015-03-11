<? foreach($parameters as $parameter): ?>
<option value="<?=$parameter->id?>" <?=((is_array($object->getParametersRelationArray()))?in_array($parameter->id, $object->getParametersRelationArray()):false) ? 'selected' : ''; ?>><?=$parameter->name?></option>
<? endforeach; ?>
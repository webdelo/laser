<table>
	<tr>
		<td>
			<a href="http://<?=$orderGood->getGood()->getGoodDomain()?><?=$this->isAuthorisatedUserAnManager() ? $orderGood->getGood()->getPath() : $orderGood->getGood()->getAdminUrl()?>" class="orderGoodDetailsModal" target="blank">
				<img src="<?=$orderGood->getGood()->getFirstPrimaryImage()->getImage('600x400')?>" />
			</a>
		</td>
	</tr>
	<tr>
		<td>
			<? if ( $this->isHasParametersOrProperties($orderGood) ): ?>
			<form class="goodEditForm" action="/admin/orders/editOrderComplectsGoodDetals/" data-post="&objectId=<?=$orderGood->id?>">
				<? foreach ( $parameters as $parameter ): ?>
				<div class="parameters">
					<h3><?=$parameter->name?></h3>
					<ul class="colors">
						<? foreach ( $parameter->getParameterValues() as $value ): ?>
						<li>
							<input
								id="color<?=$value->id?>"
								type="<?=$parameter->getChooseMethod()->name?>"
								name="parameters[<?=$parameter->isCheckbox()?'':$parameter->alias?>]"
								value="<?=$value->id?>"
								<?=in_array($value->id, $orderGood->getParametersArray())?'checked=checked':''?>
							/>
							<label for="color<?=$value->id?>">
								<span class="text"><?=$value->getValue()?></span>
								<span class="colorBlock" style="background-color:<?=$value->description?>;"></span>
							</label>
						</li>
						<? endforeach; ?>
					</ul>
				</div>
				<? endforeach; ?>
				<? foreach ( $properties as $parameter ): ?>
				<div class="properties">
					<h3><?=$parameter->name?></h3>
					<ul class="colors">
						<? foreach ( $parameter->getPropertyValues() as $value ): ?>
						<li>
							<div class="propertyRelation saveRelation" data-action="/admin/<?=$_REQUEST['controller']?>/ajaxEditComplectsPropertyRelation/">
								<input type="hidden" name="id" class="formEditExclude" value="<?=$orderGood->getPropertyValueById($value->id)->id?>" />
								<input type="hidden" name="ownerId" class="formEditExclude" value="<?=$orderGood->id?>" />
								<input type="hidden" name="propertyValueId" class="formEditExclude" value="<?=$value->id?>" />
								<a class="saveRelationSubmit hide">Невидимая кнопка submit, нажимается скриптом.</a>

								<input class="editRelation" name="value" value="<?=$orderGood->getPropertyValueById($value->id)->value?>" placeholder="<?=$value->value?>" type="text" />
								<select class="measurements <?= $value->getMeasuresByCategory()->current()->getShortName()=='&nbsp;' ? 'hide' : ''?>" name="measureId" data-notJumpStep="true" style="width: auto;">
									<? foreach( $value->getMeasuresByCategory() as $measure ): ?>
									<option value="<?=$measure->id?>" <?=$orderGood->getPropertyValueById($value->id)->measureId==$measure->id?'selected=selected':''?> >
											<?=$measure->getShortName()?>
									</option>
									<? endforeach; ?>
								</select>
							</div>
						</li>
						<? endforeach; ?>
					</ul>
				</div>
				<? endforeach; ?>
				<input type="hidden" class="goodEditFormSubmit" name="submit" value="1" />
			</form>
			<? else: ?>
			Для данного вида товаров не предусмотрены характеристики и свойства. <br>
			<a href="<?=$orderGood->getGood()->getAdminUrl()?>/">Посмотреть в каталоге</a>
			<? endif; ?>
		</td>
	</tr>
</table>
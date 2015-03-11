					<script type="text/javascript" src="<?=DIR_ADMIN_HTTP?>js/jquery/autosuggest/jquery.autoSuggest.js"></script>
					<link rel="stylesheet" type="text/css" href="<?=DIR_ADMIN_HTTP?>js/jquery/autosuggest/autoSuggest.css">
					<script type="text/javascript" src="/modules/catalog/devices/js/colors.js"></script>
					<link rel="stylesheet" type="text/css" href="/modules/catalog/devices/css/colors.css">
					<div class="main_edit">
						<div class="main_param">
							<p class="title">Цвет этой модели:</p>
							<table>								
								<tr>
									<td class="first">Выбрать:</td>
									<td>
										<div id="colorField" style="background-color: #<?=$object->color?>;"></div>
										<input id="colorInput" type="hidden" name="color" value="<?=$object->color?>" />
									</td>
								</tr>
								<tr>
									<td class="first">Подпись:</td>
									<td><input type="text" name="colorTitle" value="<?=$object->colorTitle?>" style="width: 305px;" /></td>
								</tr>
								<tr>
									<td colspan="2" style="padding: 0; margin: 0; padding-top: 10px;">
										<p class="title">
											Модели другого цвета: 
										</p>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="padding: 0; margin: 0;">
										Это устройство другого цвета:
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div class="associateDevice" data-action="/admin/devices/ajaxAssociateDevicesByColor/" data-method="post">
											<input type="hidden" name="goodId" value="<?=$object->id?>" />
											<input type="hidden" name="goodName" value="<?=$object->getName()?>" />
											<input class="otherColor" type="text" name="otherColor" />
											<input class="extraParams" type="hidden" name="extraParams" value="&categoryId=<?=$object->categoryId?>" />
											<a class="associateDeviceSubmit buttonInContent"><img src="/admin/images/buttons/save_object.png" alt=""> Связать</a>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="associatedDevices" data-source="/admin/devices/ajaxGetAssociatedDevicesTemplate/?objectId=<?=$object->id?>">
										<? if( $this->isNotNoop( $object->getColors() ) ): ?>
											<?=$this->getAssociatedDevicesTemplate($object)?>
										<? endif; ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="clear"></div>
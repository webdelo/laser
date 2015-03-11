									<h2>Себестоимость от партнёров:</h2>
										<div id="saveBasePrices" >
											<input type="hidden" name="priceId" value="<?=$price->id?>"/>
<?$basePrices = $price->getBasePrices();?>
<? foreach ($partners as $partner):?>
<?$basePrice = $basePrices->getBasePriceByPartnerId($partner->id);?>
											<div class="supplier-price">
												<div
													class="supplier"
													<?if($basePrice->history):?>
													title="<?=$basePrice->history?>"
													style="color: mediumSlateBlue; font-style: italic; cursor: pointer;"
													<?endif?>
												>
													<?=$partner->name?>
												</div>
												<div class="list-price">
													<input name="<?=$partner->id?>" type="text" value="<?=$basePrice->getBasePrice()?>"/>
													<span>руб</span>
												</div>
											</div>
<?endforeach;?>
										</div>
									<a class="save-list pointer" id="saveBasePricesButton">Сохранить</a>
									<a class="close-list pointer" id="closeBasePricesButton">Отменить</a>
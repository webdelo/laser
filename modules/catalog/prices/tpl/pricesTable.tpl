								<h2>Таблица цен:</h2>
								<table class="table-price">
									<tr class="top">
										<td class="number-price">Кол-во</td>
										<td class="oline-price">Цена на сайте / Старая цена</td>
										<td class="supply-price">Цена поставки</td>
										<td class="trash-price"><img src="/admin/images/bg/trash.png" alt="Удалить" /></td>
									</tr><!--top - end-->
<?foreach($object->getPrices() as $key => $price):?>
									<tr class="line">
										<td class="number-price"><?=$price->getQuantity()?> </td>
										<td class="oline-price">
											<div id="viewPrice_<?=$price->id?>">
												<?=$price->getPrice()?>
												<span>€</span> /
												<b><?=$price->getOldPrice()?> € (<i>-<?=$price->getDiscount()?> €</i>)</b>
												<?if( ! $this->isAuthorisatedUserAnManager()):?>
												<a  class="pointer editPriceButton" data-priceid="<?=$price->id?>"></a>
												<?endif?>
											</div>
											<div class="hide" id="editPrice_<?=$price->id?>">
												<input name="price_<?=$price->id?>" id="price_<?=$price->id?>" value="<?=$price->getPrice()?>"/>
												<span>€</span> /
												<b><input name="oldPrice_<?=$price->id?>" id="oldPrice_<?=$price->id?>" value="<?=$price->getOldPrice()?>"/> €
												<a class="save-oline-price pointer savePriceButton" data-priceid="<?=$price->id?>"></a>
											</div>
										</td>
										<td class="supply-price">
											<?$minBasePrice = $price->getBasePrices()->getMinBasePrice();?>
											<?if ($this->isNoop($minBasePrice)):?>
											<b>Нет предложений...</b>
											<?else:?>
											<i><?=$price->getBasePrices()->getMinBasePrice()->getBasePrice()?></i>
											<span>€</span>
											<b><?=$price->getBasePrices()->getMinBasePrice()->getPartner()->name?></b>
											<?endif;?>
											<a class="pointer roster viewBasePriceLink" data-id="<?=$price->id?>" title="Показать цены поставщиков"></a>
<!--											<a class="pointer edit"></a>
											<a class="pointer add"></a>-->
										</td>
										<td class="delete">
											<a class="pointer deletePriceLink" data-id="<?=$price->id?>" title="Удалить цену"></a>
										</td>
									</tr><!--line - end-->
<?  endforeach;?>
								</table><!--table-price - end-->
								<div class="list-manufacturers hide" id="basePrices"></div>
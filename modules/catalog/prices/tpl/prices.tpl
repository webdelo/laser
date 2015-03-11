					<script type="text/javascript" src="/modules/catalog/prices/js/pricesHandler.js"></script>
					<script type="text/javascript" src="/modules/catalog/prices/js/prices.class.js"></script>
					<input type="hidden" name="objectId" value="<?=$object->id?>" id="objectId"/>
<?//$this->getController('prices')->getPriceCalculator($object->id);?>
                    <div class="price">
						<div class="price-and-add">
							<div id="pricesTableBlock">
<?$this->getController('prices')->getPricesTable($object->id);?>
							</div>
<?if( ! $this->isAuthorisatedUserAnManager()):?>
<?$this->getController('prices')->getAddPriceBlock($object->id);?>
<?endif?>
							<div class="clear"></div>
						</div>
						<div class="numbers-price" id="availablePrices">
<?$this->getController('availability')->printAvailabilityBlock($object->id);?>
						</div>
						<div class="clear"></div>
                    </div><!--price - end-->
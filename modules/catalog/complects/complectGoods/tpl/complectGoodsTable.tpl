<div class="goodsTable">
	<p class="title">Список товаров:</p>
	<table class="goodsList" style="width:100%">
		<tr class="top">
			<td>#</td>
			<td class="bcomplectLeft" colspan="2">Товар</td>
			<td class="bcomplectLeft">Основной</td>
			<td class="bcomplectLeft">Скидка / Цена на сайте</td>
			<td class="bcomplectLeft">Количество</td>
			<td class="bcomplectLeft">Стоимость</td>
			<td class="bcomplectLeft"><img src="/admin/images/bg/trash.png" alt="Удалить" /></td>
		</tr>
		<? $i=1; foreach($complectGoods as $good):?>
		<tr class="line" data-id="<?=$good->id?>">
			<td><?=$i?></td>
			<td>
				<a href="<?=$good->getGood()->getFirstPrimaryImage()->getImage('800x600')?>" class="lightbox noNextPrev">
					<img src="<?=$good->getGood()->getFirstPrimaryImage()->getImage('40x40')?>" />
				</a>
				<div class="normalView">
					<a href="<?=$good->getGood()->getAdminUrl()?>/" target="blank">
						<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)
					</a>
					<br />
					<div class="comment"><?=$good->goodDescription?></div>
				</div>
				<div class="editView hide">
					<?=$good->getGood()->getName()?> (<?=$good->getGood()->getCode()?>)
					<br />
					<textarea name="goodDescriptionHidden"><?=$good->goodDescription?></textarea>
				</div>
			</td>
			<td class="noBcomplectLeft">
				<div class="normalView">
					<a class="pointer editComplectGood" data-id="<?=$good->id?>" title="Изменить товар"></a>
				</div>
				<div class="editView hide">
					<a class="pointer editComplectGoodAction" data-id="<?=$good->id?>" title="Сохранить изменения"></a>
				</div>
			</td>
			<td>
				<div class="normalView">
					<? if ($good->mainGood): ?>
					<img src="/admin/images/buttons/but_apply.png" />
					<? endif; ?>
				</div>
				<div class="editView hide">
					<label for="mainGood" style="display: block; height: 100%; width: 100%">
						<input type="checkbox" name="mainGood" value="1"  <?=($good->mainGood)?'checked':''?> style="width: 80px;" />
					</label>
				</div>

			</td>
			<td>
				<div class="normalView">
					<?=$good->discount?> / <font color="#999"><?=$good->getCatalogPrice()?></font>
				</div>
				<div class="editView hide">
					<input name="editDiscount" value="<?=$good->discount?>"> / <font color="#999"><?=$good->getCatalogPrice()?>
				</div>
			</td>
			<td>
				<div class="normalView">
					<?=$good->quantity?>
				</div>
				<div class="editView hide">
					<input name="editQuantity" value="<?=$good->quantity?>">
				</div>
			</td>
			<td>
				<?=$good->getTotalSum()?> / <font color="#999"><?=$good->getTotalBaseSum()?></font>
			</td>
			<td><div><a class="pointer delete deleteComplectGood"  title="Удалить товар"></a></div></td>
		</tr>
		<? $i++;  endforeach;?>
		<tr class="line">
			<td colspan="5" style="text-align: right; padding-right: 10px;">
				Итого:
			</td>
			<td>
				<?=$complectGoods->getTotalGoodsQuantity()?>
			</td>
			<td>
				<?=$complectGoods->getTotalSum()?> / <font color="#999"><?=$complectGoods->getTotalBaseSum()?></font>
			</td>
			<td>
			</td>
		</tr>
	</table>
	<br /><br />
	<p class="title">Добавить товар:</p>
	<div class="addGoodBlock">
		<table>
			<tr>
				<td class="first">Товар:</td>
				<td>
					<input type="text" class="inputGoodId">
					<img class="inputGoodLoader" style="margin: 5px 0px -10px 140px; display: none;" src="/images/loaders/loading-small.gif" />
				</td>
			</tr>
			<tr class="blockedRow">
				<td class="first">Основной товар комплекта:</td>
				<td><input type="checkbox" name="mainGood" class="blocked" value="1" style="width: 80px;" /></td>
			</tr>
			<tr class="blockedRow">
				<td class="first">Количество:</td>
				<td><input type="text" name="quantity" class="addedGoodQuantity blocked" pattern="[0-9]" value="" style="width: 80px;" /></td>
			</tr>
			<tr class="blockedRow">
				<td class="first">Скидка на товар:</td>
				<td>
					<input type="text" name="discount" class="goodDiscount blocked" pattern="[0-9]" value="" style="width: 80px;" />
					<div style="color:#999">
						Цена:
						<span class="addedGoodPrice"></span>
						Цена поставщика:
						<span class="addedGoodBasePrice"></span>
						( доход: <span class="goodIncome"></span> )
					</div>

				</td>
			</tr>
			<tr class="blockedRow">
				<td class="first">Заметки:</td>
				<td><textarea class="blocked" style="width: 306px; height: 60px;" name="goodDescription"></textarea></td>
			</tr>
			<tr class="blockedRow">
				<td class="first"></td>
				<td><a id="addGoodToComplect" class="add-bottom pointer blockedButton" style="margin: 0px 0px 0px -20px; ">Добавить</a></td>
			</tr>
		</table>
	</div>
</div>
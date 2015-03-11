<?if($this->checkUserRight('orderGoods_add')):?>
	<br /><br />
	<p class="title">Добавить товар:</p>
	<div class="addGoodBlock">
		<table width="100%">
			<tr>
				<td class="first">Товар:</td>
				<td>
					<input type="text" class="inputGoodId">
					<img class="inputGoodLoader" style="margin: 5px 0px -10px 140px; display: none;" src="/images/loaders/loading-small.gif" />
				</td>
			</tr>
			<tr>
				<td class="first">Количество:</td>
				<td><input type="text" name="quantity" class="addedGoodQuantity" value="" style="width: 80px;" /></td>
			</tr>
			<tr>
				<td class="first">Цена:</td>
				<td><input type="text" name="price" class="addedGoodPrice" value="" style="width: 80px;" /></td>
			</tr>
			<tr>
				<td class="first">Базовая цена:</td>
				<td><input type="text" name="basePrice" class="addedGoodBasePrice" value="" style="width: 80px;" /></td>
			</tr>
			<tr>
				<td class="first">Заметки:</td>
				<td><textarea style="width: 306px; height: 60px;" name="goodDescription"></textarea></td>
			</tr>
			<tr>
				<td class="first"></td>
				<td><a id="addGoodToOrder" class="add-bottom pointer" style="margin: 0px 0px 0px -20px; ">Добавить</a></td>
			</tr>
		</table>
	</div>
<?endif?>
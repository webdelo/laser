<div class="goodsTable">
	<p class="title">Список подтоваров:</p>
<?if ($subGoods->count()):?>
	<table class="goodsList">
		<tr class="top">
			<td>#</td>
			<td class="borderLeft">Подтовар</td>
			<td class="borderLeft">Количество</td>
			<td class="borderLeft"><img src="/admin/images/bg/trash.png" alt="Удалить" /></td>
		</tr>
<? $i=1; foreach($subGoods as $subGood):?>
		<tr class="line">
			<td><?=$i?></td>
			<td>
				<a href="<?=$subGood->getGood()->getAdminUrl()?>" target="blank">
					<?=$subGood->getGood()->getName()?> (<?=$subGood->getGood()->getCode()?>)
				</a>
			</td>
			<td class="center">
				<?=$subGood->subGoodQuantity?>
			</td>
			<td class="center">
				<div><a class="pointer delete deleteSubGood" data-id="<?=$subGood->id?>"  title="Удалить подтовар"></a></div>
			</td>
		</tr>
<? $i++; endforeach?>
	</table>
<?else:?>
	<i>К заказу не добавлено ни одного товара.</i>
<? endif;?>
	<br /><br />
	<p class="title">Добавить подтовар:</p>
	<div class="addGoodBlock">
		<table width="100%">
			<tr>
				<td class="first">Подтовар:</td>
				<td>
					<input type="text" class="inputSubGoodId">
					<img class="inputGoodLoader" style="margin: 5px 0px -10px 140px; display: none;" src="/images/loaders/loading-small.gif" />
				</td>
			</tr>
			<tr>
				<td class="first">Количество:</td>
				<td><input type="text" name="subGoodQuantity" value="" style="width: 80px;" /></td>
			</tr>
			<tr>
				<td class="first"></td>
				<td><a id="addSubGood" class="add-bottom pointer" data-mainGoodId="<?=$good->id?>" style="margin: 0px 0px 0px -20px; ">Добавить</a></td>
			</tr>
		</table>
	</div>
</div>
<div class="page_num">

<?if($pager->getFirstPage()):?>
	<a href="<?=$pager->getFirstPage()->getLink()?>">первая</a>
<?endif?>

<?foreach ( $pager as $page):?>
	<?if($page->isCurrentPage()):?>
		<span><?=$page->getPage()?></span>
	<?else:?>
		<a href="<?=$page->getLink()?>"><?=$page->getPage()?></a>
	<?endif?>
<?  endforeach;?>

<?if($pager->getLastPage()):?>
	<a href="<?=$pager->getLastPage()->getLink()?>">последняя</a>
<?endif?>

</div>

<div class="page_sort">
	Вывести по:
	<select name="itemsOnPage" onchange="location.href=this.value">
		<?  foreach ($pagesList as $page):?>
		<option value="<?=$pager->getItemsOnPageLink($page)?>" <?= $page==$this->getGET()['itemsOnPage'] ? 'selected' : ''?>><?=$page?></option>
		<?  endforeach?>
	</select>
</div>
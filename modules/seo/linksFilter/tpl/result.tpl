<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<div class="main single">
			<div class="max_width">
				<p class="speedbar">
					<a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/seo/">SEO</a>     <span>></span>
					Результат сортировка выгрузки ссылочного
				</p>
				<div class="clear"></div>
				<div class="table_edit">
<script type="text/javascript">
	toggleTable = function (){
		$('.toggleTable').live('click',function(){
			$('#table_'+$(this).attr('id')).slideToggle(0);
		});
	};
	jQuery(document).ready(function(){
		toggleTable();
	});
</script>
<?foreach ($linksFilter->getQueries() as $key => $query):?>
					<h3><a class="pointer toggleTable" id="key_<?=$key?>">Запрос "<?=$query?>":</a></h3>
					<table id="table_key_<?=$key?>" class="hide">
						<tr>
							<th>Страница</th>
							<th>Кол-во ссылок</th>
							<th>Длина анкора</th>
						</tr>
<? foreach($querySorting = $linksFilter->getPageSortingByQuery($query) as $key => $row):?>
						<tr>
							<td><?=$row['page']?></td>
							<td align="center"><strong><?=$row['totalLinks']?></strong></td>
							<td>
								<strong>
									<?= $linksFilter->getNameBySpamValue($row['words'])?>
								</strong>
							</td>
						</tr>
<? endforeach;?>
						<tr>
							<td></td>
							<td><strong>Общая спамность:</strong></td>
							<td><strong><?=$linksFilter->getTotalSpamByQuery($querySorting)?>%</strong></td>
						</tr>
					</table>

					<div class="clear"></div>
					<br/><br/>
<? endforeach;?>
					<hr/>
					<h3><a class="pointer toggleTable" id="total">Общее ссылочное:</a></h3>
					<table id="table_total" class="hide">
						<tr>
							<th>Страница</th>
							<th>Кол-во ссылок</th>
							<th>Длина анкора</th>
						</tr>
<? foreach($linksFilter->getPageSortingByWords() as $key => $row):?>
						<tr>
							<td><?=$row['page']?></td>
							<td><strong><?=$row['totalLinks']?></strong></td>
							<td><strong><?=$row['words']?></strong>-словник</td>
						</tr>
<? endforeach;?>
					</table>
					<div class="clear"></div>
					<br/><br/>
					<h3><a class="pointer toggleTable" id="base">Базовая фильтрация:</a></h3>
					<table id="table_base" class="hide">
						<tr>
							<th>#</th>
							<th>URL</th>
							<th>Донор</th>
							<th>Анкор</th>
							<th>Кол-во слов</th>
							<th>Страница-донор</th>
						</tr>
<? foreach($linksFilter->getDistinctList() as $key => $row):?>
						<tr>
							<td><?=$key+1?></td>
							<td><?=$row['page']?></td>
							<td><?=$row['domain']?></td>
							<td><?=$row['ankor']?></td>
							<td><?=$row['words']?></td>
							<td><?=$row['donorPage']?></td>
						</tr>
<? endforeach;?>
					</table>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>
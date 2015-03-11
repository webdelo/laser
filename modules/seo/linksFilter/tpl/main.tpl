<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<div class="main single">
			<div class="max_width">
				<p class="speedbar">
					<a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/seo/">SEO</a>     <span>></span>
					Сортировка выгрузки ссылочного
				</p>
				<div class="clear"></div>
				<div class="table_edit">
<?if(isset($error)):?>
					<div>
						<font color="red"><?=$error?></font>
					</div>
<?endif;?>
					<h1>Данные по выгрузке</h1>
					<form action="/admin/seo/analizeLinks/" enctype="multipart/form-data" method="post">
						<table>
							<tr>
								<td>
									<input type="file" name="csv" value="CSV файл"/>
									<br/><br/>
									<b>Домен сайта:</b> <i>(используется для вычислений естественных ссылок)</i><br/>
									<input type="text" name="domain" value="" size="60"/>
								</td>
								<td>
									<input type="submit" name="start" value="Анализировать"/>
								</td>
							</tr>
							<tr>
								<td>
									<b>Поисковые запросы:</b> <i>(каждый запрос с новой строки)</i><br/>
									<textarea name="searchQuery" rows="20" cols="50"></textarea>
									<br/>
									<font color="red">Используется для подсчёта спамности по запросу</font>
								</td>
								<td>
									<b>Стоп-слова:</b> <i>(каждое слово с новой строки)</i><br/>
									<textarea name="stopWords" rows="20" cols="50">без
к
в
за
с
по
за
на
над
в  
для
у
о
под
с
из-за
про
по
от</textarea>
									<br/>
									<font color="red">Указанные стоп-слова будут иссключены из подсчёта длины анкора</font>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>

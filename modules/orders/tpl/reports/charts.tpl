<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
		<div class="main single">
			<div class="max_width">
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/orders/">Заказы</a>     <span>></span>
					Диаграммы
				</p>
				<div class="clear"></div>
				<div id="tabs">
					<div class="tab_page">
						<ul>
							<li>
								<a href="#goodSales">Динамика продаж</a>
							</li>
						</ul>
					</div>
					<div id="goodSales">
						<?include'goodSalesChartContent.tpl'?>
					</div>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>

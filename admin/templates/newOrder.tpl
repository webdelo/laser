<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<link rel="stylesheet" type="text/css" href="/modules/orders/css/style.css">
		<link rel="stylesheet" type="text/css" href="/admin/js/jquery/autosuggest/autoSuggest.css">
		<script type="text/javascript" src="/admin/js/jquery/autosuggest/jquery.autoSuggest.js"></script>
		<script type="text/javascript" src="/modules/orders/js/newOrder/newOrderHandler.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="formAddSubmit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<a href="/admin/orders/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/orders/">Заказы</a>    <span>></span> 
					Новый заказ
				</p>
				<div class="clear"></div>
				<form class="form<?= $order->id ? 'Edit' : 'Add'?>" action="/admin/orders/saveStep1/" method="post">
					<div class="main_edit">
						<div class="main_param">
							<div class="col_in">
								<table>
									<tr>
										<td class="first">
											Товар:
										</td>
										<td>
											<input class="catalogGood" type="text" name="good" tabindex="1" value="">
										</td>
									</tr>
									<tr>
										<td class="catalogGoods">
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
    <?include(TEMPLATES_ADMIN.'footer.tpl');?>

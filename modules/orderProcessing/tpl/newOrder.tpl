<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<link rel="stylesheet" type="text/css" href="/modules/orders/css/style.css">
		<link rel="stylesheet" type="text/css" href="/modules/orderProcessing/css/orderProcessing.css">
		<link rel="stylesheet" type="text/css" href="/admin/js/jquery/autosuggest/autoSuggest.css">
		<script type="text/javascript" src="/admin/js/jquery/autosuggest/jquery.autoSuggest.js"></script>
		<script type="text/javascript" src="/modules/orderProcessing/js/newOrderHandler.js"></script>

		<script type="text/javascript" src="/modules/orders/js/goodsTableFunctions.js"></script>
		<div class="orderGoodDetails" data-source="/admin/orders/getOrderGoodDetails/"></div>
		
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a href="/admin/orders/"><img src="/admin/images/buttons/back.png" alt="" /> Выйти из оформления заказа</a>
				</div>
<!--				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					<a href="/admin/orders/">Заказы</a>    <span>></span>
					Новый заказ
				</p>-->
				<div class="clear"></div>
				<form class="form<?= $order->id ? 'Edit' : 'Add'?>" action="/admin/orders/saveStep1/" method="post">
					<input type="hidden" id="orderId" value="<?=$order->id?>">
					<div class="main_edit">
						<div class="main_param">
							<div class="col_in">
								<table>
									<tr>
										<td>
											<table width="817">
												<tr>
													<td>
														<table >
															<tr>
																<td class="first headerFirstLevel rightMenuRow" data-menu="Товары">
																	Выбрать товар:
																</td>
																<td>
																	<input class="catalogGood" type="text" name="good" tabindex="1" value="" data-extra-params="&orderId=<?=$order->id?>">
																</td>
															</tr>
															<tr>
																<td></td>
																<td class="domainsTable" data-source="/admin/orderProcessing/ajaxGetDomainsTable/?orderId=<?=$order->id?>">
																	<? if ($this->isNotNoop($order)) $this->getDomainsTable($order->id) ?>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td class="catalogGoods" data-source="/admin/orderProcessing/ajaxGetOrderGoodsTable/?orderId=<?=$order->id?>">
														<? if ($this->isNotNoop($order)) $this->getOrderGoodsTable($order->id) ?>
													</td>
												</tr>
												<tr>
													<td class="catalogDelivery" data-source="/admin/orderProcessing/ajaxGetDeliveryTable/?orderId=<?=$order->id?>">
														<? if ($this->isNotNoop($order)) $this->getDeliveryTable($order->id) ?>
													</td>
												</tr>
												<tr>
													<td class="catalogResultPriceBlock" data-source="/admin/orderProcessing/ajaxGetResultPriceBlock/?orderId=<?=$order->id?>">
														<? if ($this->isNotNoop($order)) $this->getResultPriceBlock($order->id) ?>
													</td>
												</tr>
												<tr>
													<td class="catalogClientDataBlock" data-source="/admin/orderProcessing/ajaxGetClientDataBlock/?orderId=<?=$order->id?>">
														<? if ($this->isNotNoop($order))  $this->getClientDataBlock($order->id) ?>
													</td>
												</tr>
												<tr>
													<td class="orderSummary" data-source="/admin/orderProcessing/ajaxGetOrderSummary/?orderId=<?=$order->id?>"></td>
												</tr>
											</table>
										</td>
										<td>
											<div class="rightMenu">
												<h4>Шаги:</h4>
												<ul>
												</ul>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</form>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
    <?include(TEMPLATES_ADMIN.'footer.tpl');?>

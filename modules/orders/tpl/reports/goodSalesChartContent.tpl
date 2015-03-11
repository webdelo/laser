<link rel="stylesheet" type="text/css" href="/admin/js/jquery/tree/styles/jQuery.Tree.css" />
<script type="text/javascript" src="/admin/js/jquery/tree/jQuery.Tree.js"></script>
<script type="text/javascript" src="/modules/orders/js/chartsHandler.js"></script>
 <script type="text/javascript">
	$(document).ready(function(){
		$('.tree ul:first').Tree();
	});
</script>

<table>
	<tr>
		<td>Тип товаров:</td>
		<td>Выборка по:</td>
		<td>Начальная дата:</td>
		<td>Финальная дата:</td>
		<td>Только оплаченные заказы:</td>
		<td></td>
	</tr>
	<tr>
		<td>
			<select class="catalogType">
				<option value="constructions">Конструкции</option>
				<option value="devices">Девайсы</option>
			</select>
		</td>
		<td>
			<select class="type">
				<option id="ids" name="good" value="goodsSalesChart">товарам</option>
				<option id="catids" name="category" value="categoriesSalesChart">категориям</option>
			</select>
		</td>
		<td><input class="date start_date" type="text" value="01-01-2012" /></td>
		<td><input class="date end_date" type="text" value="<?=date("j-m-Y")?>"/></td>
		<td class="center"><input class="onlyPaydOrders" type="checkbox" checked /></td>
		<td>
			<div class="action_buts">
				<a class="goodSalesChartButton"><img src="/admin/images/buttons/diagram.png" alt="" /> Открыть диаграмму</a>
			</div>
		</td>
	</tr>
</table>
<br /><br />
<div class="table_edit">
	<div class="left tree checkboxes" style="float: none;">
		<ul>
			<li class="constructions change">
				<label>
					<input type="checkbox"> Конструкции
				</label>
				<ul>
					<?
					if($this->getController('Constructions')->getConstructionsObject()->getMainCategories()):
					foreach($this->getController('Constructions')->getConstructionsObject()->getMainCategories() as $mainCategory):
					?>
					<li>
						<label>
							<input type="checkbox" class="category" value="<?=$mainCategory->id?>"> <?=$mainCategory->getName()?>
						</label>
						<?
						if($mainCategory->getChildren()):
						foreach($mainCategory->getChildren() as $category):
						?>
						<ul>
							<li>
								<label>
									<input type="checkbox" class="category" value="<?=$category->id?>"> <?=$category->getName()?>
								</label>
								<?
								if($category->getChildren()):
								foreach($category->getChildren() as $subCategory):
								?>
								<ul>
									<li>
										<label>
											<input type="checkbox" class="category" value="<?=$subCategory->id?>"> <?=$subCategory->getName()?>
										</label>
										<?
										if($this->getController('Constructions')->getConstructionsObject()->getConstructionsByCategoryId($subCategory->id)):
										foreach($this->getController('Constructions')->getConstructionsObject()->getConstructionsByCategoryId($category->id) as $good):
										?>
										<ul>
											<li>
												<label>
													<input type="checkbox" class="good" value="<?=$good->id?>"> <?=$good->getName()?>
												</label>
											</li>
										</ul>
										<?endforeach;endif?>
									</li>
								</ul>
								<?endforeach; endif?>
								<?
								if($this->getController('Constructions')->getConstructionsObject()->getConstructionsByCategoryId($category->id)):
								foreach($this->getController('Constructions')->getConstructionsObject()->getConstructionsByCategoryId($category->id) as $good):
								?>
								<ul>
									<li>
										<label>
											<input type="checkbox" class="good" value="<?=$good->id?>"> <?=$good->getName()?>
										</label>
									</li>
								</ul>
								<?endforeach;endif?>
							</li>
						</ul>
						<?endforeach; endif?>
					</li>
					<?endforeach; endif?>
				</ul>
			</li>
			<li class="devices change hide">
				<label>
					<input type="checkbox"> Девайсы
				</label>
				<ul>
					<?
					if($this->getController('Devices')->getDevicesObject()->getMainCategories()):
					foreach($this->getController('Devices')->getDevicesObject()->getMainCategories() as $mainCategory):
					?>
					<li>
						<label>
							<input type="checkbox" class="category" value="<?=$mainCategory->id?>"> <?=$mainCategory->getName()?>
						</label>
						<?
						if($mainCategory->getChildren()):
						foreach($mainCategory->getChildren() as $category):
						?>
						<ul>
							<li>
								<label>
									<input type="checkbox" class="category" value="<?=$category->id?>"> <?=$category->getName()?>
								</label>
								<?
								if($category->getChildren()):
								foreach($category->getChildren() as $subCategory):
								?>
								<ul>
									<li>
										<label>
											<input type="checkbox" class="category" value="<?=$subCategory->id?>"> <?=$subCategory->getName()?>
										</label>
										<?
										if($this->getController('Devices')->getDevicesObject()->getDevicesByCategoryId($subCategory->id)):
										foreach($this->getController('Devices')->getDevicesObject()->getDevicesByCategoryId($subCategory->id) as $good):
										?>
										<ul>
											<li>
												<label>
													<input type="checkbox" class="good" value="<?=$good->id?>"> <?=$good->getName()?>
												</label>
											</li>
										</ul>
										<?endforeach;endif?>
									</li>
								</ul>
								<?endforeach; endif?>
								<?
								if($this->getController('Devices')->getDevicesObject()->getDevicesByCategoryId($category->id)):
								foreach($this->getController('Devices')->getDevicesObject()->getDevicesByCategoryId($category->id) as $good):
								?>
								<ul>
									<li>
										<label>
											<input type="checkbox" class="good" value="<?=$good->id?>"> <?=$good->getName()?>
										</label>
									</li>
								</ul>
								<?endforeach;endif?>
							</li>
						</ul>
						<?endforeach; endif?>
					</li>
					<?endforeach; endif?>
				</ul>
			</li>
		</ul>
	</div>
</div>
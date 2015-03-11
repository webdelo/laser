<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="ru">
		<title>Statistic Report</title>
		<link rel="stylesheet" type="text/css" href="/admin/css/style.css">
		<style type="text/css">
			a {text-decoration: none;}
			.head_line{padding: 2px 0;}
			.title{float: left; margin: 13px 0px 5px 150px; text-align: center; font-size: 16px; font-weight: bold;}
			.max_width{max-width: none; width: 1130px;}
		</style>
	</head>
	<body>
		<div class="root">
			<div class="head_line">
				<div class="max_width">
					<p class="logo"><a href="/admin/"><img src="/admin/images/logo/logo.png" alt=""></a></p>
					<div class="title"><?=$title?></div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="main single">
				<div class="max_width">
					<?if(isset($noDataRecevied)):?>
					<p>Не переданы необходимые данные.</p>
					<p>Вернитесь на <a href="/admin/orders/charts/">страницу Диаграмм</a> и выберите необходимые данные.</p>
					<?else:?>
						<?if(isset($noData)):?>
						<p>Данных для диаграммы не существует.</p>
						<p>Попробуйте задать другие параметры для диаграммы.</p>
						<?else:?>
						<div class="table_edit">
							<table>
								<tr>
									<th class="first">id</th>
									<th>Название (родитель)</th>
									<th>Количество, шт.</th>
									<th>Стоимость, руб.</th>
									<th>Прибыль, руб.</th>
									<th class="last">id заказа (количество, шт.)</th>
								</tr>
								<?foreach($dataArray as $row):?>
								<tr>
									<td><?=$row['id']?></td>
									<td><?=$row['name']?> (<?=$row['category']?>)</td>
									<td><?=$row['quantity']?></td>
									<td><?=\core\utils\Prices::standartPrice($row['totalSum'])?></td>
									<td><?=\core\utils\Prices::standartPrice($row['totalIncome'])?></td>
									<td>
										<?foreach($row['orders'] as $order):?>
										<a href="/admin/orders/order/<?=$order['orderId']?>/" target="blank"><?=$order['orderId']?> (<?=$order['quantity']?> шт.)</a>,&nbsp;&nbsp;&nbsp;
										<?endforeach?>
									</td>
								</tr>
								<?endforeach?>
								<tr>
									<th class="first" colspan="2" align="right"><b>Итого:</b></th>
									<th><b><?=$countAll?></b></th>
									<th><b><?=\core\utils\Prices::standartPrice($totalSumAll)?></b></th>
									<th><b><?=\core\utils\Prices::standartPrice($totalIncomeAll)?></b></th>
									<th class="last"></th>
								</tr>
							</table>
						</div>


						<!--Load the AJAX API-->
						   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
						   <script type="text/javascript">

							 // Load the Visualization API and the piechart package.
							 google.load('visualization', '1.0', {'packages':['corechart']});

							 // Set a callback to run when the Google Visualization API is loaded.
							 google.setOnLoadCallback(drawChart);

							 // Callback that creates and populates a data table,
							 // instantiates the pie chart, passes in the data and
							 // draws it.
							 function drawChart() {

							   // Create the data table.
							   var data = new google.visualization.DataTable();
							   data.addColumn('string', 'Topping');
							   data.addColumn('number', 'Slices');
							   data.addRows([
								   <?=$data?>
							   ]);

							   // Set chart options
							   var options = {
									'title':<?= json_encode($title)?>,
									'width':900,
									'height':900,
									'top':500
								};

							   // Instantiate and draw our chart, passing in some options.
							   var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
							   chart.draw(data, options);
							 }
						   </script>
						<div id="chart_div"></div>
						<?endif?>
					<?endif?>
				</div>
			</div><!--main-->
			<div class="vote"></div>
		</div><!--root-->
	</body>
</html>



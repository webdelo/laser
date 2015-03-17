		<?=$this->getHeader();?>
		<div class="content">
			<table>
			  <col width="130">
			  <col width="800">
			  <tr>
				  <td><b>Время :</b></td>
				<td><?=$data[0][1];?></td>
			  </tr>
			  <tr>
				<td><b>IP :</b></td>
				<td><?=$data[1][1];?> (<?=$data[1][2];?>)</td>
			  </tr>
			  <tr>
				<td><b>Запрос :</b></td>
				<td><?=$data[2][1];?></td>
			  </tr>
			  <tr>
				<td><b>Referer :</b></td>
				<td><?=$data[3][1];?></td>
			  </tr>
			  <tr>
				<td><b>User-Agent :</b></td>
				<td><?=$data[4][1];?></td>
			  </tr>
			</table>
		</div>
		<?=$this->getFooter();?>
<html>
	<head>
		<style>
			h1 {
				color: #fff;
				background-color: #8ab2c0;
			}
			h1 span {
				font-size: 12px;
			}
			div.mainContent {
				float: left;
				color: #000;
				font-size: 12px;
				margin-bottom: 20px;
			}
			table.noBorders td{
				border: none;
			}
			table{
				float: left;
				border-collapse: collapse;
				font-size: 12px;
				margin: 5px;
			}
			table.noBorders td table td{
				border: 1px solid #000;
				padding: 5px;
			}
			table td.title{
				background-color: #016080;
				color: #fefefe;
			}
			table td.mainTitle{
				background-color: #016080;
				color: #fefefe;
				font-weight: bold;
				font-size: 15px;
			}
			div.dumpHead {
				width: 1000px;
				float: left;
				background-color:#656565;
				color:#ffffff;
				padding: 5px;
				border: 1px solid #929292;
				margin: 0px;
				margin-top: 10px;
				font-size: 15px;
			}
			div.dumpBody {
				float: left;
				width: 1000px;
				color: #000;
				font-size: 12px;
				background-color:#fefbe6;
				border: 1px solid #929292;
				padding: 0px;
				margin: 0px;
				overflow: auto;
			}
		</style>
	</head>
	<body>
		<h1>&nbsp;<?=$this->config['projectName'];?> <span><?=date('d-m-Y | H:i');?></span></h1>
		<div class="mainContent">
			<table class="noBorders">
				<tr>
					<td>
						<table width="100%">
							<tr>
								<td class="mainTitle" colspan="2">Error Data</td>
							</tr>
							<tr>
								<td class="title">Message</td>
								<td><?=$this->message;?></td>
							</tr>
							<tr>
								<td class="title">File</td>
								<td><?=$this->file;?></td>
							</tr>
							<tr>
								<td class="title">Line</td>
								<td><?=$this->line;?></td>
							</tr>
							<tr>
								<td class="title">Type</td>
								<td><?=$this->type;?></td>
							</tr>
							<tr>
								<td class="title">Exception class</td>
								<td><?=$this->exceptionClass;?></td>
							</tr>
							<tr>
								<td class="title">Code title</td>
								<td><?=$this->code;?></td>
							</tr>
							<? if ($this->isErrorCode($this->code)): ?>
							<tr>
								<td class="title">Code</td>
								<td style="background-color: <?=$this->errorsList[$this->code]['color']?>"><?=$this->errorsList[$this->code]['name'];?></td>
							</tr>
							<tr>
								<td class="title">Code description</td>
								<td><?=$this->errorsList[$this->code]['description'];?></td>
							</tr>
							<? endif; ?>
						</table>
						<table>
							<tr>
								<td class="mainTitle" colspan="2">Server Data</td>
							</tr>
							<tr>
								<td class="title">PHP Version</td>
								<td><?=$this->phpVersion;?></td>
							</tr>
							<tr>
								<td class="title">HOST</td>
								<td><?=$this->server['HTTP_HOST'];?></td>
							</tr>
							<tr>
								<td class="title">SERVER SOFTWARE</td>
								<td><?=$this->server['SERVER_SOFTWARE'];?></td>
							</tr>
							<tr>
								<td class="title">SERVER ADDR</td>
								<td><?=$this->server['SERVER_ADDR'];?></td>
							</tr>
							<tr>
								<td class="title">REQUEST TIME</td>
								<td><?=date('d/m/Y H:i', $this->server['REQUEST_TIME']);?></td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table>
							<tr>
								<td class="mainTitle" colspan="2">Client Data</td>
							</tr>
							<tr>
								<td class="title">IP</td>
								<td><?=$this->server['REMOTE_ADDR'];?></td>
							</tr>
							<tr>
								<td class="title">USER AGENT</td>
								<td><?=$this->server['HTTP_USER_AGENT'];?></td>
							</tr>
							<tr>
								<td class="title">HTTP ACCEPT LANGUAGE</td>
								<td><?=$this->server['HTTP_ACCEPT_LANGUAGE'];?></td>
							</tr>
							<tr>
								<td class="title">HTTP ACCEPT CHARSET</td>
								<td><?=$this->server['HTTP_ACCEPT_CHARSET'];?></td>
							</tr>
							<tr>
								<td class="title">REFERER</td>
								<td><?=(!empty($this->server['HTTP_REFERER']))?$this->server['HTTP_REFERER']:'Refferer undefined';?></td>
							</tr>
							<tr>
								<td class="title">REQUEST METHOD</td>
								<td><?=$this->server['REQUEST_METHOD'];?></td>
							</tr>
							<tr>
								<td class="title">REQUEST URI</td>
								<td><?=$this->server['REQUEST_URI'];?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<pre>
						<?foreach ($this->data as $key=>$value) : ?>
							<div class="dumpHead"><?=$key?></div>
							<div class="dumpBody">
<? var_dump($value); ?>
							</div>
						<? endforeach; ?>
						</pre>
					</td>
				</tr>
			</table>
		</div>

	</body>
</html>
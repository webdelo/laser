<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator</title>
<link rel="stylesheet" type="text/css" href="style/style.css" />
<script type="text/javascript" src="<?= DIR_HTTP?>js/jquery.js"></script>
<script type="text/javascript" src="<?= DIR_HTTP?>/js/errorHandler.js"></script>
<script type="text/javascript">
	function send()
	{
		var data = $("#user").serialize();
		ajaxImg('start');
		$.ajax({  beforeSend: function(){authorization.start()}, error: function(){ ajaxImg('stop'); },
			url: 'index.php?action=editAdministratorAction',
			type: 'post',
			data: data,
			dataType: 'json',
			success: function (response){
				ajaxImg('stop');
				if (response == 1) {
					alert('Information has been saved');
					location.reload();
				}
				else{
					inputError(response,true);
				}
			}
		})
	}
</script>
</head>
<body>
<div id="ajax_bg">&nbsp;</div>
<div id="ajax_img"><img src="../images/ajax-loader.gif"/></div>
<div style="margin:0 auto 0 auto; width:1078px;">
<table>
<tr><td align="left" valign="top">
	<div>
    <?php include(TEMPLATES_ADMIN.'header.tpl')?>
    </div>
	<div class="main_content">
		<div id="error" align="center"></div>
		<form id="user" action="" onsubmit="send(); return false;">
		<table>
			<tr>
				<td class="td_right">User Name:</td>
				<td class="td"><input type="text" name="username" value="" /></td>
			</tr>
			<tr>
				<td class="td_right">Current Password:</td>
				<td class="td"><input type="password" name="pass" value="" /></td>
			</tr>
			<tr>
				<td class="td_right">New Password:</td>
				<td class="td"><input type="password" name="n_pass" value="" /></td>
			</tr>
			<tr>
				<td class="td_right">Confirm New Password:</td>
				<td class="td"><input type="password" name="n_pass_confirm" value="" /></td>
			</tr>
		</table>
		<br />
		<?php include(TEMPLATES_ADMIN.'back_button.tpl')?> <input type="submit" class="btn_submit" value=""/>
		</form>
    </div>
    <div>
	<?php include(TEMPLATES_ADMIN.'footer.tpl')?>
	</div>
</td></tr>
</table>
</div>
</body>
</html>

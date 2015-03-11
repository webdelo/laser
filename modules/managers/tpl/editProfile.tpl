<div class="main_edit">
    <input type='hidden' class='objectId' value='<?=$profile->id?>'>
	<div class="main_param" style="width: 670px; margin-right: -42px;">
		<div class="col_in">
			<p class="title">Основные данные:</p>
			<table width="100%">
				<tr>
					<td>Имя:</td>
					<td><input type="text" name="name" value="<?=$profile->name;?>" /></td>
				</tr>
				<tr>
					<td>Логин:</td>
					<td>
					<? if($profile->id): ?>
						<?=$profile->getLogin();?>
					<? else: ?>
						<input style="width: 137px;" type="text" name="login" value="" />
					<? endif; ?></td>
				</tr>
				<tr>
					<td>Пароль:&nbsp;&nbsp;</td>
					<td>
						<? if ($profile->id): ?>
						<div class="formChangePassword" data-action="/admin/profile/editPassword/" data-method="post" data-post-action="?action=administrator=<?=$profile->id?>">
						<? endif; ?>
							<input type="hidden" name="id" value="<?=$profile->id?>" />
							<input style="width: 137px;" type="password" name="password" class="exp" value />
							&nbsp;&nbsp;Подтверждение:
							<input style="width: 137px;" type="password" name="passwordConfirm" />
						<? if ($profile->id): ?>
							<input style="margin: 12px 0px 0px 136px" type="submit" class="formChangePasswordSubmit" name="changePassword" value="Изменить пароль" />
							<div class="changePasswordMessage"></div>
						</div>
						<? endif; ?>
					</td>
				</tr>
				<tr>
					<td>Партнер: </td>
					<td>
						<?=$profile->getPartner()->name?>
					</td>
				</tr>
				<tr>
					<td>Статус: </td>
					<td>
						<?=$profile->getStatus()->name?>
					</td>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="clear"></div>
</div><!--main_edit-->
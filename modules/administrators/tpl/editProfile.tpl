<div class="main_edit">
    <input type='hidden' class='objectId' value='<?=$profile->id?>'>
	<div class="main_param" style="width: 670px; margin-right: -42px;">
		<div class="col_in">
			<p class="title">Основные данные:</p>
			<table width="100%">
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
					<td>Группа:</td>
					<td>
						<?=$profile->getGroup()->name?>
					</td>
				</tr>
				<tr>
					<td>Статус:</td>
					<td>
						<?=$profile->getStatus()->name?>
					</td>
				</tr>
				<tr>
					<td>Дата:</td>
					<td>
						<?=$profile->date?>
					</td>
				</tr>
			</table>
		</div>
	</div><!--main_param-->
	<div class="dop_param">
		<div class="col_in">
			<p class="title">Персональные данные:</p>
			<table width="100%">
				<tr>
					<td>Фамилия:</td>
					<td><input type="text" name="name" value="<?=$profile->name;?>" /></td>
				</tr>
				<tr>
					<td>Имя:</td>
					<td>
						<input type="text" name="firstname" value="<?=$profile->firstname;?>" />
					</td>
				</tr>
				<tr>
					<td>Отчество:</td>
					<td>
						<input type="text" name="lastname" value="<?=$profile->lastname;?>" />
					</td>
				</tr>
				<tr>
					<td>Email:</td>
					<td>
						<input type="text" name="email" value="<?=$profile->email;?>" />
					</td>
				</tr>
				<tr>
					<td>Skype:</td>
					<td>
						<input type="text" name="skype" value="<?=$profile->skype;?>" />
					</td>
				</tr>
				<tr>
					<td>Address:</td>
					<td>
						<input type="text" name="address" value="<?=$profile->address;?>" />
					</td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td>
						<input type="text" name="phone" value="<?=$profile->phone;?>" />
					</td>
				</tr>
				<tr>
					<td>Mobile:</td>
					<td>
						<input type="text" name="mobile" value="<?=$profile->mobile;?>" />
					</td>
				</tr>
			</table>

		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
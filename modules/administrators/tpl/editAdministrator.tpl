<div class="main_edit">
    <input type='hidden' class='objectId' value='<?=$administrator->id?>'>
	<div class="main_param" style="width: 670px; margin-right: -42px;">
		<div class="col_in">
			<p class="title">Основные данные:</p>
			<table width="100%">
				<tr>
					<td>Логин:</td>
					<td>
					<? if($administrator->id): ?>
						<?=$administrator->getLogin();?>
					<? else: ?>
						<input style="width: 137px;" type="text" name="login" value="" />
					<? endif; ?></td>
				</tr>
				<tr>
					<td>Пароль:&nbsp;&nbsp;</td>
					<td>
						<? if ($administrator->id): ?>
						<div class="formChangePassword" data-action="/admin/administrators/editPassword/" data-method="post" data-post-action="?action=administrator=<?=$administrator->id?>">
						<? endif; ?>
							<input type="hidden" name="id" value="<?=$administrator->id?>" />
							<input style="width: 137px;" type="password" name="password" class="exp" value />
							&nbsp;&nbsp;Подтверждение:
							<input style="width: 137px;" type="password" name="passwordConfirm" />
						<? if ($administrator->id): ?>
							<input style="margin: 12px 0px 0px 136px" type="submit" class="formChangePasswordSubmit" name="changePassword" value="Изменить пароль" />
							<div class="changePasswordMessage"></div>
						</div>
						<? endif; ?>
					</td>
				</tr>
				<tr>
					<td>Группа:</td>
					<td>
						<select name="groupId" class="groupId" style="width:150px;">
							<? foreach($administrators->getGroups() as $group): ?>
							<option value="<?=$group->id?>" <?=($group->id==$administrator->getGroup()->id)?'selected':''?> ><?=$group->name?></option>
							<? endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Статус:</td>
					<td>
						<select name="statusId" style="width:150px;">
						<? foreach($administrators->getStatuses() as $status): ?>
							<option value="<?=$status->id?>" <?=($status->id==$administrator->statusId)?'selected':''?> ><?=$status->name?></option>
						<? endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Дата:</td>
					<td>
						<input class="date" name="date" value="<?=$administrator->date?>" />
					</td>
				</tr>
				<tr>
					<td class="td_right">Права:</td>
					<td>
						<div class="left tree checkboxes">
						<?=$tree?>
						</div>
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
					<td><input type="text" name="name" value="<?=$administrator->name;?>" /></td>
				</tr>
				<tr>
					<td>Имя:</td>
					<td>
						<input type="text" name="firstname" value="<?=$administrator->firstname;?>" />
					</td>
				</tr>
				<tr>
					<td>Отчество:</td>
					<td>
						<input type="text" name="lastname" value="<?=$administrator->lastname;?>" />
					</td>
				</tr>
				<tr>
					<td>Email:</td>
					<td>
						<input type="text" name="email" value="<?=$administrator->email;?>" />
					</td>
				</tr>
				<tr>
					<td>Skype:</td>
					<td>
						<input type="text" name="skype" value="<?=$administrator->skype;?>" />
					</td>
				</tr>
				<tr>
					<td>Address:</td>
					<td>
						<input type="text" name="address" value="<?=$administrator->address;?>" />
					</td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td>
						<input type="text" name="phone" value="<?=$administrator->phone;?>" />
					</td>
				</tr>
				<tr>
					<td>Mobile:</td>
					<td>
						<input type="text" name="mobile" value="<?=$administrator->mobile;?>" />
					</td>
				</tr>
				<tr>
					<td>Comments</td>
					<td>
						<textarea name="note" style="width:232px; height:70px" ><?=$administrator->note?></textarea>
					</td>
				</tr>
			</table>

		</div>
	</div><!--dop_param-->
	<div class="clear"></div>
</div><!--main_edit-->
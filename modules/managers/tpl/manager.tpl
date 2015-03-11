<?include(TEMPLATES_ADMIN.'top.tpl');?>
	<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
	<link rel="stylesheet" type="text/css" href="/admin/js/jquery/tree/styles/jQuery.Tree.css" />
	<script type="text/javascript" src="/admin/js/jquery/tree/jQuery.Tree.js"></script>
	<script type="text/javascript" src="/modules/managers/js/managers.handler.js"></script>
	<script type="text/javascript" src="/modules/administrators/js/administrators.handler.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $object->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($object->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the manager?" data-action="/admin/managers/remove/<?=$object->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/managers/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif?>
					<a href="/admin/managers/<?= $object->partnerId ? $object->partnerId : $returnAction?>/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span><a href="/admin/partners/">Партнеры</a>     <span>></span>
					<a href="/admin/managers/<?= $object->partnerId ? $object->partnerId : $returnAction?>/">Менеджеры</a>    <span>></span>
					<?= $object->id ? 'Редактирование' : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<form class="form<?= $object->id ? 'Edit' : 'Add'?>" action="/admin/managers/<?= $object->id ? 'edit' : 'add'?>/" method="post" data-post-action="/admin/managers/">
					<?=$object->id ? '<input type="hidden" name="id" value="'.$object->id.'"/>' : '';?>
					<div id="tabs">

						<div class="tab_page">
							<ul>
								<li>
									<!--<a href="#" class="on">Параметры</a>-->
									<a href="#params">Параметры</a>
								</li>
							</ul>
						</div>
						<div id="params">
							<div class="main_edit">
								<div class="main_param">
									<div class="col_in">
										<p class="title">Основные параметры:</p>
										<table width="100%">
											<tr>
												<td class="first">Имя:</td>
												<td><input type="text" name="name" value="<?=$object->name?>" /></td>
											</tr>
											<tr>
												<td class="first">Логин:</td>
												<td><input type="text" name="login" value="<?=$object->getLogin()?>" /></td>
											</tr>
											<tr>
												<td  class="first">Пароль:&nbsp;&nbsp;</td>
												<td>
													<? if ($object->id): ?>
													<div class="formChangePassword" data-action="/admin/managers/editPassword/" data-method="post" data-post-action="?action=administrator=<?=$object->id?>">
													<? endif; ?>
														<input type="hidden" name="id" value="<?=$object->id?>" />
														<input style="width: 137px;" type="password" name="password" class="exp" value />
														&nbsp;&nbsp;Подтверждение:
														<input style="width: 137px;" type="password" name="passwordConfirm" />
													<? if ($object->id): ?>
														<br />
														<input style="margin: 12px 0px 0px 136px" type="submit" class="formChangePasswordSubmit" name="changePassword" value="Изменить пароль" />
														<div class="changePasswordMessage"></div>
													</div>
													<? endif; ?>
												</td>				</tr>
											<tr>
												<td class="first">Описание:</td>
												<td><textarea name="description" cols="95" rows="10"><?=$object->description?></textarea>
											</tr>
											<tr>
												<td class="td_right">Права:</td>
												<td>
													<div>
														<a class="printPartnersManagersRightsTree" data-groupId="<?=$this->getController('Managers')->partnersManagersGroupsId?>" style="cursor: pointer;">Выбрать стандартные права для менеджера партнера</a>
													</div>
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
										<p class="title">Дополнительные параметры:</p>
										<table width="100%">
											<tr>
												<td class="first">Status:</td>
												<td>
													<select name="statusId" style="width:150px;">
														<?if ($statuses): foreach($statuses as $status):?>
														<option value="<?=$status->id?>" <?= $status->id==$object->statusId ? 'selected' : ''?>><?=$status->name?></option>
														<?endforeach; endif?>
													</select>
												</td>
											</tr>
											<tr>
												<td class="first">Партнер:</td>
												<td>
													<select name="partnerId" style="width:150px;">
														<?if ($partners): foreach($partners as $partner):?>
														<option value="<?=$partner->id?>" <?if($object->id){ echo $partner->id==$object->partnerId ? 'selected' : ''; } else { echo $partner->id==$returnAction ? 'selected' : ''; }?>><?=$partner->name?></option>
														<?endforeach; endif?>
													</select>
												</td>
											</tr>
											<tr>
												<td class="first">Дата:</td>
												<td><input class="date" name="date" title="Date" value="<?=$object->date?>"/></td>
											</tr>
											<tr>
												<td class="first">Приоритет:</td>
												<td><input name="priority" value="<?=$object->priority; ?>" /></td>
											</tr>
										</table>
									</div>
								</div><!--dop_param-->
								<div class="clear"></div>
							</div><!--main_edit-->
						</div>
					</div>
				</form>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
    <?include(TEMPLATES_ADMIN.'footer.tpl');?>
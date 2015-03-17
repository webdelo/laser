<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="formEditSubmit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<a href="/admin/"><img src="/admin/images/buttons/back.png" alt="" /> Закрыть</a>
				</div>
				<p class="speedbar"><a href="/admin/">Главная</a>     <span>></span>
					Настройки
				</p>
				<div class="clear"></div>
				<div class="main_param">
					<div class="col_in">
						<form class="formEdit" action="/admin/settings/saveSettings" method="post">
							 <table id="settings">
								 <?foreach ($infos as $key=>$info):?>
								<tr>
									<td><?=$info['title']?>:</td>
									<td>
									<?if ($info['name'] == 'week_f_day'):?>
									<select name="settings[<?=$info['id']?>][<?=$info['name']?>]">
										<option value="0" <?=($info['value'] == 0) ? 'selected' : ''?> >Воскресение</option>
										<option value="1" <?=($info['value'] == 1) ? 'selected' : ''?> >Понедельник</option>
									</select>
									<?else:?>
									<input type="text" name="settings[<?=$info['id']?>][<?=$info['name']?>]" value="<?=$info['value']?>" />
									<?endif?>
								  </tr>
								<?endforeach?>
							</table>
						</form>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
<?include(TEMPLATES_ADMIN.'footer.tpl');?>
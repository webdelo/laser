					<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
					<form class="formEdit" action="/admin/domainsInfo/editDomainInfo/" method="post" data-post-action="none">
						<input type="hidden" name="id" value="<?=$good->id?>" id="objectId"/>
						<input type="hidden" name="domainAlias" value="<?=$domainAlias?>" id="domainAlias"/>
						<div id="tabs">
							<div class="tab_page">
								<ul>
									<li>
										<a href="#main">Параметры</a>
									</li>
								</ul>
							</div>
							<div id="main">
							<div class="main_edit">
								<div class="main_param">
									<p class="title">Мета-данные <?=$domainAlias?>:</p>
										<table width="100%">
											<tr>
												<td class="first">Мета-title:</td>
												<td><input type="text" name="metaTitle" value="<?=$object->metaTitle?>" /></td>
											</tr>
											<tr>
												<td class="first">Мета-keywords:</td>
												<td><textarea name="metaKeywords" cols="95" rows="10"><?=$object->metaKeywords?></textarea>
											</tr>
											<tr>
												<td class="first">Мета-description:</td>
												<td><textarea name="metaDescription" cols="95" rows="10"><?=$object->metaDescription?></textarea>
											</tr>
										</table>
									<div class="col_in">
										<p class="title">Основные параметры для <?=$domainAlias?>:</p>
										<table width="100%">
											<tr>
												<td class="first">Имя:</td>
												<td><input type="text" name="name" value="<?=$object->name?>" /></td>
											</tr>
											<tr>
												<td class="first">Текст для шапки сайта:</td>
												<td><input type="text" name="headerText" value="<?=$object->headerText?>" /></td>
											</tr>
											<tr>
												<td class="first">Короткое описание:</td>
												<td><textarea name="smallDescription" cols="95" rows="10"><?=$object->smallDescription?></textarea>
											</tr>
											<tr>
												<td class="first">Описание:</td>
												<td><textarea name="description" cols="95" rows="10"><?=$object->description?></textarea>
											</tr>
											<tr>
												<td class="first">Текст:</td>
												<td><textarea name="text" cols="95" rows="10"><?=$object->text?></textarea>
											</tr>
										</table>
									</div>
								</div><!--main_param-->
								<div class="dop_param">
									<div class="col_in">
										<p class="title">Дополнительные параметры:</p>
										<table width="100%">
											<tr>
												<td class="first">Алиас:</td>
												<td><input name="alias" value="<?=$object->alias?>" /></td>
											</tr>
											<tr>
												<td class="first">Код:</td>
												<td><input name="code" value="<?=$object->code?>" /></td>
											</tr>
<?if ($object->id):?>
											<tr>
												<td class="first" colspan="2" style="text-align: center;">
													<a href="<?=$good->getPathByDomainAlias($domainAlias);?>" title="Открыть объект в клиентской части" target="_blank">
														Просмотреть объект
													</a>
												</td>
											</tr>
<?endif;?>
										</table>
									</div>
								</div><!--dop_param-->
								<? //$this->includeTemplate('images')?>
								<div class="clear"></div>
							</div><!--main_edit-->
						</div><!--main-->
					</form>
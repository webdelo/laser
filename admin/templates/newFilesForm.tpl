		<? if($object->id): ?>
		<div
			class="filesAddForm"
			data-action="/admin/<?=$this->getREQUEST()['controller']?>/addFilesFromEditPage/<?=$object->id?>/"
			data-method="post"
			data-post-action="<?=$_SERVER['REQUEST_URI']?>"
		>
		<? endif; ?>
			<div id="placeForQueueFiles"></div>
			<div class="progressBlock hide">
				<div class="progress"></div>
				<div class="progressDescription">Загружаются файлы...</div>
			</div>
			<div class="hide">
				<div class="filesForm example">
					<input class="name" type="hidden" name="" value="" />
					<input class="tmpName" type="hidden" name="" value="" />
					<div class="edit_file">
						<table width="100%">
							<tbody>
								<tr>
									<td class="item">
										<div class="file"></div>
										<table class="info_file" align="left">
											<tbody>
												<tr>
													<td class="title"><span>Title:</span> </td>
													<td class="title"><input type="text" name="" value=""/></td>
												</tr>
												<tr>
													<td class="alias"><span>Alias:</span> </td>
													<td class="alias"><input type="text" name="" value=""/></td>
												</tr>
												<tr>
													<td class="status"><span>Status:</span></td>
													<td class="status">
														<select name="">
															<? foreach( $objects->getFilesStatuses() as $status ): ?>
																<option value="<?=$status->id?>"><?=$status->name?></option>
															<? endforeach; ?>
														</select>
													</td>
												</tr>
												<tr>
													<td class="category"><span>Category:</span></td>
													<td class="category">
														<select name="">
															<? foreach( $objects->getFilesCategories() as $category ): ?>
																<option value="<?=$category->id?>"><?=$category->name?></option>
															<? endforeach; ?>
														</select>
													</td>
												</tr>
												<tr>
													<td class="description"><span>Description:</span></td>
													<td class="description"><textarea name=""></textarea></td>
												</tr>
												<tr>
													<td></td>
													<td class="remove">
														<a href="javascript:">Удалить</a>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<? if($object->id): ?>
			<div class="clear"></div>
			<p class="save_files filesAddFormSubmitBlock hide"><a href="#saveFiles" class="filesAddFormSubmit"><button><span>Сохранить файлы <img src="/admin/images/backgrounds/save_img.png" alt=""></span></button></a></p>
		</div>
		<? endif; ?>
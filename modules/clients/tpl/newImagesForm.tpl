	<? if($object->id): ?>
	<div
		class="imagesAddForm"
		data-action="/admin/<?=$this->getREQUEST()['controller']?>/addImagesFromEditPage/<?=$object->id?>/"
		data-method="post"
		data-post-action="<?=$_SERVER['REQUEST_URI']?>"
	>
	<? endif; ?>
		<div id="placeForQueue"></div>
		<div class="progressBlock hide">
			<div class="progress"></div>
			<div class="progressDescription">Загружаются изображения...</div>
		</div>
		<div class="hide">
			<div class="imagesForm example">
				<input class="name" type="hidden" name="" value="" />
				<input class="tmpName" type="hidden" name="" value="" />
				<div class="edit_image">
					<table width="100%">
						<tbody>
							<tr>
								<td class="item">
									<div class="image"></div>
									<table class="info_image" align="left">
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
														<? foreach( $objects->getImagesStatuses() as $status ): ?>
															<option value="<?=$status->id?>"><?=$status->name?></option>
														<? endforeach; ?>
													</select>
												</td>
											</tr>
											<tr>
												<td class="category"><span>Category:</span></td>
												<td class="category">
													<select name="">
														<? foreach( $objects->getImagesCategories() as $category ): ?>
															<option value="<?=$category->id?>"><?=$category->getName()?></option>
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
		<p class="save_images imagesAddFormSubmitBlock hide"><a href="#saveImages" class="imagesAddFormSubmit"><button><span>Сохранить изображения <img src="/admin/images/backgrounds/save_img.png" alt=""></span></button></a></p>
	</div>
	<? endif; ?>

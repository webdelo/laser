			<?if($object->getFilesCategories()) foreach($object->getFilesCategories() as $item): ?>
				<?if( $object->getFilesByCategory($item->id)->current() ): ?>
					<p><?=$item->name?></p>
				<div class="table_edit">
					<table width="100%">
						<tbody>
							<tr>
								<th colspan="2" class="first">Тип файла</th>
								<th>Название / <span class="alias">Алиас</span> </th>
								<th>Статус / Категория</th>
								<th class="last" colspan="4">Размер / Дата</th>
							</tr>
							<?foreach ( $object->getFilesByCategory($item->id) as $file ): ?>
							<tr>
								<td></td>
								<td>
									<a href="/admin/<?=$_REQUEST['controller']?>/download/<?=$object->id?>/<?=$file->alias?>" title="Скачать">
										<img src="<?=$file->getFileIcon()?>" alt="">
									</a>
								</td>
								<td>
									<div class="overflow">
										<div class="sh"></div>
										<p class="name"><?= $file->title ? $file->title : $file->name?></p>
										<p class="alias"><strong><?=$file->alias?></strong></p>
									</div>
								</td>
								<td>
									<p class="status"><font color="<?=$file->getStatus()->color?>"><?=$file->getStatus()->name?></font></p>
									<p><?=$file->getCategory()->getName()?></p>
								</td>
								<td>
									<p><?=$file->getSize()?></p>
									<p><?=$file->date?></p>
								</td>
								<td>
									<?if($this->checkUserRight('showRemoveEditFiles')):?>
									<a
										href="#edit"
										title="Редактировать"
										class="editFile pen"
										data-action="/admin/<?=$_REQUEST['controller']?>/getTemplateToEditFile/<?=$file->id?>/<?=$object->id?>/"
									>
									</a>
									<?endif?>
								</td>
								<td>
									<?if($this->checkUserRight('showRemoveEditFiles')):?>
									<a
										href="#remove"
										title="Удалить"
										class="removeFile confirm del"
										data-confirm     = "Remove file?"
										data-action      = "/admin/<?=$_REQUEST['controller']?>/removeFile/<?=$file->id?>/"
									>
									</a>
									<?endif?>
								</td>
							</tr>
							<? endforeach?>
						</tbody>
					</table>
				</div>
				<?endif?>
			<?endforeach;?>
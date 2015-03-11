<div id="fileDetails" class="filesForm">
	<form class="formFileEdit" action="/admin/<?=$this->getREQUEST()['controller']?>/editFile/<?=$file->id?>/">
		<input type="hidden" name="name" value="<?=$file->name?>">
		<table>
			<tr>
				<td class="file">
					<img src="<?= $file->getFileIcon()?>" />
				</td>
				<td>
					<table>
						<tr>
							<td class="titleFile"><span>Название:</span> </td>
							<td class="titleFile"><input type="text" name="title" value="<?=$file->title?>"/></td>
						</tr>
						<tr>
							<td class="titleFile"><span>Первоначальное<br />название:</span> </td>
							<td class="titleFile"><input type="text" name="name" value="<?=$file->name?>"/></td>
						</tr>
						<tr>
							<td class="alias"><span>Алиас:</span> </td>
							<td class="alias"><input type="text" name="alias" value="<?=$file->alias?>"/></td>
						</tr>
						<tr>
							<td class="status"><span>Статус:</span></td>
							<td class="status">
								<select name="statusId">
									<? foreach( $objects->getFilesStatuses() as $status ): ?>
										<option value="<?=$status->id?>" <?=($status->id==$file->getStatus()->id)?'selected':''?>><?=$status->name?></option>
									<? endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td class="status"><span>Категория:</span></td>
							<td class="status">
								<select name="categoryId">
									<? foreach( $objects->getFilesCategories() as $category ): ?>
										<option value="<?=$category->id?>" <?=($category->id==$file->getCategory()->id)?'selected':''?>><?=$category->name?></option>
									<? endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="description">
								<span style="display: block;">Описание:</span>
								<textarea name="description"><?=$file->description?></textarea>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="4" class="remove">
					<a
						href="#remove"
						class="removeFileFromDetails confirm hide"
						data-confirm="Remove file?"
						data-action="/admin/<?=$this->getREQUEST()['controller']?>/removeFile/<?=$file->id?>/"
					>
						remove
					</a>
				</td>
			</tr>
			<tr>
				<td colspan="4" class="edit">
					<a
						href="#edit"
						class="formFileEditSubmit hide"
					>
						edit
					</a>
				</td>
			</tr>
		</table>
	</form>
</div>
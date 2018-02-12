		<? if ( $this->isNotNoop($object->getImages()) ) if( $object->getImages()->count() > 0 ): ?>
		<p class="title">Загруженные фото:</p>
		<? else: ?>
		<p class="grayText">Вы можете загрузить изображения...</p>
		<? endif; ?>
		<?if($object->getImagesCategories()) foreach($object->getImagesCategories() as $item): ?>
		<?if( $object->getImagesByCategory($item->id)->current() ): ?>
			<p><b><?=$item->getName()?></b></p>
		<?endif?>
			<div class="imagesSortable" data-action="/admin/<?=$_REQUEST['controller']?>/setPriority/?objectId=<?=$object->id?>">
		    <? foreach ( $object->getImagesByCategory($item->id) as $image ): ?>
				<div id="image<?=$image->id?>" data-id="<?=$image->id?>" class="image active">
					<div class="fileHeader hide editImage"
						data-action="/admin/<?=$_REQUEST['controller']?>/getTemplateToEditImage/<?=$image->id?>/<?=$object->id?>/"
					>
						<div class="fileMenu">
							<ul>
								<li>
									<a
										href="#primary"
										class="setPrimary<?=($image->isPrimary()) ? ' hide' : '' ?> editPrimary"
										data-action="/admin/<?=$_REQUEST['controller']?>/setPrimary/<?=$image->id?>/"
									>
										главное
									</a>
									<a
										href="#resetPrimary"
										class="resetPrimary<?=($image->isPrimary()) ? '' : ' hide' ?> editPrimary"
										data-action="/admin/<?=$_REQUEST['controller']?>/resetPrimary/<?=$image->id?>/"
									>
										в список
									</a>
								</li>
								<li>
									<a
										href="#block"
										class="setBlock<?=($image->isBlocked()) ? ' hide' : '' ?> editBlocking"
										data-action="/admin/<?=$_REQUEST['controller']?>/setBlock/<?=$image->id?>/"
									>
										заблок.
									</a>
									<a
										href="#unblock"
										class="resetBlock<?=($image->isBlocked()) ? '' : ' hide' ?> editBlocking"
										data-action="/admin/<?=$_REQUEST['controller']?>/resetBlock/<?=$image->id?>/"
									>
										разблок.
									</a>
								</li>
								<li>
									<a
										href="#edit"
										class="editImage"
										data-action="/admin/<?=$_REQUEST['controller']?>/getTemplateToEditImage/<?=$image->id?>/<?=$object->id?>/"
									>
										редакт.
									</a>
								</li>
								<li>
									<a
										href="#remove"
										class="removeImage confirm"
										data-confirm     = "Remove image?"
										data-action      = "/admin/<?=$_REQUEST['controller']?>/removeImage/<?=$image->id?>/"
									>
										удалить
									</a>
								</li>
							</ul>
						</div>
						<span class="fileTitle"><?=$image->name?></span>
						<span class="fileDescription"><?=$image->description?></span>
					</div>
					<a href="<?=$image->getImage('800x600')?>" class="lightbox">
						<img src="<?=$image->getImage('240x180')?>" />
					</a>
					<p>Приоритет - <?= $image->priority?></p>
					<p>ID - <?= $image->id?></p>
					<p><?=  str_replace(DIR, '/', $image->getPath())?></p>
				</div>
				<? endforeach; ?>
			</div>
			<div class="clear"></div>
			<br /><br /><br />
		<?endforeach;?>
<?include(TEMPLATES_ADMIN.'top.tpl');?>
		<script type="text/javascript" src="/modules/catalog/domainsInfo/js/domainsInfoHandler.js"></script>
		<script type="text/javascript" src="/modules/catalog/domainsInfo/js/domainsInfo.class.js"></script>
		<script type="text/javascript" src="/js/ajaxLoader.class.js"></script>
		<div class="main single">
			<div class="max_width">
				<div class="action_buts">
					<a class="form<?= $object->id ? 'Edit' : 'Add'?>Submit pointer" ><img src="/admin/images/buttons/save_object.png" alt="" /> Сохранить</a>
					<? if ($object->id):?>
						<a class="button confirm pointer" data-confirm= "Remove the device?" data-action="/admin/devices/remove/<?=$object->id?>/"
							data-callback="postRemoveFromDetails" data-post-action="/admin/devices/" >
							<img src="/admin/images/buttons/delete.png" alt="" /> Удалить
						</a>
					<? endif;?> 
					<a href="/admin/devices/"><img src="/admin/images/buttons/back.png" alt="" /> Вернуться</a>
				</div>
				<p class="speedbar">
					<a href="/admin/">Главная</a><span> > </span>
					<a href="/admin/devices/">Девайсы</a><span> > </span>
					<?= $object->id ? $object->getName() : 'Добавление'?>
				</p>
				<div class="clear"></div>
				<div class="sitebar">
                    <a class="pointer domainInfoLink active" data-domainalias="main" data-controller="devices">Общая информация</a>
<?if ($object->id):?>
					<?foreach ($this->getController('ModulesDomain')->getDomainsByModuleAlias('devices') as $key => $value):?>
                    <a class="pointer domainInfoLink" data-domainalias="<?=$value?>"><?=$value?></a>
					<?endforeach;?>
<?endif;?>
                    <div class="clear"></div><!--end clear-->
                </div><!--end sitebar-->
				<div id="contentBlock">
<?$this->getMainContentBlock($object->id)?>
				</div>
			</div>
		</div><!--main-->
		<div class="vote"></div>
	</div><!--root-->
    <?include(TEMPLATES_ADMIN.'footer.tpl');?>
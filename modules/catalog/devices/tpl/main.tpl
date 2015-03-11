	<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
	<script type="text/javascript" src="/modules/catalog/js/additionalCategories.js"></script>
	<script type="text/javascript" src="/admin/js/jquery/multi-select/multi-select.js"></script>
	<link rel="stylesheet" type="text/css" href="/admin/js/jquery/multi-select/multi-select.css" />
	<?if ($object->id):?><input type="hidden" value="<?=$object->id?>" id="objectId"/><?endif;?>
	<form class="form<?= $object->id ? 'Edit' : 'Add'?>" action="/admin/devices/<?= $object->id ? 'edit' : 'add'?>/" method="post" data-post-action=""> 
	<div id="tabs">
		<div class="tab_page">
			<ul>
			<?foreach ($tabs as $tab=>$tabName):?>
				<li>
					<a href="#<?=$tab?>"><?=$tabName?></a>
				</li>
			<?endforeach?>
			</ul>
		</div>
		<? foreach ($tabs as $tab=>$tabName): ?>
			<div id="<?=$tab?>">
				<?$this->includeTemplate($tab); ?>
			</div>
		<? endforeach; ?>
	</div>
	</form>

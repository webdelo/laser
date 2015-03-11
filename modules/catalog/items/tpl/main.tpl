					<script type="text/javascript" src="/admin/js/base/system/tabs.js"></script>
					<script type="text/javascript" src="/modules/catalog/js/additionalCategories.js"></script>
					<script type="text/javascript" src="/admin/js/jquery/multi-select/multi-select.js"></script>
					<link rel="stylesheet" type="text/css" href="/admin/js/jquery/multi-select/multi-select.css" />

					<link rel="stylesheet" type="text/css" href="/modules/catalog/subGoods/css/style.css">
					<script type="text/javascript" src="/modules/catalog/subGoods/js/subGoodsHandler.js"></script>
					<script type="text/javascript" src="/modules/catalog/subGoods/js/subGoods.class.js"></script>

					<script type="text/javascript" src="/js/ajaxLoader.class.js"></script>
					<script type="text/javascript" src="/modules/orders/js/autosuggest/autosuggest.js"></script>
					<script type="text/javascript" src="/modules/orders/js/autosuggest/jquery.autoSuggest.js"></script>
					<link rel="stylesheet" type="text/css" href="/modules/orders/css/autoSuggest.css" />

					<script type="text/javascript" src="/admin/js/base/actions/selectsFalls.class.js"></script>
					<script type="text/javascript" src="/modules/catalog/items/js/parametersSave.js"></script>
					<script type="text/javascript" src="/modules/catalog/items/js/propertiesSave.js"></script>

					<script type="text/javascript" src="/modules/catalog/items/js/selectFabricatorHandler.js"></script>

					<form class="form<?= $object->id ? 'Edit' : 'Add'?>" action="/admin/catalog/<?= $object->id ? 'edit' : 'add'?>/" method="post" data-post-action="<?= $object->id ? 'none' : '/admin/catalog/catalogItem/'?>">
						<?if ($object->id):?><input type="hidden" value="<?=$object->id?>" id="objectId"/><?endif;?>
						<div id="tabs">
							<div class="tab_page">
								<ul>
									<? foreach ($tabs as $tab=>$tabName): ?>
									<li>
										<a href="#<?=$tab?>"><?=$tabName?></a>
									</li>
									<? endforeach; ?>
								</ul>
							</div>

							<?foreach ($tabs as $tab=>$tabName):?>
							<div id="<?=$tab?>">
								<? $this->includeTemplate($tab); ?>
							</div>
							<?endforeach?>
						</div>
					</form>
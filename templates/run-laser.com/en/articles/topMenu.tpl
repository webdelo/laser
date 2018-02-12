<? $ItemNr = 0; ?>
<li <?=$this->getREQUEST()['action'] == '' ? 'class="active"' : ''?>><a href="/"><?=$this->getController('Article')->getMainArticleName()?></a></li>
<?foreach($topMenu as $item):?>
<?$ItemNr++;?>
<li <?=$this->getREQUEST()['action'] == $item->alias ? 'class="active"' : ''?>><a href="<?=$item->getPath()?>"><?=$item->getName()?></a></li>
<?if($ItemNr != $totalItems):?> <?endif?>
<?endforeach;?>
<? $ItemNr = 0; ?>
<li <?=$this->getREQUEST()['action'] == '' ? 'class="active"' : ''?>><a href="/">Главная</a></li>
<?foreach($topMenu as $item):?>
<?$ItemNr++;?>
<li <?=$this->getREQUEST()['action'] == $item->alias ? 'class="active"' : ''?>><a href="<?=$item->getPath()?>"><?=$item->name?></a></li>
<?if($ItemNr != $totalItems):?> <?endif?>
<?endforeach;?>
<script type="text/javascript" src="/admin/templates/addressesDecoratorTrait/js/addressTrait.js"></script>
<link rel="stylesheet" type="text/css" href="/admin/templates/addressesDecoratorTrait/css/addressTrait.css" />
<link rel="stylesheet" type="text/css" href="/admin/js/jquery/autosuggest/autoSuggest.css">
<script type="text/javascript" src="/admin/js/jquery/autosuggest/jquery.autoSuggest.js"></script>
<div class="addressDataBlock" data-source="/admin/<?=$this->getREQUEST()['controller']?>/ajaxGetAddressesTemplate/<?=$object->id?>/">
	<? $this->getAddressesTemplate($object->id) ?>
</div>
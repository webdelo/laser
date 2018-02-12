<script type="text/javascript" src="/admin/templates/clientDecoratorTrait/js/clientTrait.js"></script>
<link rel="stylesheet" type="text/css" href="/admin/templates/clientDecoratorTrait/css/clientTrait.css" />
<link rel="stylesheet" type="text/css" href="/admin/js/jquery/autosuggest/autoSuggest.css">
<script type="text/javascript" src="/admin/js/jquery/autosuggest/jquery.autoSuggest.js"></script>
<div class="clientDataBlock" data-source="/admin/<?=$this->getREQUEST()['controller']?>/ajaxGetClientTemplate/<?=$object->id?>/">
	<? $this->getClientTemplate($object->id) ?>
</div>
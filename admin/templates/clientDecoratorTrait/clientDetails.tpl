<div class="clientDetails">
	<a href="/admin/clients/client/<?=$object->getClient()->id?>/" target="_blank"><?=$object->getClient()->getAllName()?></a>
	<div class="additionals">e-mail: <strong><?=$object->getClient()->getLogin()?></strong></div>
	<div class="additionals">номер телефона: <strong><?=$object->getClient()->phone?></strong></div>
	<div class="additionals">доп. номер: <strong><?=$object->getClient()->getMobile()?></strong></div>

	<a 
		class="deleteClient confirm" 
		data-action="/admin/<?=$this->getREQUEST()['controller']?>/ajaxDeleteClientRelation/<?=$object->id?>/"
		data-confirm="Удалить связь?"
	>удалить</a>
</div>
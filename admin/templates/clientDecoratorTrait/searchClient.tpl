<div class="clientSearchBlock" data-action="/admin/<?=$this->getREQUEST()['controller']?>/ajaxClientRelationSave/">
	<input type="hidden" name="id" value="<?=$object->id?>"/>
	<input type="text" name="searchClients" class="clientSearchInput" data-action="/admin/<?=$this->getREQUEST()['controller']?>/ajaxSearchClientsToAutosuggest/" />
	<input class="clientSearchBlockSubmit" type="hidden" name="submit" value="Сохранить"/>
</div>
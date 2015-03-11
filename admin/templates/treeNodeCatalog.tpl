<option name="treeListCatalog[]" value="<?=$node->id?>" <?if ($this->confirmObject->objectExists($node->id)):?>checked="checked"<?endif;?> /> <?=$node->name?></option>
<?=$this->appendList($node->childs);?>

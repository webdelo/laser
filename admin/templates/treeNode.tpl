<li>
	<label>
		<input type="checkbox" name="treeList[]" value="<?=$node->id?>" <?if ($this->confirmObject->objectExists($node->id)):?>checked="checked"<?endif;?> /> <?=$node->name?>
	</label>
	<?=$this->appendList($node->childs);?>
</li>
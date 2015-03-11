<?php
namespace core\modules\utils\tree;
class TreePrinter
{
	private $treeObject;
	private $confirmObject;
	private $listTemplatePath;
	private $nodeTemplatePath;

	public function __construct($listTemplatePath, $nodeTemplatePath, $treeObject, $confirmObject = null)
	{
		$this->setConfirmObject($confirmObject)
			 ->setTreeObject($treeObject)
			 ->setTemplates($listTemplatePath, $nodeTemplatePath);
	}

	private function setTreeObject($treeObject)
	{
		$this->treeObject = $treeObject;
		return $this->checkTreeObject();
	}

	private function checkTreeObject()
	{
		if ($this->treeObject instanceof TreeGenerator)
			return $this;
		throw new \Exception('Tree Object is not an interface TreeGenerator in class '.get_class($this).'!');
	}

	private function setConfirmObject($confirmObject)
	{
		$this->confirmObject = $confirmObject;
		return $this->checkConfirmObject();
	}

	private function checkConfirmObject()
	{
		if (!isset($this->confirmObject))
			$this->confirmObject = new \core\Noop();
		return $this;
	}

	private function setTemplates($listTemplatePath, $nodeTemplatePath)
	{
		$this->listTemplatePath = $listTemplatePath;
		$this->nodeTemplatePath = $nodeTemplatePath;
		return $this->checkTemplates();
	}

	private function checkTemplates()
	{
		if ($this->listTemplatePath && $this->nodeTemplatePath)
			return $this;
		throw new \Exception('Templates path variables is empty in class '.get_class($this).'!');
	}

	public function getHTML()
	{
		return $this->appendList($this->treeObject);
	}

	private function appendList($treeListWrapper)
	{
		$nodes = '';
		if (isset($treeListWrapper)){
			foreach ($treeListWrapper as $node) {
				$nodes .= $this->appendNode($node);
			}
			ob_start();
			include ($this->listTemplatePath);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
	}

	private function appendNode($node)
	{
		ob_start();
		include ($this->nodeTemplatePath);
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
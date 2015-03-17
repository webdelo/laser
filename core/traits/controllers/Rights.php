<?php
// Requires: \core\traits\controllers\Authorization
// Requires: \core\traits\controllers\ServiceRequests
// Requires: \core\traits\ObjectPool
namespace core\traits\controllers;
trait Rights
{
	protected function checkUserRight($alias)
	{
		return $this->getAuthorizatedUser()->getRights()->checkRightByAlias($alias);
	}

	protected function checkUserRightById($rightID)
	{
		return $this->getAuthorizatedUser()->getRights()->checkRightById($rightID);
	}

	protected function checkUserRightAndBlock($alias, $noMessage = null)
	{
		$rights = $this->getObject('\core\modules\rights\Rights');
		if ($this->getAuthorizatedUser()->getRights()->checkRightByAlias($alias))
			return $this;
		$rights = $this->getObject('\core\modules\rights\Rights');
		$rightId = $rights->getIdByAlias($alias);
		$right = $this->getObject('\core\modules\rights\Right', $rightId);
		$noMessage == 'noMessage'   ?   ''   :   $this->accessDenied($right);
		throw new \exceptions\ExceptionAccess();
	}

	protected function getRightsTree ($userRights)
	{
		$rights = new \core\modules\rights\Rights();
		$rightsTree = $rights->getTree();
		$treePrinter = new \core\modules\utils\tree\TreePrinter(TEMPLATES_ADMIN.'treeList.tpl', TEMPLATES_ADMIN.'treeNode.tpl', $rightsTree, $userRights);
		return $treePrinter->getHTML();
	}
}
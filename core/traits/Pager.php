<?php
namespace core\traits;
trait Pager
{
	protected function printPager($pagerClass,$template)
	{
		$this->setContent('pager',$pagerClass)
			 ->includeTemplate($template);
	}

	protected function isFirstPage()
	{
		if(!isset($_REQUEST['page']))
			return true;
		if($_REQUEST['page'] == 1)
			return true;
		return ! is_numeric($_REQUEST['page']);
	}
}

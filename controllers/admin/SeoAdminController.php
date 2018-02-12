<?php
namespace controllers\admin;
class SeoAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization;
	
	protected $permissibleActions = array(
		'utilites',
		'utilite',
		'analizeLinks',
	);
	
	public function  __construct()
	{
		parent::__construct();
	}

	public function defaultAction()
	{
		$this->utilites();
	}
	
	protected function utilites()
	{
		$this->includeTemplate(DIR.'modules/seo/utilites');
	}
	
	protected function utilite()
	{
		$module = $this->getREQUEST()[0];
		if (method_exists($this, $module))
			return $this->$module();
		$this->redirect404();
	}
	
	private function linksFilter()
	{
		$this->includeTemplate(DIR.'modules/seo/linksFilter/tpl/main');
	}
	
	protected function analizeLinks()
	{
		\core\utils\Directories::makeDirsRecusive('/tmp/linksFilter/');
		$uploadfile = DIR.'/tmp/linksFilter/'.time().'_links.csv';
		$result = @move_uploaded_file($_FILES['csv']['tmp_name'], $uploadfile);
		if ($result && $this->getPOST()['domain']){
			$file = new \core\files\uploader\File($uploadfile);
			$linksFilter = new \modules\seo\linksFilter\lib\LinksFilter($file, $this->getPOST()['searchQuery']);
			$linksFilter->setStopWords($this->getPOST()['stopWords'])
						->setDomain($this->getPOST()['domain']);
			$this->setContent('linksFilter', $linksFilter)
				 ->includeTemplate(DIR.'modules/seo/linksFilter/tpl/result');
			$file->delete();
		} else {
			$error = '';
			if (!$result){
				$error = 'Не указан файл выгрузки или ошибка доступа на сервере';
				if (!$this->getPOST()['domain'])
					$error .= '<br/>';
			}
			if (empty($this->getPOST()['domain']))
				$error .= 'Не указан домен анализируемого сайта';
			$this->setContent('error', $error)
				 ->includeTemplate(DIR.'modules/seo/linksFilter/tpl/main');
		}
	}
}
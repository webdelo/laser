<?php
namespace controllers\admin;
class ArticlesAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\TranslateCategories,
		\core\traits\controllers\Images,
		\core\traits\controllers\Files,
		\core\traits\controllers\Rights,
		\core\traits\controllers\Templates,
		\core\traits\controllers\Authorization,
		\core\traits\Pager,
		\controllers\admin\traits\ListActionsAdminControllerTrait;

	const STATUS_DELETED = 3;

	protected $permissibleActions = array(
		'articles',
		'add',
		'edit',
		'article',
		'remove',

		/* Start: List Trait Methods*/
		'changePriority',
		'groupActions',
		'groupRemove',
		/* End: List Trait Methods*/

		/* Start: Categories Trait Methods*/
		'categories',
		'categoryAdd',
		'categoryEdit',
		'category',
		'removeCategory',
		'getMainCategories',
		'changeCategoriesPriority',
		/*   End: Categories Trait Methods*/

		/* Start: Images Trait Methods*/
		'uploadImage',
		'addImagesFromEditPage',
		'removeImage',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editImage',
		'getTemplateToEditImage',
		'ajaxGetImagesBlock',
		'ajaxGetImagesListBlock',
		/*   End: Images Trait Methods*/

		/* Start: Files Trait Methods*/
		'uploadFile',
		'addFilesFromEditPage',
		'removeFile',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editFile',
		'getTemplateToEditFile',
		'ajaxGetFilesBlock',
		'ajaxGetFilesListBlock',
		'getFileIcon',
		'download'
		/*   End: Files Trait Methods*/
	);

	public function  __construct()
	{
		parent::__construct();
		$this->_config = new \modules\articles\lib\ArticleConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	protected function defaultAction()
	{
		return $this->articles();
	}

	protected function articles ()
	{
		$this->checkUserRightAndBlock('articles');
		$this->rememberPastPageList($_REQUEST['controller']);

		$this->setObject($this->objectsClass);
		$start_date = (empty($this->getGET()['start_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['start_date']);
		$end_date = (empty($this->getGET()['end_date'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['end_date']);
		$status = (empty($this->getGET()['statusId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['statusId']);
		$category = (empty($this->getGET()['categoryId'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['categoryId']);
		$id = (empty($this->getGET()['id'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['id']);
		$alias = (empty($this->getGET()['alias'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['alias']);
		$name = (empty($this->getGET()['name'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['name']);
		$description = (empty($this->getGET()['description'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['description']);
		$text = (empty($this->getGET()['text'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['text']);
		$itemsOnPage = (empty($this->getGET()['itemsOnPage'])) ? '' : \core\utils\DataAdapt::textValid($this->getGET()['itemsOnPage']);

		if (!empty($this->getGET()['id']))
			$this->modelObject->setSubquery('AND `id` = ?d', $this->getGET()['id']);

		if (!empty($start_date))
			$this->modelObject->setSubquery('AND `date` >= ?d', \core\utils\Dates::convertDate($start_date));

		if (!empty($end_date))
			$this->modelObject->setSubquery('AND `date` <= ?d', \core\utils\Dates::convertDate($end_date));

		if (!empty($category))
			$this->modelObject->setSubquery('AND `categoryId` = ?d', $category);

		if (!empty($status))
			$this->modelObject->setSubquery('AND `statusId` = ?d', $status);

		if (!empty($id))
			$this->modelObject->setSubquery('AND `id` = ?d', $id);

		if (!empty($description))
			$this->modelObject->setSubquery('AND `description` LIKE \'%?s%\'', $description);

		if (!empty($alias))
			$this->modelObject->setSubquery('AND LOWER(`alias`) LIKE \'%?s%\'', strtolower($alias));

		if (!empty($name))
			$this->modelObject->setSubquery('AND LOWER(`name`) LIKE \'%?s%\'', strtolower($name));

		if (!empty($text))
			$this->modelObject->setSubquery('AND `text` LIKE \'%?s%\'', $text);

		$this->modelObject->setOrderBy('`priority` ASC')->setPager($itemsOnPage);

		$this->setContent('objects', $this->modelObject)
			->setContent('pager', $this->modelObject->getPager())
			->setContent('pagesList', $this->modelObject->getQuantityItemsOnSubpageListArray())
			->includeTemplate($this->_config->getAdminTemplateDir().'articles');
	}

	protected function add()
	{
		$this->checkUserRightAndBlock('article_add');
		$objectId =  $this->setObject($this->_config->getObjectsClass())->modelObject->add($this->getPOST(), $this->modelObject->getConfig()->getObjectFields());
		if ($objectId) {
			$this->setObject($this->_config->getObjectClass(), $objectId)
				 ->addImages();
		}
		$this->ajax($objectId, 'ajax', true);
	}

	protected function edit()
	{
		$this->checkUserRightAndBlock('article_edit');
		$this->setObject($this->_config->getObjectClass(), (int)$this->getPOST()['id'])->ajax($this->modelObject->edit($this->getPOST(), $this->modelObject->getConfig()->getObjectFields()), 'ajax', true);
	}

	protected function article()
	{
		$this->checkUserRightAndBlock('article');
		$this->useRememberPastPageList();

		$article = new \core\Noop();
		if (isset($this->getREQUEST()[0]))
			$article = $this->getObject($this->_config->getObjectClass(), $this->getREQUEST()[0]);

		$tabs = array('editArticle' => 'Параметры и фото');
		$article->id ? $tabs = array_merge($tabs, array('files' => 'Файлы')) : '';

		$articles = new $this->objectsClass;
		$this->setContent('article', $article)
			 ->setContent('object', $article) // Need for images template
			 ->setContent('objects', $articles) // Need for images template
			 ->setContent('tabs', $tabs)
			 ->setContent('articles', $articles)
			 ->setContent('statuses', $articles->getStatuses())
			 ->setContent('mainCategories', $articles->getMainCategories(1))
			 ->includeTemplate($this->_config->getAdminTemplateDir().'article');
	}

	protected function remove()
	{
		$this->checkUserRightAndBlock('article_delete');
		if (isset($this->getREQUEST()[0]))
			$articleId = (int)$this->getREQUEST()[0];

		if (!empty($articleId)) {
			$article = $this->getObject($this->objectClass, $articleId);
			$this->ajaxResponse($article->remove());
		}
	}

	public function test ()
	{
		set_time_limit(0);
		$tables = array(
			'articles_images',
			'catalog_constructions_images',
//			'catalog_devices_images',
			'catalog_domainsinfo_images',
			'offers_images',
		);
		foreach($tables as $table) {
			$images = \core\db\Db::getMysql()->rowsAssoc('SELECT `alias`, `id` FROM `tbl_'.$table.'` WHERE 1=1');
			echo '===== '.$table.' =====<br>';
			foreach($images as $image) {
				$image['alias'] = strtolower(\core\utils\Utils::translit($image['alias']));
//				echo $image['alias'].'<br>';
//				$res = \core\db\Db::getMysql()->query('UPDATE `tbl_'.$table.'` SET `alias`=\''.$image['alias'].'\' WHERE id='.$image['id']);
//				var_dump($res);
			}
		}
	}

}

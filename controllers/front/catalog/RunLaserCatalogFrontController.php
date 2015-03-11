<?php
namespace controllers\front\catalog;
class RunLaserCatalogFrontController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Meta,
		\core\traits\controllers\Templates,
		\core\traits\composition\RequestLevels_RequestHandler,
		\core\traits\Pager,
		\core\traits\controllers\Authorization,
		\core\traits\controllers\Breadcrumbs,
		\core\traits\ObjectPool;

	const ACTIVE_CATEGORY_STATUS = 1;
	const NUMBER_OF_ADDITIONAL_GOODS = 13;
	const NUMBER_OF_OFFERS_IN_LEFT_COLUMN_AND_INDEX = 2;

	const NUMBER_OF_ELEMENTS_IN_INDEX_CATEGORY_BLOCK = 9;
	const FIRST_INDEX_CATEGORY_ID = 9;
	const SECOND_INDEX_CATEGORY_ID = 96;
	
	const QUANTITY_ITEMS_ON_SUBPAGE = 12;
	const QUANTITY_ITEMS_ON_INDEX = 9;
	
	const NO_PAGINATION_QUANTITY_ITEMS = 1000000;
	
	const SOLD_CATEGORY_ID = 7;
	
	protected $permissibleActions = array(
		'getLeftCategoriesBlock',
		'getHeaderCategories',
		'getFooterCategories',
		'getredirects',
		'search',
		'getListObjects',
		'getAdditionalGoodsBlock',
		'getConstructionsByCategoryId',
		'getCategoryById',
		'getLeftOffersBlock',
		'getItemPropertyByAlias',
		
		'reviews',
		
		'test3',
		'test2',
		
		'test22',
		
		'getCatalogListContentItemBlock',
	);
	
	protected $sortingValues = array(
		'price' => array(
			'up' => '(SELECT MAX( `tbl_catalog_items_prices`.`price` ) FROM `tbl_catalog_items_prices` WHERE `tbl_catalog_items_prices`.`objectId` = mt.`id`
				) ASC',
			'down' => '(SELECT MAX( `tbl_catalog_items_prices`.`price` ) FROM `tbl_catalog_items_prices` WHERE `tbl_catalog_items_prices`.`objectId` = mt.`id`
				) DESC'
		) ,
		'novelty' => array(
			'up' => '`date` ASC',
			'down' => '`date` DESC'
		),
	);
	
	protected $sizesRelations = array(
		'40' => '184',
		'42' => '185',
		'44' => '188',
		'46' => '189',
		'48' => '190',
		'50' => '186',
		'52' => '187',
		'54' => '191',
		'56' => '192',
		'58' => '193',
		'60' => '194'
	);

	protected $catalog;
	protected $currencyExponent;
	protected $currencyTermination;
	
		public function __construct()
	{
		parent::__construct();
		$this->_config = new \modules\catalog\items\lib\CatalogItemConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
	}

	public function __call($name, $arguments)
	{
		if (empty($name))
			return $this->defaultAction();
		elseif ($this->setAction($name)->isPermissibleAction())
			return $this->callAction($arguments);
		else
			return $this->pageDetect();
	}

	protected function defaultAction()
	{
		$this->index();
	}

	protected function index()
	{
		$featuredObjects = $this->getActiveObjects();
		$featuredObjects->setSubquery('AND `categoryId` NOT IN (?s)', self::SOLD_CATEGORY_ID);
		$sortFeatured = $this->sortingValues['novelty']['up'];
		
		$featuredObjects->setOrderBy($sortFeatured)
						->setQuantityItemsOnSubpageList(array(self::QUANTITY_ITEMS_ON_INDEX))
						->setPager(self::QUANTITY_ITEMS_ON_INDEX);
		
		$this->setContent('objects', $featuredObjects)
			 ->setContent('parameters', $this->getParametersBlock())
			 ->includeTemplate('index');
	}
	
	protected function getListObjects($objects, $category = null)
	{
		$this->setContent('objects', $objects)
			 ->setContent('category', isset($category) ? $category : $this->getNoop())
			 ->includeTemplate('catalog/catalogListObjectsContent');
	}

	protected function pageDetect()
	{
		$alias = $this->getLastElementFromRequest();
		$good = $this->getGoodByAlias($alias);
		if ($good){
			if ($this->checkObjectPath($good))
				return $this->viewGood($good);
		}
		$category = $this->getCatalogObject()->getCategories()->getObjectByAlias($alias);
		if ($category){
			if ($this->checkObjectPath($category))
				return $this->viewCategory($alias);
		}

		$this->sendRequestToArticlesController();
	}

	protected function checkCategory()
	{
		$alias = $this->getLastElementFromRequest();
		$category = $this->getCatalogObject()->getCategories()->getObjectByAlias($alias);
		if ($category){
			if ($this->checkObjectPath($category))
				return true;
		}
	}
	
	protected function viewCategory($alias)
	{
		$category = $this->getCatalogObject()->getCategories()->getObjectByAlias($alias);
		$this->setLevel($category->getParent()->name, $category->getParent()->getPath())
			 ->setLevel($category->getName());
		
		$this->setContent('category', $category)
			 ->setMetaFromObject($category)
			 ->includeTemplate('catalog/catalogList');
	}

	protected function getCatalogObject()
	{
		if (empty($this->catalog))
			$this->catalog = new \modules\catalog\items\lib\Catalog();
		return $this->catalog;
	}

	private function getExludedStatusesArray()
	{
		return $exludedStatuses = array(
			\modules\catalog\items\lib\CatalogItemConfig::BLOCKED_STATUS_ID,
			\modules\catalog\items\lib\CatalogItemConfig::REMOVED_STATUS_ID,
		);
	}

	protected function getGoodByAlias($alias)
	{
		return \modules\catalog\CatalogFactory::getInstance()->getGoodByAlias($alias, $this->getCurrentDomainAlias());
	}

	protected function checkObjectPath($object)
	{
		return rtrim($object->getPath(), '/') == rtrim(str_replace('?'.$this->getSERVER()['REDIRECT_QUERY_STRING'], '', $this->getSERVER()['REQUEST_URI']), '/');
	}

	protected function viewGood($good)
	{
		if ($good->statusId == 9) { $this->redirect404(); }
		$this->setLevels($good);
		$this->setMetaFromObject($good)
			 ->setContent('object', $good)
			 ->setContent('objects', $this->getCatalogObject())
			 ->setContent('parameters', $this->getParametersBlock())
			 ->setContent('sizeProp', $this->getPropertiesListByAlias('specs'))
			 ->includeTemplate('/catalog/catalogObject');
	}

	protected function sendRequestToArticlesController()
	{
		$action = $this->getREQUEST()['action'];
		$this->getController('Article')->$action();
	}

	protected function getLeftCategoriesBlock()
	{
	    $this->setContent('categories', $this->getConstructionsObject()->getMainCategories(self::ACTIVE_CATEGORY_STATUS))
		     ->includeTemplate('catalog/leftCategoriesMenu');
	}

	protected function getHeaderCategories()
	{
		$this->setContent('topMenu', $this->getMainCategoriesTop())
			 ->includeTemplate('catalog/topMenu');
	}

	protected function getFooterCategories()
	{
		$this->setContent('footerMenu', $this->getMainCategoriesTop())
			 ->includeTemplate('catalog/footerMenu');
	}

	protected function getredirects()
	{
		$file = file('./redirect/InformatorsGoods.csv');
		$filestreem = fopen('./redirect/redirect_new.csv', 'w+');
		foreach ($file as $row){
			$data = explode(';', $row);
			$data[0] = strtoupper($data[0]);
			echo '<pre>';
			var_dump(\modules\catalog\CatalogFactory::getInstance()->codeExists($data[0]), $data[0]);
			if (\modules\catalog\CatalogFactory::getInstance()->codeExists($data[0])){
				$good = \modules\catalog\CatalogFactory::getInstance()->getGoodByCode($data[0]);
				fwrite($filestreem, str_replace('http://go-informator.ru', '', trim($data[1])).';'.$good->getPath()."\r\n");
			} else {
				fwrite($filestreem, str_replace('http://go-informator.ru', '', trim($data[1])).';'."\r\n");
			}
		}
		fclose($filestreem);
	}

	private function getSearchParamsToURL()
	{
		$searchString = '?';
		$params = $this->getGET();
		$sameParams = array();
		
		if (!empty($params['query'])) { $sameParams['query'] = $params['query']; }
		if (is_array($params['itemtype']) && !empty($params['itemtype'])) { 
			foreach ($params['itemtype'] as $key) {
				$sameParams['itemtype['.$params['itemtype'][$key].']'] = $key;
			}
		}
		if (isset($params['pricefrom'])) { $sameParams['pricefrom'] = $params['pricefrom']; }
		if (isset($params['pricemax'])) { $sameParams['pricemax'] = $params['pricemax']; }
		if (isset($params['sizefrom'])) { $sameParams['sizefrom'] = $params['sizefrom']; }
		if (isset($params['sizemax'])) { $sameParams['sizemax'] = $params['sizemax']; }
		if (is_array($params['condition']) && !empty($params['condition'])) { 
			foreach ($params['condition'] as $key) {
				$sameParams['condition['.$params['condition'][$key].']'] = $key;
			}
		}
		if (isset($params['page'])) { $sameParams['page'] = $params['page']; }
		return http_build_query($sameParams);
	}

	protected function search()
	{
		$this->setLevel('Поиск');
		
		$getParams = $this->getGET();
		$objects = $this->getSearchObjects($getParams);
		$objCount = $objects->count();
		
		$currentSortField = '';
		if (isset($getParams['sortBy'])) {
			$sortKeys = array_keys($getParams['sortBy']);
			$sortKey = array_pop($sortKeys);
			$currentSortField = $sortKey;
		}
		
		if ($objCount > 0) {
			$sortString = $this->sortingValues[ isset($getParams['sortBy']) ? $sortKey : 'novelty']
											  [ isset($getParams['sortBy']) ? $getParams['sortBy'][$sortKey] : 'up' ];

			$objects->setOrderBy($sortString)
					->setQuantityItemsOnSubpageList(array(((isset($getParams['results_per_pg'])) && (!empty($getParams['results_per_pg']))) ? $getParams['results_per_pg'] : self::QUANTITY_ITEMS_ON_SUBPAGE))
					->setPager( self::QUANTITY_ITEMS_ON_SUBPAGE );			
		}
		
		$sorting = array(
			'current' => $currentSortField,
			'direction' => ($this->getGET()['sortBy'][$currentSortField]=='up')?'down':'up',
			'fields' => array(
				'По цене'=>'price',
				'По новизне'=>'novelty'
			)
		);

		$this->setContent('objects', $objects)
			 ->setContent('objectsCount', $objCount)
			 ->setContent('parameters', $this->getParametersBlock())
			 ->setContent('pager', $objects->getPager())
			 ->setContent('sorting', $sorting)
			 ->setContent('urlParams',$this->getSearchParamsToURL())
			 ->includeTemplate('catalog/search');
	}
	
	protected function getSearchObjects($getParams)
	{
		$params = $getParams;
		
		$objects = $this->getActiveObjects();
		
		if(!empty($params['pricefrom']))
			$objects->setSubquery('AND `id` IN (SELECT `objectId` FROM `tbl_catalog_items_prices` WHERE `price` >=  ?d)', $params['pricefrom']);
	
		if(!empty($params['pricemax']))
			$objects->setSubquery('AND `id` IN (SELECT `objectId` FROM `tbl_catalog_items_prices` WHERE `price` <=  ?d)', $params['pricemax']);
		
		if (is_array($params['itemtype'])  &&  count($params['itemtype'])  &&  !empty($params['itemtype']))
			$objects->setSubquery('AND `categoryId` IN (?s)', implode(',', $params['itemtype']));
			
		if(!empty($params['sizefrom']))
			$this->selectObjectsBySize($objects,$params['sizefrom'],$params['sizemax']);
	
		//if(!empty($params['condition']))
			//$objects->setSubquery('AND `condition` = ?d', $params['condition']);
		
		if (is_array($params['condition'])  &&  count($params['condition'])  &&  !empty($params['condition']))
			$objects->setSubquery('AND `condition` IN (?s)', implode(',', $params['condition']));
		
		if (!empty($params['query']))
			$objects->setSubquery(
				'AND `id` IN (SELECT `id` FROM `tbl_catalog` WHERE ('
					. '`code` LIKE \'%?s%\' OR '
					. '`name` LIKE \'%?s%\'))',
				$params['query'], $params['query']
			);

		return $objects;	
	}
	
	private function selectObjectsBySize($objects, $minSize, $maxSize)
	{
		if (empty($maxSize)) { $maxSize = 60; }
		$filterKeys = array_filter(array_keys($this->sizesRelations), function ($range) use ($minSize, $maxSize){ return (($range >= $minSize) && ($range <= $maxSize)); }); 
		$filtred = array_intersect_key($this->sizesRelations, array_flip($filterKeys));

		$parameters = array();
		foreach ( $filtred as $key ) {
			$parameters [] =  $key;
		}		
		$i = 0;

		if ( $parameters ) {
			$joins = '';
			foreach($parameters as $parameter){
				$joins .= ' INNER JOIN `tbl_catalog_items_parameters_values_relation` pV'.$parameter.' ON rT.`id` = pV'.$parameter.'.`ownerId`';
			}
			$subquery = ' 1=1 ';
			foreach($parameters as $parameter) {
				if ($i == 0) {
					$subquery .= ' AND pV'.$parameter.'.`objectId`='.$parameter;
				} else {
					$subquery .= ' AND pV'.$parameter.'.`objectId`='.$parameter; //AND HERE; WAS OR
				}
				$i++;
			}
			$query = ' AND `id` IN (
				SELECT rT.`id` 
				FROM `tbl_catalog_items` rT 
				'.$joins.' WHERE '.$subquery.'
			)';
			$objects->setSubquery($query);
		}
	}
	
	private function getParametersBlock()
	{
		$parameters = $this->getObject('\modules\parameters\lib\Parameters')->getParametersByCategoryAlias('catalogadmincontroller')->setOrderBy('`priority` ASC');
		return $parameters;
	}
	
	protected function getPropertiesListByAlias($alias)
	{
		$properties = new \modules\properties\lib\Properties;
		$propertiesValues = new \modules\properties\components\propertiesValues\lib\PropertyValues;
		$propertiesValues->setSubquery(' AND `propertyId` IN ( SELECT `id` FROM `'.$properties->mainTable().'` WHERE `alias` = \'?s\' ) ', $alias);
		return $propertiesValues;
	}
	
	public function getObjectSizeParameter($object)
	{
		$size = '';
		if (!$this->isNoop($object)) {
			$parameters = $this->getParametersBlock();
			foreach( $parameters as $parameter ) {
				$parameterValues = $parameter->getParameterValuesByObjectParametersArray($object->getParametersArray());
				if(isset($parameterValues[0])) {
					foreach( $parameterValues as $parameterValue ) {
						if($parameter->name == 'Размер') {
							$size = $size . $parameterValue['name'] . ($parameterValue === end($parameterValues) ? '' : ' - ');
						}
					}
				}
			}
		return $size;	
		}
	}
	
	private function getIdString()
	{
		$objectsArray = \modules\catalog\CatalogFactory::getInstance()->getGoodsByNameOrCode($this->getGET()['query'], $this->getCurrentDomainAlias());
		$objectsIdString = '';

		if(!empty($objectsArray))
			foreach ($objectsArray as $object)
				$objectsIdString = $objectsIdString.$object->id.',';

		$objectsIdString = substr($objectsIdString, 0, strlen($objectsIdString) - 1);
		return $objectsIdString ? $objectsIdString : -1;
	}

	protected function getObjectsByCategory($category)
	{
		$objects = $this->getActiveObjects()
						->setFiltersByCategoryAlias($category->alias);
		return $objects;
	}

	protected function getActiveObjects()
	{
		$objects = $this->getCatalogObject();
		$objects->resetFilters();
		$objects->setSubquery('AND `statusId` NOT IN (?s)', implode(',', $this->getExludedStatusesArray()));
		return $objects;
	}
	
	protected function getCatalogListContent($criteria)
	{
		$getParams = $this->getGET();
		
		if(get_class($criteria) == 'core\modules\categories\Category'){
			$category = $criteria;
			$objects = $this->getObjectsByCategory($category);
		}
		else{
			$objects = $criteria;
			$category = $objects->current()->getCategory();
		}

		$objCount = $objects->count();
		$currentSortField = '';
		if (isset($getParams['sortBy'])) {
			$sortKeys = array_keys($getParams['sortBy']);
			$sortKey = array_pop($sortKeys);
			$currentSortField = $sortKey;
		}
		
		if ($objCount > 0) {
			$sortString = $this->sortingValues[ isset($getParams['sortBy']) ? $sortKey : 'novelty']
											  [ isset($getParams['sortBy']) ? $getParams['sortBy'][$sortKey] : 'up' ];

			$objects->setOrderBy($sortString)
					->setQuantityItemsOnSubpageList(array(((isset($getParams['results_per_pg'])) && (!empty($getParams['results_per_pg']))) ? $getParams['results_per_pg'] : self::QUANTITY_ITEMS_ON_SUBPAGE))
					->setPager( self::QUANTITY_ITEMS_ON_SUBPAGE );
		
			$sorting = array(
				'current' => $currentSortField,
				'direction' => ($this->getGET()['sortBy'][$currentSortField]=='up')?'down':'up',
				'fields' => array(
					'По цене'=>'price',
					'По новизне'=>'novelty'
				)
			);
		}
		
		$this->setContent('objects', $objects)
			 ->setContent('objectsCount', $objCount)
			 ->setContent('pager', $objects->getPager())
			 ->setContent('sorting', $sorting)
			 ->setContent('urlParams',$this->getSearchParamsToURL())
			 ->includeTemplate('/catalog/catalogListContent');
	}

	protected function getCatalogListContentItemBlock($object)
	{
		$this->setContent('object', $object)
			 ->setContent('parameters',$this->getParametersBlock())
			 ->includeTemplate('/catalog/catalogListContentItem');
	}
	
	protected function getMainCategoriesTop()
	{
		$categories = array();
		foreach ($this->getCatalogObject()->getMainCategories(self::ACTIVE_CATEGORY_STATUS) as $category)
			$categories[] = $category;
		return $categories;
	}
	
	protected function printParams($params)
	{
		if (is_array($params))
			$this->setContent('params', $params)
				 ->includeTemplate('catalog/paramsBlock');
		else
			echo $params;
		return $this;
	}

	protected function printSmallParams($params)
	{
		if (is_array($params))
			$this->setContent('params', $params)
				 ->includeTemplate('catalog/smallParamsBlock');
		else
			echo $params;
		return $this;
	}

	protected function getAdditionalGoodsBlock($goodId)
	{
		$this->setContent('objects', $this->getAdditionalGoods($goodId))
			 ->includeTemplate('/catalog/catalogObjectAdditionalGoods');
	}

	private function getAdditionalGoods($goodId, $goodsNumber = null)
	{
		$product = \modules\catalog\CatalogFactory::getInstance()->getGoodById($goodId);
		$items = $this->getCatalogObject();
		return $items->setFiltersByCategoryAlias($product->getCategory()->alias)
					 ->setSubquery('And mt.id<>?d and statusId<>9', $goodId)
					 ->setOrderBy('RAND()')
					 ->setLimit($goodsNumber ? $goodsNumber : self::NUMBER_OF_ADDITIONAL_GOODS);
	}

	protected function getComplectsBlock($goodId)
	{
		$complects = $this->getComplects($goodId);
		if ($complects->count()) {
			$this->setContent('objects', $complects)
				->includeTemplate('/catalog/complects');
		}
	}

	private function getComplects($goodId)
	{
		$complects = new \modules\catalog\complects\lib\Complects();
		$config = $complects->getConfig();
		return $complects->getComplectsByGoodId($goodId, $config::ACTIVE_STATUS_ID);
	}

	protected function getItemPropertyByAlias($alias, $object, $limit = null)
	{
		$array = array();
		$i = 1;
		foreach( $this->getPropertiesListByAlias($alias) as $item )
			if($object->getPropertyValueById($item->id)->value){
				$temp = array();
				$temp['name'] = $object->getPropertyValueById($item->id)->value;
				$temp['value'] = $item->getValue();
				$temp['measure'] = $object->getPropertyValueById($item->id)->getMeasure()->shortName;
				$array[] = $temp;
				if(isset($limit)  &&  $limit==$i)
					return empty($array) ? false : $array;
				$i++;
			}
		return empty($array) ? false : $array;
	}

	protected function getCategoryById($id)
	{
		return new \core\modules\categories\Category($id, $this->_config);
	}

	protected function includeOfferToObjectContent($object)
	{
		echo $this->setContent('object', $object)
				  ->includeTemplate('catalog/offerToCatalogObject');
	}

	protected function getLeftOffersBlock()
	{
		$this->setContent('offers', $this->getValidOffers())
			->includeTemplate('catalog/leftOffersBlock');
	}

	private function getValidOffers()
	{
		$offers = new \modules\catalog\offers\lib\Offers();
		return $offers->getValidOffers(self::NUMBER_OF_OFFERS_IN_LEFT_COLUMN_AND_INDEX);
	}

	public function sort($sort)
	{
		if ($sort === 'newest') {
			$this->setOrderBy(' `date` DESC ');
		} else if ($sort === 'oldest') {
			$this->setOrderBy(' `date` ASC ');
		} else {
			$this->sortByPrice($sort);
		}
		
		return $this;
	}	

}
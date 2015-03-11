<?php
namespace modules\catalog\traits;
trait CatalogWordsSearch {
	
	private $queryStringTest = '';
	private $queryDataTest   = array();
	private $searchText = '';
	
	public function searchByEveryWord($text, $domainAlias)
	{
		$idString = $this->setSearchText($text)
                                ->searchInDomainAliases($domainAlias)
                                ->searchInCatalog()
                                ->searchInModule()
                                ->getIdString();

                $query = (!empty($idString)) ? ' AND `id` IN ('.$idString.')' : ' AND `id` IN (0)';
                $this->setSubquery($query);
		
		return $this;
	}
	
	private function setSearchText ($text)
	{
		
		$this->searchText = explode(' ', $text);
		
		return $this;
	}
	
	public function searchInDomainAliases($domainAlias) 
	{
		$this->queryStringTest = '(
		
			SELECT `objectId` as `id` 
			FROM `tbl_catalog_domainsinfo` 
			WHERE (
					(	'.$this->searchByEveryWordInSomeTables('name').') 
					OR 
					(	'.$this->searchByEveryWordInSomeTables('code').') 
					OR 
					(	'.$this->searchByEveryWordInSomeTables('description').') 
				  ) 
			AND `domainAlias`="?s"  
		)';
		$this->queryDataTest[] = $domainAlias;
		
		return $this;
	}
	
	public function searchByEveryWordInSomeTables($field='name')
	{
		$query = ' 1=1 ';
		foreach ($this->searchText as $word){
			$query .= ' AND `'.$field.'` LIKE "%?s%"';
			$this->queryDataTest[] = $word;
		}
		return $query;
	}
	
	public function searchInCatalog() 
	{
		$this->queryStringTest .= ' 
		UNION ( 
			SELECT `id` 
			FROM `tbl_catalog` 
			WHERE 
				('.$this->searchByEveryWordInSomeTables('name').') 
			OR 
				('.$this->searchByEveryWordInSomeTables('code').') 
		)';
		
		return $this;
	}
	
	public function searchInModule() 
	{
		$this->queryStringTest .= ' 
		UNION ( 
			SELECT `id` 
			FROM `'.$this->mainTable().'` 
			WHERE 
				('.$this->searchByEveryWordInSomeTables('description').') 
			OR 
				('.$this->searchByEveryWordInSomeTables('text').') 
		)';
		
		return $this;
	}
	
	public function getIdString() 
	{

		$idListFromDB = \core\db\Db::getMysql()->rowsAssoc($this->queryStringTest, $this->queryDataTest);
                $idList = array();
                foreach ($idListFromDB as $key=>$value) $idList[] = $value['id'];
		return implode(',', $idList);
	}
	
}
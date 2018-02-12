<?php
namespace modules\seo\linksFilter\lib;
class LinksFilter 
{
	private $file;
	private $queries;
	private $stopWords;
	private $domain;
	
	private $db;
	private $tableName = 'tmp_link_table';
	private $sortingTableName = 'tmp_link_table_sorting';

	public function __construct(\core\files\uploader\File $file, $queries = null, $convertToUtf8 = true)
	{
		$this->file = $file;
		$this->setQueries($queries);
		$this->insertDataInHeapTable($this->parseFile($convertToUtf8));
	}
	
	protected function setQueries($queries)
	{
		$this->queries = $this->getWordsByString($queries);
		return $this;
	}
	
	protected function getWordsByString($string)
	{
		$string = trim($string);
		return empty($string) ? array() : explode("\r\n", $string);
	}
	
	protected function parseFile($convertToUtf8)
	{
		$fileContent = $this->file->getFileContent();
		if ($convertToUtf8)
			$fileContent = @iconv('cp1251', 'utf-8', $fileContent);
		$rows = explode("\r\n", $fileContent);
		foreach ($rows as $key => $value){
			$rows[$key] =  explode(';', $value);
		}
		return $rows;
	}
	
	protected function insertDataInHeapTable($data)
	{
		$this->createHeapTable($this->tableName);
		foreach($data as $linkRow){
			if (!empty($linkRow[0])){
				$query = '
					INSERT INTO `'.$this->tableName.'` 
						(`page`, `donorPage`, `ankor`, `domain`, `words`)
					VALUE
						("?s", "?s", "?s", "?s", ?d)';
				$ankor = isset($linkRow[2]) ? trim($linkRow[2]) : '';
				$data = array($linkRow[0], $linkRow[1], $ankor, \core\utils\Url::getSecondDomainLevelByUrl($linkRow[1]), $this->countWords($ankor));
				$this->getDb()->query($query,$data);
			}
		}
		return $this;
	}
	
	protected function createHeapTable($table)
	{
		$query = '
		CREATE TABLE IF NOT EXISTS `'.$table.'` (
			`page` VARCHAR(700), 
			`domain` VARCHAR(700), 
			`ankor` VARCHAR(700), 
			`words` CHAR(10), 
			`donorPage` VARCHAR(700)
		) ENGINE = MEMORY';
		$result = $this->getDb()->query($query);
		if ($result){
			$this->truncateHeapTableIfExists($table);
			return $this;		
		}
		throw new \Exception('Can\'t create temporary MySQL Table in Memory in class '.get_class($this).'!');
	}
	
	protected function getDb()
	{
		if (empty($this->db))
			$this->db = \core\db\Db::getMysql();
		return $this->db;
	}
	
	protected function truncateHeapTableIfExists($table)
	{
		$query = 'TRUNCATE TABLE `'.$table.'`';
		$this->getDb()->query($query);
		return $this;
	}
	
	protected function countWords($string)
	{
		return empty($string) ? 0 : count(explode(' ', $string));
	}
	
	public function setStopWords($mixed)
	{
		$this->stopWords = is_array($mixed) ? $mixed : $this->getWordsByString($mixed);
		return $this;
	}
	
	public function setDomain($domain)
	{
		$this->domain = \core\utils\Url::getDomainByUrl($domain);
		return $this;
	}
	
	public function getDistinctList()
	{
		$query = '
			SELECT DISTINCT
				`page`, `domain`, `ankor`, `words`, `donorPage`
			FROM 
				`'.$this->tableName.'`
			GROUP BY
				`page`, `domain`, `ankor`';
		return $this->getDb()->rowsAssoc($query);
	}
	
	public function getPageSortingByWords()
	{	
		$this->createHeapTable('tmp_link_table_sort');
		$query = '
			INSERT INTO
				`tmp_link_table_sort`
			SELECT DISTINCT
				*
			FROM 
				`'.$this->tableName.'`
			GROUP BY
				`page`, `domain`, `ankor`';
		$this->getDb()->query($query);
		
		$query = '
			SELECT 
				COUNT(*) as `totalLinks`, `page`, `words`
			FROM 
				`tmp_link_table_sort`
			GROUP BY 
				`page`, `words`';
		
		return $this->getDb()->rowsAssoc($query);
	}
	
	public function getQueries()
	{
		return $this->queries;
	}
	
	public function getPageSortingByQuery($query)
	{	
		$this->createHeapTable('tmp_link_table_sort_query');
		foreach ($this->getDistinctList() as $row){
			$resultAnkorAnalize = $this->analizeAnkor($this->removeStopWords($row['ankor']), $query);
			$resultAnkorAnalize = ($resultAnkorAnalize > 0) 
				? ($resultAnkorAnalize-1) 
				: $resultAnkorAnalize == 0;
			
			if ($this->isNaturalLink($row['ankor']))
				$resultAnkorAnalize = 'NL';
			
			if ($this->isEmptyAnkorLink($row['ankor']))
				$resultAnkorAnalize = 'EAL';
			
			$dbQuery = '
				INSERT INTO `tmp_link_table_sort_query` 
					(`page`, `donorPage`, `ankor`, `domain`, `words`)
				VALUE
					("?s", "?s", "?s", "?s", "?s")';
			$data = array($row['page'], $row['donorPage'], strtolower($row['ankor']), $row['domain'], $resultAnkorAnalize);
			$this->getDb()->query($dbQuery,$data);
		}

		$query = '
			SELECT 
				COUNT(*) as `totalLinks`, `page`, `words`
			FROM 
				`tmp_link_table_sort_query`
			GROUP BY 
				`page`, `words`';
		return $this->getDb()->rowsAssoc($query);
	}
	
	protected function removeStopWords($ankor)
	{
		foreach ($this->stopWords as $word){
			$ankor = str_replace(' '.trim($word).' ', '', $ankor);
		}
		return $ankor;
	}
	
	protected function isNaturalLink($ankor)
	{
		if ($this->isMultiWords($ankor))
			return false;
		return mb_stripos($ankor, $this->domain) !== false;
	}

	protected function isMultiWords($string)
	{
		return count(explode(' ', $string)) > 1;
	}
	
	protected function isEmptyAnkorLink($ankor)
	{
		return empty($ankor) && $ankor !== 0;
	}
	
	protected function analizeAnkor($ankor, $query)
	{	
		$query = trim($query);
		return ($this->isMultiWords($query)) 
			? $this->MultiWordsAnkorAnalizer($ankor, $query)
			: $this->SimpleWordAnkorAnalizer($ankor, $query);
	}
	
	protected function MultiWordsAnkorAnalizer($ankor, $query)
	{
		$ankor = mb_strtolower($ankor,'UTF-8');
		$query = mb_strtolower($query,'UTF-8');
		
		$words = explode(' ', trim($query));
		$nvLevel = 0;
		foreach ($words as $word){
			if (mb_stripos($ankor, $word) !== false) {
				$ankor = trim(str_replace($word, '', $ankor));
			} else 
				$nvLevel++;
		}
		if ($nvLevel){
			if ($nvLevel == $this->countWords($query))
				return 0;
			else {
				$querySpam = ($this->countWords($query) - $nvLevel) / $this->countWords($query) * 100 ;
				$ankorSpam = $this->countWords($ankor) + 1;
				return 0-round($querySpam / $ankorSpam);
			}
		}
		$ankor = $this->removeMultipleSpaces($ankor);

		return $this->countWords($ankor)+1;	
	}
	
	protected function removeMultipleSpaces($string)
	{
		$string = str_replace('  ', ' ', $string, $count);
		if ($count)
			$string = $this->removeMultipleSpaces($string);
		return $string;
	}
	
	protected function SimpleWordAnkorAnalizer($ankor, $query)
	{
		$ankorWords = explode(' ', $ankor);
		return array_search($query, $ankorWords) !== false
			? (count($ankorWords) == 1 ? 1 : count($ankorWords)-1)
			: 0;
	}
	
	public function getTotalSpamByQuery($querySortingArray)
	{
		$spam = $countLinks = 0;
		foreach($querySortingArray as $row){
			$countLinks+=$row['totalLinks'];
			
			if ($row['words'] < 0 && $row['words'] != 'NL' && $row['words'] != 'EAL') 
				$spam += abs($row['totalLinks']*$row['words']);
			
			if ($row['words'] > 0 && $row['words'] != 'NL' && $row['words'] != 'EAL')
				$spam += ($row['totalLinks']*100/($row['words']+1));;
		}
		return $countLinks ? round($spam/$countLinks) : 0;
	}
	
	public function getNameBySpamValue($spam)
	{
		if ($spam == 'EAL')
			return 'Безанкорная ссылка';
		if ($spam == 'NL')
			return 'Естественная ссылка';
		if ($spam === '0')
			return 'Без фрагмента ключевика';
		$type = ($spam < 0) ? 'НВ' : 'ТВ';
		$value = ($spam < 0) 
			? $spam.'%' 
			: (($spam) ? '+'.$spam : '');
		return $type.$value;								
	}
	
	public function __destruct()
	{
		//$this->dropHeapTable($this->tableName);
		//$this->dropHeapTable($this->sortingTableName);
		//$this->dropHeapTable('tmp_link_table_sort_query');
	}
	
	public function dropHeapTable($table)
	{
		$query = 'DROP TABLE `'.$table.'`';
		$this->getDb()->query($query);
	}
	
}
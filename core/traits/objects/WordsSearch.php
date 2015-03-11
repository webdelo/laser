<?php
namespace core\traits\objects;
trait WordsSearch {
	
	private $queryStringWordsSearch = '';
	private $queryDataWordsSearch   = array();
	private $searchText = '';
	protected $fieldsForSearchByEveryWord = array('name');


	public function searchByEveryWord($text)
	{
		$this->setSearchText($text)
			 ->setSearchQueryByEveryWord();
		return $this;
	}
	
	private function setSearchText ($text)
	{
		$this->searchText = explode(' ', $text);	
		return $this;
	}
        
	private function setSearchQueryByEveryWord()
	{
                $this->queryStringWordsSearch .= ' AND ';
                foreach($this->fieldsForSearchByEveryWord as $field) {
                    $counter = 0;
                    $this->queryStringWordsSearch .= ' ( ';
                    foreach ($this->searchText as $word){
                            $counter++;
                            $this->queryStringWordsSearch .= $this->plusAnd($counter).' `'.$field.'` LIKE "%?s%"';
                            $this->queryDataWordsSearch[] = $word;
                    }
                    $this->queryStringWordsSearch .= ' ) OR';
                }
                $this->clearSearchText();
                $this->setSubqueryArray($this->queryStringWordsSearch, $this->queryDataWordsSearch);
                
		return $this;
	}
        
        private function plusAnd($key)
        {
            return ($key%2==0 && sizeof($this->searchText)>=$key) ? ' AND' : '';
        }
        
        private function clearSearchText ()
        {
            if (sizeof($this->fieldsForSearchByEveryWord)%2!=0) $this->queryStringWordsSearch = substr($this->queryStringWordsSearch, 0, -2);
        }
}
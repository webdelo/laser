<?php
namespace modules\clients\components\clientLangs\lib;
class ClientLangs extends \core\modules\base\ModuleObjects
{
	use \core\traits\ObjectPool;

	private $clientId;

	protected $configClass     = 'modules\clients\components\clientLangs\lib\ClientLangConfig';
	protected $objectClassName = 'modules\clients\components\clientLangs\lib\ClientLang';

	function __construct($clientId)
	{
		parent::__construct(new $this->configClass);
		$this->setClientId($clientId);
	}

	private function setClientId($clientId)
	{
		$this->clientId = $clientId;
	}

	public function add($langId, $fields = null)
	{
		$clientLangExist = $this->getClientLangId($langId);
		if($clientLangExist)
			return $clientLangExist;

		$data = array(
			'clientId' => $this->clientId,
			'languageId' => $langId
		);
		return parent::add($data);
	}

	private function getClientLangId($langId)
	{
		return $this->getFieldWhereCriteria('id', $langId, 'languageId', $this->clientId, 'clientId');
	}

	public function remove($langId)
	{
		$clientLangId = $this->getClientLangId($langId);
		if($clientLangId)
			return $this->getObjectById($clientLangId)->remove();
		return false;
	}

	public function setMainLang($langId)
	{
		$clientLangId = $this->getClientLangId($langId);
		if(!$clientLangId)
			return false;

		foreach ($this->getTableClientLangs() as $clientLang)
			$clientLang->edit(array('isMain' => 0));

		return $this->getObjectById($clientLangId)->edit(array('isMain' => 1));
	}

	private function getTableClientLangs()
	{
		return  $this->setSubquery(' AND `clientId` = ?d ', (int)$this->clientId);
	}

	public function getClientLangs()
	{
		$clientsLanguages = $this->getTableClientLangs();
		if(!$clientsLanguages->count())
			return $this->getNoop();

		$ids = '';
		foreach($clientsLanguages as $clientLang)
			$ids .= $clientLang->languageId.',';

		return clone $this->getObject('\modules\languages\lib\Languages')->getLangByIdsString( substr($ids, 0, strlen($ids)-1) );
	}

	public function getMainLang()
	{
		$langId = $this->getFieldWhereCriteria('languageId', $this->clientId, 'clientId', 1, 'isMain');
		if ( $langId ) {
			return $this->getObject('\modules\languages\lib\Language', $langId);
		} else {
			$langs = $this->getClientLangs();
			return $langs->current();
		}
	}

	public function removeClientLangByLanguageId($langId)
	{
		return $this->getObjectById($this->getClientLangId($langId))->remove();
	}
}
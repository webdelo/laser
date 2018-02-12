<?php
namespace core\mail;
class Error404Mail extends \core\mail\SystemMailer
{
	use	\core\traits\RequestHandler;

	protected $bodyFileName = 'Error404.tpl';

	function __construct()
	{
		parent::__construct();
		$this->CollectErrorData();
	}

	protected function CollectErrorData()
	{
		date_default_timezone_set('Europe/Chisinau');
		$data	= array();
		$data[] = array("Time",date('d.m.Y H:i:s', time()));
		$data[] = array("IP",$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']));
		$data[] = array('Request',$_SERVER['REQUEST_URI']);
		$data[] = (empty($_SERVER["HTTP_REFERER"])) ? array('Referrer','Empty') : array('Referrer',$_SERVER["HTTP_REFERER"]);
		$data[] = array('User-Agent',$_SERVER['HTTP_USER_AGENT']);

		$this->Content('data', $data);
		return $this;
	}

	public function send()
	{
		$this->setTitle('Обнаружена ошибка 404')
			 ->Subject('Обнаружена ошибка 404 на сайте ' . $this->getCurrentDomainAlias());
		if(parent::Send())
			return true;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

}
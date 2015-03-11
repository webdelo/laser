<?php
namespace modules\mailers;
class NewOrderManagersMail extends \core\mail\MailBase
{
	use	\core\traits\RequestHandler,
		\core\traits\controllers\Authorization,
		\core\traits\ObjectPool;

	function __construct($data)
	{
		parent::__construct();
		$this->templates = DIR.'modules/orders/tpl/mailTemplates/';
		$this->data = $data;
	}

	public function sendNewOrderManagersMail()
	{
		$managers = array();
		foreach($this->getObject('modules\administrators\lib\Administrators')->getActiveManagers() as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email'])    &&    $manager != $this->getAuthorizatedUser())
				$managers[] = $manager->getUserData()['email'];

		$res = $this->From($this->noreplyEmail)
				->To('d.godiac@webdelo.org')
				->Subject('Новый заказ на сайте  '.SEND_FROM)
				->Content('order', $this->data)
				->Content('time', $this->getPost()['time'])
				->Content('managers', $managers)
				->BodyFromFile('alertNewOrderManagers.tpl')
				->Send();
		if($res)
			return 1;
		throw new Exception('Error mail() in '.get_class($this).'!');
	}

}
<?php
namespace modules\mailers;
class OrderByOneClickMail extends \core\mail\MailBase
{
	use	\core\traits\RequestHandler,
		\core\traits\controllers\Authorization,
		\core\traits\ObjectPool;

	function __construct()
	{
		parent::__construct();
		$this->templates = DIR.'modules/orders/tpl/mailTemplates/';
	}

	public function sendPhoneNumberToManagers($goodId, $clientPhoneNumber)
	{
//		$managers = array('testdeloadm@gmail.com');
		foreach($this->getObject('modules\administrators\lib\Administrators')->getActiveManagers() as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email'])    &&    $manager != $this->getAuthorizatedUser())
				$managers[] = $manager->getUserData()['email'];

		$good = \modules\catalog\CatalogFactory::getInstance()->getGoodById($goodId);

		$res = $this->From($this->noreplyEmail)
				->To($managers)
				->Subject('Клиент просит позвонить по номеру '.$clientPhoneNumber.', для оформления заказа на '.SEND_FROM)
				->Content('good', $good)
				->Content('clientPhoneNumber', $clientPhoneNumber)
				->Content('managers', $managers)
				->BodyFromFile('mailOrderByOneClickContent.tpl')
				->Send();
		if($res)
			return 1;
		throw new Exception('Error mail() in '.get_class($this).'!');
	}

}

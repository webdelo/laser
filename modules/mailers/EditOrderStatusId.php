<?php
namespace modules\mailers;
class EditOrderStatusId extends \core\mail\MailBase
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

	public function sendMailToClient($statusData)
	{
		$res = $this->From($this->noreplyEmail)
				->To($this->data->getClient()->getLogin())
				->Subject('Был изменен статус Вашего заказа №'.$this->data->nr.' на сайте '.DIR_HTTP)
				->Content('order', $this->data)
				->Content('statusData', $statusData)
				->BodyFromFile('editOrderStatusIdClient.tpl')
				->Send();
		if($res)
			return 1;
		return ('Error mail() in '.get_class($this).'!');
	}

	public function sendMailToPartner($statusData)
	{
		$managers = array();
		foreach($this->data->getPartner()->getManagers() as $manager)
			if( \core\utils\Utils::isEmail($manager->getLogin()) )
				$managers[] = $manager->getLogin();

		$adminManagers = array();
		foreach($this->getObject('\modules\administrators\lib\Administrators')->getActiveManagers() as $manager)
			if( \core\utils\Utils::isEmail($manager->email) )
				$adminManagers[] = $manager->email;

		$managers = array_merge($managers, $adminManagers);

		$res = $this->From($this->noreplyEmail)
				->To($managers)
				->Subject('Заказ №'.$this->data->nr. ', статус изменен с '.strtoupper($statusData->oldStatusName).' на '.strtoupper($statusData->newStatusName))
				->Content('order', $this->data)
				->Content('statusData', $statusData)
				->BodyFromFile('editOrderStatusIdAdmin.tpl')
				->Send();
		if($res)
			return 1;
		return ('Error mail() in '.get_class($this).'!');
	}

}
<?php
namespace modules\mailers;
class EditDescriptionNotice extends \core\mail\MailBase
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

	public function sendMail($newDescription)
	{
		$managers = array();
		foreach($this->getObject('modules\administrators\lib\Administrators')->getActiveManagers() as $manager)
			if( \core\utils\Utils::isEmail($manager->getUserData()['email'])    &&    $manager->id != $this->getAuthorizatedUser()->id)
				$managers[] = $manager->getUserData()['email'];

		$res = $this->From($this->noreplyEmail)
				->To($managers)
				->Subject($this->getAuthorizatedUser()->getAllName().' обновил заметки в заказе №'.$this->data->nr)
				->Content('order', $this->data)
				->Content('newDescription', $newDescription)
				->BodyFromFile('editDescriptionNotice.tpl')
				->Send();
		if($res)
			return 1;
		throw new Exception('Error mail() in '.get_class($this).'!');
	}

}
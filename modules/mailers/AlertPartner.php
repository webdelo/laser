<?php
namespace modules\mailers;
class AlertPartner extends \core\mail\MailBase
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

	public function sendAlertPartner()
	{
		$this->setBcc();
		$this->setAdditionalMessage();
		$this->attachFiles();
		$res = $this->From($this->adminEmail)
				->To($this->getManagers())
				->Subject($this->getSubject())
				->Content('order', $this->data)
				->Content('time', $this->getPost()['time'])
				->BodyFromFile('alertPartnerMail.tpl')
				->Send();
		if($res)
			return 1;
		throw new \Exception('Error mail() in '.get_class($this).'!');
	}

	public function sendMessagePartner()
	{
		$this->setBcc();
		$this->setAdditionalMessage();
		$this->attachFiles();

		$res = $this->From($this->adminEmail)
				->To($this->getManagers())
				->Subject('Новое сообщение по заказу №'.$this->data->nr . ' ('.$this->getClientName().')')
				->Content('order', $this->data)
				->Content('time', $this->getPost()['time'])
				->BodyFromFile('messagePartnerBase.tpl')
				->Send();
		if($res)
			return 1;
		throw new Exception('Error mail() in '.get_class($this).'!');
	}

	private function getManagers()
	{
		return $this->getPost()['managers'];
	}

	private function setBcc()
	{
		if($this->getPost()['copyToAdmin'] == 'true')
			$this->Bcc($this->adminEmail);
	}

	private function setAdditionalMessage()
	{
		if($this->getPost()['aditionalMessage'])
			$this->Content('aditionalMessage',	str_replace("\n", "<br />", $this->getPost()['aditionalMessage']));
	}

	private function attachFiles()
	{
		$files = $this->getFiles();
		if(is_array($files))
			foreach($files as $file)
				if(file_exists($file['filePath']))
					$this->attach(array(
									'path'=>$file['filePath'],
									'name'=>$file['fileName']
									)
							);
	}

	private function getFiles()
	{
		$files = array();
		$object = $this->data;
		if($object->getFilesCategories()) foreach($object->getFilesCategories() as $item)
			if( $object->getFilesByCategory($item->id)->current() )
				foreach ( $object->getFilesByCategory($item->id) as $file ){
					$tmpArray = array(
								'filePath' => DIR.'files/'.$_REQUEST['controller'].'/files/'.$file->id.'.'.$file->extension,
								'fileName' => $file->name
								);
					$files[] = $tmpArray;
				}
		return $files;
	}

	private function getSubject()
	{
		return 'Новый заказ для '.$this->data->getPartner()->name.' от '.$this->getClientName();
	}

	private function getClientName()
	{
		return trim($this->data->getClient()->company ? $this->data->getClient()->company : $this->data->getClient()->getAllName()) ;
	}

	public function getAlertPartnerContent($bodyFromFile)
	{
		$this->setBcc();
		$this->setAdditionalMessage();
		$this->attachFiles();

		return $this->From($this->adminEmail, $this->getAuthorizatedUser()->getAllName())
				->To($this->getManagers())
				->Subject($this->getSubject())
				->Content('subject', $this->getSubject())
				->Content('order', $this->data)
				->Content('time', $this->getPost()['time'])
				->BodyFromFile($bodyFromFile.'.tpl')
				->getBodyContent();
	}

}

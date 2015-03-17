<?php
namespace core\mail;
abstract class SystemMailer extends \core\mail\MailBase
{
	const LOGO_DIR = '/admin/images/logo/studio.png';

	protected $templatesDir = 'core/mail/tpl/systemMailer/';
	protected $baseTemplatesDir = 'core/mail/tpl/systemMailer/';
	protected $bodyFileName;
	protected $title = '';

	public function __construct()
	{
		parent::__construct();
		$this->templates = DIR.$this->templatesDir;
		$this->setTo($this->getDevelopersMails());
		$this->From($this->noreplyEmail);
	}

	protected function getHeader()
	{
		return $this->getTemplate($this->baseTemplatesDir . 'header.tpl');
	}

	protected function getFooter()
	{
		return  $this->getTemplate($this->baseTemplatesDir . 'footer.tpl');
	}

	protected function getTemplate($templatePath)
	{
		ob_start();
		include(DIR . $templatePath);
		$body = ob_get_contents();
		ob_end_clean();
		return $body;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	protected function getTitle()
	{
		return $this->title;
	}

	protected function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	public function getLogo()
	{
		return 'http://' . $this->getCurrentDomainAlias() . self::LOGO_DIR;
	}

	private function getDevelopersMails()
	{
		$array = \core\Configurator::getInstance()->getArraybyKey('debug');
		return $array['mailTo'];
	}

	public function _beforeSend()
	{
		$this->BodyFromFile($this->bodyFileName);
		parent::_beforeSend();
	}

}
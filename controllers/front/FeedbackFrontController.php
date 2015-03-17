<?php
namespace controllers\front;
class FeedbackFrontController extends \controllers\base\Controller
{
	use \core\traits\controllers\Templates;

	protected $permissibleActions = array(
		'ajaxSendMessage',
		'ajaxSendContactMessage'
	);

	public function __construct()
	{
		parent::__construct();
	}

	protected function ajaxSendMessage()
	{
	    $feedback = new \modules\mailers\ContactsFeedback($this->getPOST());
	    $this->setObject($feedback)
		    ->ajax($this->modelObject->sendFeedbackMail(), 'ajax');
	}

	protected function redirect404()
	{
	    echo '404';
	}

	public function getFeedbackContactBlock()
	{
		$captchaSting = new \core\captcha\CaptchaString();
		$this->setContent('captchaString', $captchaSting)
			->includeTemplate('feedback/feedbackContactBlock');
	}

	protected function ajaxSendContactMessage()
	{
		$feedback = new \modules\mailers\SendContactMessage($this->getPOST());
		$res = $feedback->sendMessage();
		$return = $res   ?   array('result'=>'ok')   :   $feedback->getErrors();
		return $this->ajaxResponse( $return );
	}
	
}

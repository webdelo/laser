<?php
namespace controllers\front\cabinet\traits;
trait profileTrait
{
	protected function getUserProfileBlock(\modules\clients\lib\Client $user)
	{
		$this->setContent('user', $user)
			 ->includeTemplate('cabinet/profile/cabinet/bookingRightBlock');
	}
}
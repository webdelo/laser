<?php
namespace controllers\admin;
class SettingsAdminController extends \controllers\base\Controller
{
	use	\core\traits\controllers\Rights,
		\core\traits\controllers\Authorization;

	public function __call($name, $arguments)
	{
		$this->settings();
	}

	public function settings()
	{
		$this->checkUserRightAndBlock('settings');
		$settings = new \core\Settings();
		$filter = array('order_by' => 'sort_order');
		$infos = $settings->getSettingsEdit($filter);
		include(DIR.'/modules/settings/tpl/settings.tpl');
	}

	public function saveSettings()
	{
		$this->checkUserRightAndBlock('settings_edit');
		$this->setObject('\core\Settings');
		$res = $this->modelObject->edit($this->getPOST()['settings']);

		if($res == false){
			$errors = $this->modelObject->getErrors();
			$this->resetErrors();
			foreach($errors as $key=>$value)
				$newErrors['settings['.$this->getFieldId($key).']['.$key.']'] = $value;
			return $this->ajaxResponse($newErrors);
		}
		$this->ajaxResponse(true);
	}

	private function getFieldId($name)
	{
		$this->setObject('\core\Settings');
		return $this->modelObject->getField('id', $name, 'name');
	}

	public function ajaxClearCache()
	{
		$cache = new \core\images\resize\Cache();
		$this->ajaxResponse($cache->clearAll());
	}
}
?>

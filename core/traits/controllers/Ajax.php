<?php
// Requires: \core\traits\ObjectPool
namespace core\traits\controllers;
trait Ajax
{
	protected $modelObject;
	
	protected function setObject($object, $objectId = null, $objectConfig = null)
	{
		$this->modelObject = (is_object($object)) ? $object : $this->getObject($object, $objectId, $objectConfig);
		return $this;
	}
	
	public function ajax($response, $type = 'ajax', $json = true, $j_script_action = '')
	{
		if ($response === false)
			$response = $this->modelObject->getErrors();
		
		if ($type == 'ajax')
			$this->ajaxResponse($response, $json);
		elseif ($type == 'iframe')
			$this->iframeResponse($response, $json, $j_script_action);
		
		return $this;
	}

	protected function ajaxResponse($response, $json = true)
	{
		if ($response === true)
			echo 1;
		else {
			if ($json)
				$response = json_encode($response);
			else
				$response = implode('<br>', $response);
			echo $response;
		}
	}
	
	protected function iframeResponse($response, $json, $j_script_action)
	{
		if ($response === true)
			$response = 1;
		elseif ($json)
			$response = json_encode($response);
		else
			$response = implode('<br>', $response);
		ob_start();
		$this->setContent('response', $response)
			 ->setContent('json', $json)
			 ->setContent('j_script_action', $j_script_action)
			 ->includeTemplate('iframe_controller');
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;
	}
}
<?php
namespace core\debug;
class Errors extends \core\Model
{
	public function addError($errorHandler) {
		$data = array(
			'date'           => time(),
			'message'        => $errorHandler->message,
			'code'           => $errorHandler->code,
			'file'           => $errorHandler->file,
			'line'           => $errorHandler->line,
			'code'           => $errorHandler->code,
			'type'           => $errorHandler->type,
			'exceptionClass' => $errorHandler->exceptionClass,
			'phpVersion'     => $errorHandler->phpVersion,
			'content'        => $errorHandler->content,
			'resolution'     => $errorHandler->data['data']['resolution']['width'].'x'.$errorHandler->data['data']['resolution']['height']
		);
		$this->baseAdd($data);
		
	}
	
	public function showError ($id) {
		if ($this->isExist($id)) {
			$filters['query'] = ' mt.`id` = ?d ';
			$filters['data'] = array($id);
			$error = $this->getOne('content', array('where'=>$filters));
			echo $error['content'];
		} else {
			echo 'Error with id='.id.' was not found!';
		}
	}
}
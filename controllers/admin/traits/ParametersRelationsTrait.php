<?php
//	Для вывода своего темплейта нужно переопределить два метода
//	1. getTemplateWithClient
//	2. getTemplateToSearchClient
//	В них нужно указать путь к другим файлам шаблонов
namespace controllers\admin\traits;
trait ParametersRelationsTrait
{	

	protected function ajaxGetParameterBlocks()
	{
		echo $this->getParameterBlocks($this->getGET()->objectId);
	}

	protected function getParameterBlocks($objectId)
	{
		$realty = $this->getObject($this->objectClass, $objectId);
		$methods = $this->getObject('\modules\parameters\components\chooseMethods\lib\ChooseMethods');		
		$this->setContent('object', $realty)
			 ->setContent('methods', $methods)
			 ->setContent('parameters', $this->getRealtyParameters())
			 ->includeTemplate('parameterBlocks');
	}
	
	protected function parameterEdit()
	{
		$object = $this->getObject($this->objectClass, $this->getPOST()->id);
		return $this->ajaxResponse($object->getParameters()->edit($this->getPOST()->parametersValues));
	}
}

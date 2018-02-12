<?php
namespace controllers\front;
class AddFrontController extends \controllers\base\Controller
{
	use	\core\traits\Pager,
		\core\traits\controllers\Meta,
		\core\traits\controllers\Images,
		\core\traits\controllers\Templates,
		\core\traits\controllers\RequestLevels,
		\core\traits\controllers\Authorization,
		\controllers\admin\traits\PropertiesRelationsTrait,
		\core\traits\RequestHandler;

	const SESSION_STEP_KEY  = 'addRealtyCurrentStep';

	private $realtiesObject;
	private $realtyObject;
	private $realtyObjectId;
	private $currentStep = 1;

	private $realtiesExcludedCategories = array(1,2);
	private $addingStatus;
	private $addingCategory;

	protected $permissibleActions = array(
		'ajaxSaveFirstStep',
		'ajaxSaveSecondStep',
		'ajaxSaveThirdStep',
		'ajaxSaveSubstep',
		'ajaxGetCurrentStep',
		'ajaxGetStep1',
		'ajaxGetStep2',
		'ajaxGetStep3',

		'ajaxGetPricesTable',
		'ajaxGetRealtyObjectPath',
		'ajaxAddOnePrice',
		'ajaxDeletePrice',
		'ajaxEditPrice',
		'ajaxEditField',
		'ajaxEditEntryTime',
		'ajaxEditGuestsPay',
		'ajaxEditParameter',
		'ajaxEditPropertyRelation',
		'ajaxEditSleepsRelation',
		'ajaxSaveAddressDetails',
		'ajaxEditImportantField',
		'ajaxEditName',
		'ajaxEditDescription',
		'ajaxEditPersonalInfo',

		'ajaxAddStopPeriod',
		'ajaxRemoveStopPeriod',
		'getCurrentStopPeriods',
		'ajaxGetStopPeriodsTable',
		'ajaxRemoveAllStopPeriods',

		/* Start: Images Trait Methods*/
		'uploadImage',
		'addImagesFromEditPage',
		'removeImage',
		'setPrimary',
		'resetPrimary',
		'setBlock',
		'resetBlock',
		'editImage',
		'getTemplateToEditImage',
		'ajaxGetImagesBlock',
		'ajaxGetImagesListBlock',
		/*   End: Images Trait Methods*/
	);

	public function __construct()
	{
		parent::__construct();
		$this->_config = new \modules\realties\lib\RealtyConfig();
		$this->objectClass = $this->_config->getObjectClass();
		$this->objectsClass = $this->_config->getObjectsClass();
		$this->objectClassName = $this->_config->getObjectClassName();
		$this->objectsClassName = $this->_config->getObjectsClassName();
		$this->setDefaultRealtyData()
			 ->setRealtyObjectdId()
			 ->setRealtyObject()
			 ->setContent('object', $this->getRealtyObject());
	}

	protected function defaultAction()
	{
		if ( $this->getController('authorization')->isClient() ) {
			return $this->rent();
		} else {
			return $this->authorization();
		}
	}

	public function __call($name, $arguments)
	{

		if (empty($name))
			return $this->defaultAction();
		elseif ($this->setAction($name)->isPermissibleAction())
			return $this->callAction($arguments);
		else
			return $this->defaultAction();
	}

	private function setDefaultRealtyData()
	{
		$this->addingStatus   = $this->_config->addingStatus;
		$this->addingCategory = $this->_config->defaultCategory;
		return $this;
	}

	private function setCurrentStepFromSession()
	{
		if ($this->getSESSION()[self::SESSION_STEP_KEY])
			$this->currentStep = $this->getSESSION()[self::SESSION_STEP_KEY];
		return $this;
	}

	private function setRealtyObjectdId()
	{
		if ( (int)$this->getREQUEST()['action'] ) {
			if ( $this->checkUserRightForRealtyAndBlock((int)$this->getREQUEST()['action']) ) {
				$id = (int)$this->getREQUEST()['action'];
			}
		}
		if (!empty($id)) {
			if (is_int($id) && $id != 0 ) {
				$this->realtyObjectId = $id;
			}
		} else {
			$this->realtyObjectId = null;
		}
		return $this;
	}

	private function rent()
	{
		$this->includeMainTemlate();
	}

	private function issetRealtyObject()
	{
		return !!$this->realtyObjectId;
	}

	private function setRealtyObject()
	{
		if ($this->issetRealtyObject()){
			$this->realtyObject = $this->getObjectByCode($this->realtyObjectId);
			$this->checkUserRightForRealtyAndBlock($this->realtyObject->code);
		} else {
			$this->realtyObject = $this->getNoop();
		}
		return $this;
	}

	private function getObjectByCode($code)
	{
		$objects = new $this->objectsClass;
		return $objects->getObjectByCode($code);
	}

	private function checkUserRightForRealtyAndBlock($code)
	{
		if (!$this->checkUserRightForRealty($code))
			if (empty($this->getAuthorizatedUserId()))
				return;
			else
				$this->redirect404();
		return $this;
	}

	private function checkUserRightForRealty($code)
	{
		$object = $this->getObjectByCode($code);
		if ($object) {
			return ($object->clientId === $this->getAuthorizatedUserId());
		}
		
		return false;
	}


	private function includeMainTemlate()
	{
		$this->setContent('bodyType', empty($_REQUEST['action'])?'more_bg':'article')->includeTemplate('newObject/main');
	}

	private function includeCurrentStep()
	{
		$method = 'includeStep'.$this->getCurrentStep();
		$this->$method();
		return $this;
	}

	private function includeStep1()
	{
		$this->setContent('types', $this->getRealtiesCategories())
			 ->includeTemplate('newObject/step1');
	}

	private function includeAccordion()
	{
		$this->setContent('types', $this->getRealtiesCategories())
			 ->setContent( 'sleeps', $this->getPropertyValuesByPropertyAlias('sleeps'))
			 ->setContent( 'near', new \modules\properties\lib\Property(159) )
			 ->setContent( 'parameters', $this->getParametersToSecondStep() )
			 ->includeTemplate( 'newObject/accordion' );
	}

	private function getParametersToSecondStep()
	{
		$parameters = new \modules\parameters\lib\Parameters;
		$parameters->setSubquery(' AND `alias` IN (\'tehnika\', \'facilities\', \'facilities-techno\') ');
		return $parameters;
	}

	private function getPropertyValuesByPropertyAlias($alias)
	{
		$properties = new \modules\properties\lib\Properties;
		return $properties->getObjectByAlias($alias)->getPropertyValues();
	}

	private function includeStep3()
	{
		$this->includeTemplate('newObject/step3');
	}

	private function saveFirstStep()
	{
		$test = ( isset($_REQUEST['objectId']) );
		return $test
			? $this->updateObject($this->getPOST())
			: $this->createObject($this->getPOST());
	}

	private function updateObject($data)
	{
		$object = $this->getRealtyById($data['objectId']);
		$objectSaveRes = $object->edit(array(
					'categoryId' => $data->categoryId,
					'guests'     => $data->guests,
					'rooms'      => $data->rooms,
					'minRent'    => $data->minRent,
		));

		$this->setObject($object->getAddress());
		$citySaveRes = $this->modelObject->edit(array('cityId' => (int)$data->cityId));
		if (!$citySaveRes) {
			return $this->ajax($citySaveRes, 'ajax', true);
		}
		$this->ajax($objectSaveRes, 'ajax', true);
	}

	private function getRealtyObject()
	{
		return $this->realtyObject;
	}

	private function createObject($data)
	{
		$this->setObject($this->getRealtiesObject()->getAddresses());
		$addressId = $this->modelObject->add(array(
			'cityId' => $data->cityId,
		));

		if ( is_int($addressId) ) {
			$realtyId = $this->getRealtiesObject()->add(
				array(
					'statusId'      => $this->addingStatus,
					'categoryId'    => $data->categoryId,
					'clientId'      => $this->getAuthorizatedUserId(),
					'guests'        => $data->guests,
					'rooms'         => $data->rooms,
					'addressId'     => $addressId,
					'addingSubstep' => $data->addingSubstep,
					'minRent'       => $data->minRent,
					'anyTime'       => 1
				)
			);
			$this->setStep(2);
			$this->setObject($this->getRealtiesObject())->ajax($realtyId, 'ajax', true);
		} else {
			$this->ajax($addressId, 'ajax', true);
		}
		return false;
	}

	private function setNextStep()
	{
		return $this->setStep($this->getCurrentStep()+1);
	}

	private function setStep($number)
	{
		$_SESSION[self::SESSION_STEP_KEY] = $this->currentStep = $number;
		return $this;
	}

	private function getCurrentStep()
	{
		return $this->issetRealtyObject() ? $this->currentStep : 1;
	}

	public function isFirstStep()
	{
		return ($this->getCurrentStep() == 1);
	}

	public function isSecondStep()
	{
		return ($this->getCurrentStep() == 2);
	}

	public function isThirdStep()
	{
		return ($this->getCurrentStep() == 3);
	}

	public function getRealtiesCategories()
	{
		$categories = $this->getRealtiesObject()->getCategories();
		$categories->setSubquery('AND `id` NOT IN ('.implode(',',$this->realtiesExcludedCategories).')');
		return $categories;
	}

	private function getRealtiesObject()
	{
		if (!isset($this->realtiesObject))
			$this->realtiesObject = new $this->objectsClass;
		return $this->realtiesObject;
	}

	/* Start: ajax methods */
	protected function ajaxGetCurrentStep()
	{
		$this->includeCurrentStep();
	}

	protected function ajaxGetStep1()
	{
		$this->includeStep1();
	}

	protected function ajaxGetStep2()
	{
		$this->includeStep2();
	}

	protected function ajaxGetStep3()
	{
		$this->includeStep3();
	}

	protected function ajaxSaveFirstStep()
	{
		$this->saveFirstStep($this->getPOST());
	}
	/*   End: ajax methods */

	public function ajaxGetPricesTable()
	{
		echo $this->includePricesTable($this->getGET()->objectId);
	}

	public function includePricesTable($objectId)
	{
		$object = ($objectId) ? new $this->objectClass($objectId) : $this->getNoop();
		$this->setContent('pricePeriods', $object->getPrices())
			 ->setContent('object', $object)
			 ->setContent('months', \core\utils\Months::getMonthsDeclension())
			 ->includeTemplate('newObject/prices/pricesTable');
	}

	public function ajaxAddYourPrice()
	{
		$post = $this->getPOST();

		$startDay   = isset(explode('-', $post->startPeriod)[0]) ? explode('-', $post->startPeriod)[0] : null ;
		$startMonth = isset(explode('-', $post->startPeriod)[1]) ? explode('-', $post->startPeriod)[1] : null;
		$endDay     = isset(explode('-', $post->endPeriod)[0]) ? explode('-', $post->endPeriod)[0] : null;
		$endMonth   = isset(explode('-', $post->endPeriod)[1]) ? explode('-', $post->endPeriod)[1] : null;

		$this->addPrice(new \core\ArrayWrapper(array(
			'startDay'     => $startDay,
			'startMonth'   => $startMonth,
			'endDay'       => $endDay,
			'endMonth'     => $endMonth,

			'name'         => $post->name,
			'objectId'     => $post->objectId,
			'dayPrice'     => $post->dayPrice,
			'weekendPrice' => $post->weekendPrice,
			'weekPrice'    => $post->weekPrice,
			'monthPrice'   => $post->monthPrice,
		)));
	}

	public function ajaxAddOnePrice()
	{
		if ($this->isNotNoop($this->getPOST()->id))
			$this->editPrice($this->getPOST());
		else
			$this->addPrice($this->getPOST());

	}

	private function addPrice($post)
	{
		$object = $this->getRealtyById($post->objectId);
		$this->setObject($object->getPrices())
			 ->ajax($this->modelObject->add($post));
	}

	protected function ajaxEditPrice()
	{
		$post = $this->getPOST();

		foreach( $post->period as $id => $period ) {
			$period = new \core\ArrayWrapper($period);
			$startDay   = isset(explode('-', $period->startPeriod)[0]) ? explode('-', $period->startPeriod)[0] : null ;
			$startMonth = isset(explode('-', $period->startPeriod)[1]) ? explode('-', $period->startPeriod)[1] : null;
			$endDay     = isset(explode('-', $period->endPeriod)[0]) ? explode('-', $period->endPeriod)[0] : null;
			$endMonth   = isset(explode('-', $period->endPeriod)[1]) ? explode('-', $period->endPeriod)[1] : null;

			$this->editPrice(new \core\ArrayWrapper(array(
				'startDay'     => $startDay,
				'startMonth'   => $startMonth,
				'endDay'       => $endDay,
				'endMonth'     => $endMonth,

				'id'           => $id,
				'name'         => $period->name,
				'objectId'     => $post->objectId,
				'dayPrice'     => $period->dayPrice,
				'weekendPrice' => $period->weekendPrice,
				'weekPrice'    => $period->weekPrice,
				'monthPrice'   => $period->monthPrice,
			)));
		}
	}

	private function editPrice($post)
	{
		$object = new $this->objectClass($post->objectId);
		$this->ajaxResponse($object->getPrices()->getObjectById($post->id)->edit($post));
	}


	protected function ajaxDeletePrice()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$this->ajaxResponse($object->getPrices()->getObjectById($this->getPOST()->id)->delete());
	}

	protected function ajaxEditField()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$field = array_keys($_POST)[0];
		$value = array_shift($_POST);

		$this->ajaxResponse($object->editField($value, $field));
	}

	protected function ajaxEditImportantField()
	{
		if ( $this->getPOST()->objectId ) {
			$object = new $this->objectClass($this->getPOST()->objectId);
			$field = array_keys($_POST)[0];
			$value = array_shift($_POST);

			$this->ajaxResponse($object->editField($value, $field));
		} else {
			$this->ajaxResponse(false);
		}
	}

	protected function ajaxEditEntryTime()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$field = array_keys($_POST)[0];
		$value = array_shift($_POST);

		if( $res = $object->editField($value, $field) )
			return $this->ajaxResponse($object->editField(0, 'anyTime'));

		$this->ajaxResponse($res);
	}

	protected function ajaxEditPersonalInfo()
	{

		$field = array_keys($_POST)[0];
		$value = array_shift($_POST);

		$obj = $this->getAuthorizatedUser();
		
		$this->setObject($obj);
		$this->ajax($obj->editField($value, $field), 'ajax');
	}

	protected function ajaxEditGuestsPay()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		$field = array_keys($_POST)[0];
		$value = array_shift($_POST);

		if( $res = $object->editField($value, $field) )
			return $this->ajaxResponse($object->editField(0, 'guestsFixed'));

		$this->ajaxResponse($res);
	}

	protected function ajaxEditParameter()
	{
		$object = new $this->objectClass($this->getPOST()->objectId);
		if ($this->getPOST()->valueId) {
			$object->removeParameter($this->getPOST()->parameterId);
			$this->ajaxResponse(0);
		} else {
			$object->addParameter($this->getPOST()->parameterId);
			$this->ajaxResponse(1);
		}
	}

	protected function ajaxSaveSecondStep ()
	{
		$this->setStep(3)->ajaxResponse(true);
	}

	protected function ajaxSaveThirdStep ()
	{
		$object = $this->getRealtyById($this->getPOST()->objectId);
		if ( !$object->getAddress()->isAccept() ) {
			$this->ajaxResponse(array('address'=>'Пожалуйста укажите точно местоположение вашего жилища'));
			return false;
		}
		if ( !$object->hasImages() ) {
			$this->ajaxResponse(array('images'=>'Пожалуйста залейте изображения для завершения наполнения объекта'));
			return false;
		}
		
		if ( !$object->hasSleeps() ) {
			$this->ajaxResponse(array('sleeps'=>'Пожалуйста укажите спальные места'));
			return false;
		}

		if ($this->getAuthorizatedUser()->name === '') {
			$this->ajaxResponse(array('name'=>'Пожалуйста заполните ваше имя'));
			return false;
		}
		
		if ($object->description === '') {
			$this->ajaxResponse(array('description'=>'Пожалуйста заполните описание предложения'));
			return false;
		}
		
		if ($object->name === '') {
			$this->ajaxResponse(array('description'=>'Пожалуйста заполните название предложения'));
			return false;
		}

		if ($this->getAuthorizatedUser()->phone === '') {
			$this->ajaxResponse(array('phone'=>'Пожалуйста заполните ваш телефон'));
			return false;
		}

		$result = $this->setObject($object)->modelObject->editField($object->getConfig()->moderStatus, 'statusId');
		if ( $result ) {
			$this->resetAdding();
		}
		$this->ajax($result, 'ajax', true);
	}

	protected function ajaxSaveAddressDetails()
	{
		$realty = $this->getRealtyById($this->getPOST()->objectId);
		$this->setObject($realty->getAddress());
		$this->ajax($this->modelObject->edit($this->getPOST()), 'ajax');
	}

	public function resetAdding()
	{
		$this->realtyObjectId = null;
		$this->setStep(1);
	}

	public function setStepFromGET()
	{
		$this->setStep((int)$this->getGET()->step);
	}

	protected function ajaxSaveSubstep()
	{
		$object = $this->getRealtyById($this->getPOST()->objectId);
		$this->checkUserRightForRealtyAndBlock($object->code);
		$this->ajaxResponse($object->editField($this->getPOST()->addingSubstep, 'addingSubstep'));
	}

	protected function getRealtyObjectPath()
	{
		return ($this->issetRealtyObject()) ? $this->getRealtyObject()->getExampleUrl() : false;
	}

	protected function ajaxGetRealtyObjectPath()
	{
		$this->ajaxResponse($this->getRealtyObjectPath());
	}

	protected function authorization()
	{
		$this->getController('authorization')->authorizationPage();
	}

	protected function uploadImage()
	{
		$imagesFilesUploader = new \core\modules\images\ImagesFileUploader();
		$file = $imagesFilesUploader->upload();
		if (!$file['result']) {
			throw new \Exception('File uploading is broken. Uploader can\'t moves file from system tmp directory. AddFrontController::uploadImage()');
		}
		
		$object = $this->getRealtyById((int)$_REQUEST[0]);
		$this->ajaxResponse($object->addImage($file));
	}

	public function removeImage()
	{
		$object = $this->getRealtyById((int)$this->getPOST()->objectId);
		$this->ajaxResponse($object->removeImage((int)$this->getREQUEST()[0]));
	}
	
	protected function setPrimary()
	{
		$realty  = $this->getRealtyById($this->getPost()['objectId']);
		$this->ajaxResponse($realty->setPrimaryImage((int)$_REQUEST[0]));
	}

	protected function ajaxAddStopPeriod()
	{
		$data = $this->getPost();
		$object = $this->getRealtyById($this->getPost()['objectId']);
		$this->checkUserRightForRealtyAndBlock($object->code);
		
		$result = $this->setObject('\modules\realties\components\stopPeriods\lib\StopPeriods', $object)->modelObject->add(
					array(
						'realtyId' => $object->id,
						'date' => date("d-m-Y"),
						'startDate' => $data['startDate'],
						'endDate' => $data['endDate'],
						'description' => $data['description']
					),
					array('realtyId', 'date', 'startDate', 'endDate', 'description')
				);
		$this->ajax($result);
	}

	protected function ajaxRemoveStopPeriod()
	{
		$object = $this->getRealtyById($this->getPost()['objectId']);
		$this->checkUserRightForRealtyAndBlock($object->code);
		$stopPeriodId = $this->getPost()['stopPeriodId'];
		$stopPeriod = $this->getObject('\modules\realties\components\stopPeriods\lib\StopPeriod', $stopPeriodId, $object->getConfig());
		if($stopPeriod->realtyId === $object->id)
			return $this->ajaxResponse ( $this->setObject($stopPeriod)->modelObject->remove() );
		throw new \Exception('Wrong id in '.  get_class($this) );
	}
	
	private function getRealtyById($id = null)
	{
		return is_null($id) 
			? new \core\Noop() 
			: $this->getObject('\modules\realties\lib\Realty', $id);
	}

	protected function ajaxRemoveAllStopPeriods()
	{
		$object = $this->getRealtyById($this->getPost()['objectId']);
		$this->checkUserRightForRealtyAndBlock($object->code);
		$stopPeriods = $this->getCurrentStopPeriods($object->id);
		if($stopPeriods)
			foreach($stopPeriods as $stopPeriod)
				$stopPeriod->remove();
		$this->ajaxResponse(1);
	}

	protected function getCurrentStopPeriods($id)
	{
		$object = $this->getRealtyById($id);
		if ( !$this->checkUserRightForRealty($object->code) ) {
			return new \core\Noop();
		}
		return $this->getObject('\modules\realties\components\stopPeriods\lib\StopPeriods', $object)
				->setSubquery('AND `endDate` >= ?d', \core\utils\Dates::convertDate(date("d-m-Y")))
				->setOrderBy(' `startDate` ASC, `endDate` ASC');
	}

	protected function ajaxGetStopPeriodsTable()
	{
		$this->getStopPeriodsTable($this->getGET()->objectId);
	}
	
	protected function getStopPeriodsTable($id)
	{
		$this->setContent('stopPeriods', $this->getCurrentStopPeriods($id))
			 ->setContent('object', $this->getRealtyById($id) )
			 ->includeTemplate('newObject/calendarTable');
	}
	
	protected function ajaxGetImagesListBlock()
	{
	    $this->getImagesListBlock($this->getPOST()['objectId']);
	}

	protected function getImagesListBlock($objectId)
	{
	    $object = new \core\Noop();
	    if (isset($objectId)) {
		    $object = $this->getObject($this->_config->getObjectClass(), $objectId);
	    }

	    $this->setContent('object', $object) // Need for images template
		     ->includeTemplate('newObject/images/imagesList');
	}
	
	protected function ajaxEditSleepsRelation () {
		if ( !$this->getPOST()->ownerId )
			return $this->ajaxResponse(false);
			
		$object = new $this->objectClass($this->getPOST()->ownerId);
		if ( !$object->isAdding() )
			$object->moveToModeration();

		$this->ajaxResponse($this->editPropertyRelation($this->getPOST()));
	}
	
	protected function ajaxEditDescription () {
		if ( !$this->getPOST()->objectId ) 
			return $this->ajaxResponse(false);
		
		$object = new $this->objectClass($this->getPOST()->objectId);
		$value = array_shift($_POST);

		$this->ajaxResponse( $object->setDescription($value) );
	}
	
	protected function ajaxEditName()
	{
		if ( !$this->getPOST()->objectId )
			return $this->ajaxResponse(false);
			
		$object = new $this->objectClass($this->getPOST()->objectId);
		$value = array_shift($_POST);
		
		$this->ajaxResponse( $object->setName($value) );
	}
	
}
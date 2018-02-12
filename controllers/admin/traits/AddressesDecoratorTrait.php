<?php
//	Для вывода своего темплейта нужно переопределить два метода
//	1. getTemplateWithClient
//	2. getTemplateToSearchClient
//	В них нужно указать путь к другим файлам шаблонов
namespace controllers\admin\traits;
trait AddressesDecoratorTrait
{
	private $addressesClass = '\modules\addresses\lib\Addresses';
	private $addressClass = '\modules\addresses\lib\Address';
	private $locationsClass = '\modules\locations\lib\Locations';
	
	public function getAddressesDecoratorTemplate( $id = NULL )
	{
		if ($id) {
			$this->setContent('object', new $this->objectClass($id))
				 ->includeTemplate('addressesDecoratorTrait/addresses');
		} else {
			$this->includeTemplate('addressesDecoratorTrait/addressesNoop');
		}
	}
	
	public function ajaxGetAddressesTemplate() {
		echo $this->getAddressesTemplate($this->getREQUEST()[0]);
	}
	
	public function getAddressesTemplate( $id )
	{
		$object = new $this->objectClass($id);
		$this->setContent('object', new $this->objectClass($id));
		if ($object->addressId) {
			$this->getTemplateWithAddresses();
		} else {
			$this->getTemplateToSetAddresses();
		}
	}
	
	public function getTemplateWithAddresses()
	{
		$this->includeTemplate('addressesDecoratorTrait/addressDetails');
	}
	
	public function getTemplateToSetAddresses()
	{
		$this->setContent('countries', $this->getCountries())
			 ->includeTemplate('addressesDecoratorTrait/setAddress');
	}
	
	public function getCountries()
	{
		return (new $this->locationsClass)->getCountries();
	}
	
	public function ajaxGetRegionsByCountry()
	{
		$regions = array();
		foreach($this->getRegionsByCountry($this->getPOST()->countryId) as $region) {
			$regions[] = array(
				'value' =>$region->id,
				'name'  => (string)$region->getName()
			);
		}
		echo json_encode($regions);
	}
	
	public function getRegionsByCountry($countryId)
	{
		return (new $this->locationsClass)->getRegionsByCountryId($countryId);
	}
	
	public function ajaxGetCitiesByRegion()
	{
		
		$cities = array();
		foreach($this->getCitiesByRegion($this->getPOST()->regionId) as $city) {
			$cities[] = array(
				'value' => $city->id,
				'name'  => (string)$city->getName()
			);
		}
		echo json_encode($cities);
	}

	public function getCitiesByRegion($regionId)
	{
		return (new $this->locationsClass)->getCitiesByRegionId($regionId);
	}
	
	public function getCitiesByCountryId($cityId)
	{
		return (new $this->locationsClass)->getCitiesByCountryId($cityId);
	}
	
	public function getCities()
	{
		return (new $this->locationsClass)->getCities();
	}

	public function ajaxAddAddressToObject()
	{
		$this->ajaxResponse($this->addressToObject($this->getPOST()->objectId));
	}
	
	public function addressToObject($objectId)
	{
		$object = new $this->objectClass($objectId);
		$addressId = $this->addAddress($objectId);
		if ( $addressId ) {
			$object->editField($addressId, 'addressId');
		}
		return $addressId;
	}
	
	public function ajaxSaveCoordinates()
	{
		$object = new $this->objectClass($this->getGET()->objectId);
		$object->getAddress()->editField($this->getGET()->latitude, 'latitude');
		$object->getAddress()->editField($this->getGET()->longitude, 'longitude');
		
		$this->ajaxResponse(true);
	}


	private function addAddress($objectId)
	{
		$object = new $this->objectClass($objectId);
		$data = array(
			'cityId'    => (int)$this->getCityId(),
			'street'    => $this->getPOST()->street,
			'home'      => $this->getPOST()->home,
			'block'     => $this->getPOST()->block,
			'flat'      => $this->getPOST()->flat,
			'latitude'  => $this->getPOST()->latitude,
			'longitude' => $this->getPOST()->longitude,
		);
		return $object->getAddresses()->add($data);
	}
	
	private function getCityId()
	{
		if ( $this->isNotNoop($this->getPOST()->cityId) ) {
			return $this->getPOST()->cityId;
		} else {
			return $this->addCity();
		}
			
	}
	
	private function addCity()
	{
		$bolgaryId = 428;
		$regionId  = 277660;
		$cityId = $this->getCitiesByCountryId($bolgaryId)
					->add(
						array(
							'countryId'=>$bolgaryId, 
							'regionId'=>$regionId, 
							'name'=>$this->getPOST()->citySearch
						)
					);
		return $cityId;
	}
	
	public function ajaxDeleteAddress()
	{
		$this->ajaxResponse($this->deleteAddress($this->getREQUEST()[0]));
	}
	
	public function deleteAddress($objectId)
	{
		$object = new $this->objectClass($objectId);
		$object->getAddress()->delete();
		return $object->editField(0, 'addressId');
	}
	
	public function ajaxSearchCitiesToAutosuggest()
	{
		$bolgaryId = 428;
		$cities = $this->getCities();
		$cities->setSubquery(' AND `name` LIKE \'%?s%\'', $this->getGET()->q);
		
		foreach ($cities as $object) {
				$json = array();
				$json['value'] = $object->id;
				$json['name'] = (string)$object->getName();
				$data[] = $json;
		}

		if (!isset($data)) {
			$data = array();
		}

		echo json_encode($data);
	}
}

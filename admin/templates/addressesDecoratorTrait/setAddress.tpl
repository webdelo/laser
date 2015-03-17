<div class="setToObjectBlock" data-action="/admin/<?=$this->getREQUEST()['controller']?>/ajaxAddAddressToObject/">
	<div>
		<input 
			type="text" 
			name="citySearch" 
			class="citySearch"
			data-action="/admin/realties/ajaxSearchCitiesToAutosuggest/"
		/>
		<input type="hidden" name="citySuggest" />
	</div>
	
	<div class="detailsCitySearch hide">
		<select 
			class="countryChoose" 
			name="countryId"
			data-action="/admin/<?=$this->getREQUEST()['controller']?>/ajaxGetRegionsByCountry/"
		>
			<option value="0"> - Страна - </option>
			<? foreach( $countries as $country ): ?>
			<option value="<?=$country->id?>"><?=$country->name?></option>
			<? endforeach; ?>
		</select>		
		<select 
			class="regionChoose" 
			name="regionId"
			disabled
			data-action="/admin/<?=$this->getREQUEST()['controller']?>/ajaxGetCitiesByRegion/"
		>
			<option value="0"> - Регион - </option>
		</select>
		<select disabled class="cityChoose" name="cityId">
			<option value="0"> - Город - </option>
		</select>
	</div>
	
	<input type="hidden" name="objectId" value="<?=$object->id?>"/>
	<input class="street" type="text" name="street" value="" placeholder="Улица" />
	<input class="home" type="text" name="home" value="" placeholder="Дом" />
	<input class="block" type="text" name="block" value="" placeholder="Корпус" />
	<input class="flat" type="text" name="flat" value="" placeholder="Квартира" />
	<a class="setToObjectBlockSubmit buttonInContent">Добавить</a>
	<div class="mapCoordinates">
		<input class="latitude" type="text" name="latitude" value="" placeholder="Широта" />
		<input class="longitude" type="text" name="longitude" value="" placeholder="Долгота" />
		<span class="desc">Для более точного определения местоположения на карте</span>
	</div>
	
	
</div>
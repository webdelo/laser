<link rel="stylesheet" type="text/css" href="/admin/css/editClient.css">
<input type="hidden" class="objectId" value="<?=$object->id?>">

	<div class="MainInformation">
		<div class="FirstBlock">
			<div class="LeftBlock">
				<h2 class="BlockCaption">Основная информация</h2>
				<div class="LeftBlockInfo">
					<dl class="definitionsLeft">
						<dt class="cond">ФИО:</dt>
						<dd class="def"><input type="text" name="name" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->name?>" class="transformer editClientInputs nameinput"/> <input type="text" name="surname" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->surname?>" class="transformer editClientInputs nameinput"/> <input type="text" name="patronimic" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->patronimic?>" class="transformer editClientInputs nameinput"/></dd>
						<dt class="cond">Email:</dt>
						<dd class="def email"><?=$object->getLogin()?></dd>
						<dt class="cond">Телефон :</dt>
						<dd class="def"><input type="text" name="phone" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->phone?>" class="transformer editClientInputs teledit telnum"/></dd>
						<dt class="cond">Доп. тел :</dt>
						<dd class="def"><input type="text" name="mobile" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->mobile?>" class="transformer editClientInputs teledit telnum"/></dd>
						<dt class="cond">Дата рождения :</dt>
						<dd class="def"><input type="text" name="birthDate" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->birthDate?>" class="transformer editClientInputs dates"/>-<input type="text" name="birthMonth" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->birthMonth?>" class="transformer editClientInputs dates"/>-<input type="text" name="birthYear" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->birthYear?>" class="transformer editClientInputs dates"/></dd>
					</dl>
				</div>
			</div>

			<div class="LeftBlock">
				<h2 class="BlockCaption">Адрес</h2>
				<div class="LeftBlockInfo">
					
					<p class="Addr"><strong>
						<select class="editClientSelects transformer" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" name="country" style="width:150px;">
							<option value="Россия" <?= $object->country=='Россия' ? 'selected' : ''?>>Россия</option>
						</select>
					</strong><a class="pen editDeliveryButton"></a></p>
					<p class="Addr">р. <strong><input type="text" name="region" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->region?>" class="transformer editClientInputs addressinput"/></strong>, г. <strong><input type="text" name="city" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->city?>" class="transformer editClientInputs addressinput"/></strong>, ул.<strong><input type="text" name="street" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->street?>" class="transformer editClientInputs streetinput"/></strong>, д.<strong><input type="text" name="home" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->home?>" class="transformer editClientInputs addressinput"/></strong>, корп.<strong><input type="text" name="block" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->block?>" class="transformer editClientInputs addressinput"/></strong>, кв.<strong><input type="text" name="flat" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->flat?>" class="transformer editClientInputs addressinput"/></strong></p>
					<p class="Addr">индекс : <strong><input type="text" name="index" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->index?>" class="transformer editClientInputs addressinput"/></strong></p>				
				</div>
			</div>
			
			<div class="LeftBlock">
				<h2 class="BlockCaption">Данные доставки</h2>
				<div class="LeftBlockInfo">
					
					<p class="Addr"><strong>
						<select class="editClientSelects transformer" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" name="deliveryCountry" style="width:150px;">
							<option value="Россия" <?= $object->deliveryCountry=='Россия' ? 'selected' : ''?>>Россия</option>
						</select>
					</strong><a class="pen editDeliveryDataButton"></a></p>
					<p class="Addr">р. <strong><input type="text" name="deliveryRegion" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryRegion?>" class="transformer editClientInputs addressinput"/></strong>, г. <strong><input type="text" name="deliveryCity" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryCity?>" class="transformer editClientInputs addressinput"/></strong>, ул.<strong><input type="text" name="deliveryStreet" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryStreet?>" class="transformer editClientInputs streetinput"/></strong>, д.<strong><input type="text" name="deliveryHome" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryHome?>" class="transformer editClientInputs addressinput"/></strong>, корп.<strong><input type="text" name="deliveryBlock" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryBlock?>" class="transformer editClientInputs addressinput"/></strong>, кв.<strong><input type="text" name="deliveryFlat" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryFlat?>" class="transformer editClientInputs addressinput"/></strong></p>
					<p class="Addr">индекс : <strong><input type="text" name="deliveryIndex" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryIndex?>" class="transformer editClientInputs addressinput"/></strong></p>				
					<p class="Addr">Контактное лицо: <strong><input type="text" name="deliveryPerson" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->deliveryPerson?>" class="transformer editClientInputs addressinput" style="width: 220px;"/></strong></p>
				</div>
			</div>
			
		</div>

		
		
		
		
		
		
	<div id="goodsOrder" class="deliveryEditBlock hide">	
		<div>
    <p class="title">Доставка:</p>
    <div class="deliveryFormAdd" data-action="/admin/clients/editDeliveryAddress/" data-method="post">
			<input type="hidden" name="objectId" value="<?=$object->id?>">
     
            <div class="deliveryAddressBlock">
            <div class="deliveryAddressContent">
				<div class="addressBlock">
					<input type="hidden" name="flexibleAddress" value="1">
					<select name="country" style="width:100px;">
						<option value="Россия" <?= $object->country=='Россия' ? 'selected' : ''?>>Россия</option>
					</select>
					<input style="width: 230px;" type="text" name="region" value="р. <?=$object->region?>" placeholder="Область">
					<input style="width: 220px;" type="text" name="city" value="г. <?=$object->city?>" placeholder="Город">
				</div>
				<div class="addressBlock" style="margin-top: 10px;">
					<input type="text" style="width: 200px;" name="street" value="ул. <?=$object->street?>" placeholder="Улица">
					<input type="text" name="home" value="д. <?=$object->home?>" placeholder="Дом">
					<input type="text" name="block" value="корп. <?=$object->block?>" placeholder="Корпус">
					<input type="text" name="flat" value="кв. <?=$object->flat?>" placeholder="Квартира">
					<input type="text" name="index" value="<?=$object->index?>" placeholder="Индекс">
				</div>
			</div>
		</div>
		<input class="deliveryFormAddSubmit" type="button" disabled="" value="Добавить доставку">
    </div>
</div>
</div>		
		
	<div id="goodsOrder" class="deliveryDataBlock hide">
			<div>
		<p class="title">Данные доставки:</p>
		<div class="deliveryDataAdd" data-action="/admin/clients/editDeliveryAddress/" data-method="post">
				<input type="hidden" name="objectId" value="<?=$object->id?>">

				<div class="deliveryAddressBlock">
				<div class="deliveryAddressContent">
					<div class="addressBlock">
						<input type="hidden" name="flexibleAddress" value="1">
						<select name="deliveryCountry" style="width:100px;">
							<option value="Россия" <?= $object->deliveryCountry=='Россия' ? 'selected' : ''?>>Россия</option>
						</select>
						<input style="width: 230px;" type="text" name="deliveryRegion" value="р. <?=$object->deliveryRegion?>" placeholder="Область">
						<input style="width: 220px;" type="text" name="deliveryCity" value="г. <?=$object->deliveryCity?>" placeholder="Город">
					</div>
					<div class="addressBlock" style="margin-top: 10px;">
						<input type="text" style="width: 200px;" name="deliveryStreet" value="ул. <?=$object->deliveryStreet?>" placeholder="Улица">
						<input type="text" name="deliveryHome" value="д. <?=$object->deliveryHome?>" placeholder="Дом">
						<input type="text" name="deliveryBlock" value="корп. <?=$object->deliveryBlock?>" placeholder="Корпус">
						<input type="text" name="deliveryFlat" value="кв. <?=$object->deliveryFlat?>" placeholder="Квартира">
						<input type="text" name="deliveryIndex" value="<?=$object->deliveryIndex?>" placeholder="Индекс">
					</div>
				</div>
			</div>
			<input class="deliveryFormAddSubmit" type="button" disabled="" value="Добавить доставку">
		</div>
	</div>
	</div>		
		
		
		
		
		<div class="RightBlock">
			<h2 class="BlockCaption">Авторизационные данные</h2>
			<div class="RightBlockInfo">
				<div class="formChangePassword" data-action="/admin/clients/ajaxEditPassword/" data-method="post" data-post-action="?action=clients=<?=$object->id?>">
					<dl class="definitionsRight">
						<dt class="cond">Логин :</dt>
						<dd class="def email"><?=$object->getLogin()?></dd>
						<dt class="cond" style="margin-top: 5px;">Пароль :</dt>
						<dd class="def"><input type="hidden" name="id" value="<?=$object->id?>" /><input type="password" name="password" class="exp" value /></dd>
						<dt class="cond" style="margin-top: 5px;">Подтверждение :</dt>
						<dd class="def"><input type="password" name="passwordConfirm" /></dd>
					</dl>
					<input type="submit" class="formChangePasswordSubmit changepassbutton" name="changePassword" value="Изменить пароль" />
				</div>
			</div>
		</div>
		
		<div class="RightBlock">
			<h2 class="BlockCaption">Системные данные</h2>
			<div class="RightBlockInfo">
				<dl class="definitionsRight">
					<dt class="cond">Статус :</dt>
					<dd class="def">
						<select class="editClientSelects transformer" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" name="statusId" style="width:150px;">
							<?if ($statuses): foreach($statuses as $status):?>
							<option value="<?=$status->id?>" <?= $status->id==$object->statusId ? 'selected' : ''?>><?=$status->name?></option>
							<?endforeach; endif?>
						</select>
					</dd>

					<dt class="cond">Приоритет :</dt>
					<dd class="def"><strong><input type="text" name="priority" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->priority?>" class="transformer editClientInputs"/></strong></dd>

					<dt class="cond">Организация :</dt>
					<dd class="def"><strong><input type="text" name="company" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->company?>" class="transformer editClientInputs OrganizationData"/></strong></dd>
					<dt class="cond">ИНН :</dt>
					<dd class="def"><strong><input type="text" name="inn" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->inn?>" class="transformer editClientInputs OrganizationData"/></strong></dd>
					<dt class="cond">КПП :</dt>
					<dd class="def"><strong><input type="text" name="kpp" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->kpp?>" class="transformer editClientInputs OrganizationData"/></strong></dd>
					<dt class="cond">ОГРН :</dt>
					<dd class="def"><strong><input type="text" name="ogrn" data-action="/admin/clients/editClient/?objectId=<?=$object->id?>" value="<?=$object->ogrn?>" class="transformer editClientInputs OrganizationData"/></strong></dd>
					
				</dl>
			</div>
		</div>
	</div>

	<div class="BronBlock">
		<h2 class="BlockCaption">Список заказов</h2>
		<div class="BronBlockInfo">
			<div class="ClientObjects">		
				<?if ($object): ?>
				<table class="ObjectsList" width="100%">
					<tr>
						<th class="ObjectsList Idth"><strong>id</strong></th>
						<th class="ObjectsList-031e"><strong>Название, Адрес</strong></th>
						<th class="ObjectsList-031e"><strong>Дата</strong></th>
						<th class="ObjectsList-031e"><strong>Статус</strong></th>
					</tr>
	
				</table>
				<? else: ?>
				<div class="NoBronsBlock">
					<div class="TextArea">
						<p>Нет заказов</p>
					</div>
				</div>
				<? endif; ?>
			</div>
		</div>
	</div>


	<div class="clear"></div>

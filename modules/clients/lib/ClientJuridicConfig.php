<?php
namespace modules\clients\lib;
class ClientJuridicConfig extends \modules\clients\lib\ClientConfig
{
	public function rules()
	{
		return array(
			'name' => array(
				'validation' => array('_validNotEmpty', array('Имя')),
			),
			'surname' => array(
				'validation' => array('_validNotEmpty', array('Фамилия')),
			),
			'phone' => array(
				'validation' => array('_validNotEmpty', array('Телефон')),
			),
			'country' => array(
				'validation' => array('_validNotEmpty'),
			),
			'region' => array(
				'validation' => array('_validNotEmpty', array('Область')),
			),
			'index' => array(
				'validation' => array('_validNotEmpty', array('Индекс')),
			),
			'city' => array(
				'validation' => array('_validNotEmpty', array('Город')),
			),
			'street' => array(
				'validation' => array('_validNotEmpty', array('Улица')),
			),
			'home' => array(
				'validation' => array('_validNotEmpty', array('Дом')),
			),

			'company' => array(
				'validation' => array('_validNotEmpty', array('Организация')),
			),
			'inn' => array(
				'validation' => array('_validNotEmpty', array('ИНН')),
			),
			'kpp' => array(
				'validation' => array('_validNotEmpty', array('КПП')),
			),
			'ogrn' => array(
				'validation' => array('_validNotEmpty', array('ОГРН')),
			),
		);
	}
}
